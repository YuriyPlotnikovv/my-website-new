<?php

namespace core;
require_once __DIR__ . '/Tools.php';

use core\Tools;
use DateTime;

/**
 * Class GitHubProjectsFetcher
 *
 * Получение и сохранение списка данных о проектах из GitHub.
 */
class GitHubProjectsFetcher
{
    private const CACHE_FILE = '/data/projects-list.json';
    private const LOCAL_IMAGE_DIR = '/data/img/projects/';
    private const METADATA_FOLDER = '.info';
    private const PROJECT_FILE = 'project.json';
    private const DEFAULT_IMAGE_FILE = 'poster.webp';
    private const HTTP_TIMEOUT = 10;
    private const USER_AGENT = 'PHP-GithubProjectsFetcher';
    private string $username;
    private ?string $accessToken;

    /**
     * GithubProjectsFetcher constructor.
     *
     * @param string $username Имя пользователя GitHub
     * @param string|null $accessToken Токен доступа GitHub
     */
    public function __construct(string $username, ?string $accessToken = null)
    {
        $this->username = $username;
        $this->accessToken = $accessToken;
    }

    /**
     * Запрашивает данные из GitHub и сохраняет в кеш.
     *
     * @return array
     */
    public function getProjects(): array
    {
        $repos = $this->fetchRepos();
        if ($repos === null) {
            return [];
        }

        $projects = [];
        foreach ($repos as $repo) {
            $projectData = $this->fetchProjectData($repo['name']);

            if ($projectData !== null) {
                $projectData['repository'] = $repo['html_url'];
                $projectData['name'] = $repo['name'];

                if (empty($projectData['image'])) {
                    $imagePath = self::METADATA_FOLDER . '/' . self::DEFAULT_IMAGE_FILE;
                    $localImageUrl = $this->fetchAndCacheFile($repo['name'], $imagePath);

                    if ($localImageUrl !== null) {
                        $projectData['image'] = $localImageUrl;
                    }
                }

                $languagesInfo = $this->fetchLanguages($repo['name']);
                if ($languagesInfo !== null) {
                    $projectData['languages'] = $languagesInfo;
                }

                $projects[] = $projectData;
            }
        }

        usort($projects, static function ($a, $b) {
            $dateA = '';
            $dateB = '';

            $dateTimeA = $a['date'] ? DateTime::createFromFormat('m.Y', $a['date']) : null;
            if ($dateTimeA) {
                $dateA = $dateTimeA->format('Y-m');
            }

            $dateTimeB = $b['date'] ? DateTime::createFromFormat('m.Y', $b['date']) : null;
            if ($dateTimeB) {
                $dateB = $dateTimeB->format('Y-m');
            }

            return $dateB <=> $dateA;
        });

        Tools::setCache(self::CACHE_FILE, $projects);

        return $projects;
    }

    /**
     * Получает список репозиториев пользователя GitHub.
     *
     * @return array|null
     */
    private function fetchRepos(): ?array
    {
        $url = "https://api.github.com/users/{$this->username}/repos";

        $response = $this->makeRequest($url);
        if ($response === null) {
            Tools::logError("Ошибка получения списка репозиториев пользователя {$this->username}");
            return null;
        }

        return $response;
    }

    /**
     * Выполняет HTTP-запрос к GitHub с необходимыми заголовками.
     *
     * @param string $url URL для запроса
     * @return array|null
     */
    private function makeRequest(string $url): ?array
    {
        $headers = [
            'User-Agent: ' . self::USER_AGENT,
        ];
        if ($this->accessToken) {
            $headers[] = "Authorization: token {$this->accessToken}";
        }

        $context = stream_context_create([
            'http' => [
                'header' => implode("\r\n", $headers),
                'timeout' => self::HTTP_TIMEOUT,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);
        if ($response === false) {
            Tools::logError("Ошибка запроса к URL: {$url}");
            return null;
        }

        if (isset($http_response_header)) {
            foreach ($http_response_header as $header) {
                if (preg_match('#^HTTP/\d+\.\d+\s+(\d+)#', $header, $matches)) {
                    $statusCode = (int)$matches[1];
                    if ($statusCode >= 400) {
                        Tools::logError("HTTP ошибка {$statusCode} при запросе к URL: {$url}");
                        return null;
                    }
                    break;
                }
            }
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Tools::logError('Ошибка JSON decode: ' . json_last_error_msg());
            return null;
        }

        if (isset($data['message'])) {
            Tools::logError('GitHub API error: ' . $data['message']);
            return null;
        }

        return $data;
    }

    /**
     * Получает содержимое файла project.json из репозитория.
     *
     * @param string $repoName Название репозитория
     * @return array|null
     */
    private function fetchProjectData(string $repoName): ?array
    {
        $filePath = self::METADATA_FOLDER . '/' . self::PROJECT_FILE;
        $url = "https://api.github.com/repos/{$this->username}/{$repoName}/contents/{$filePath}";
        $response = $this->makeRequest($url);

        if ($response === null || !isset($response['content'])) {
            Tools::logError("Файл {$filePath} не найден в репозитории {$repoName}");
            return null;
        }

        $content = base64_decode($response['content']);
        if ($content === false) {
            Tools::logError("Не удалось декодировать содержимое {$filePath} из репозитория {$repoName}");
            return null;
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Tools::logError("Ошибка парсинга JSON в репозитории {$repoName}: " . json_last_error_msg());
            return null;
        }

        return $data;
    }

    /**
     * Скачивает и сохраняет файл из репозитория GitHub.
     *
     * @param string $repoName Название репозитория
     * @param string $filePath Путь к файлу в репозитории
     * @return string|null
     */
    private function fetchAndCacheFile(string $repoName, string $filePath): ?string
    {
        $filename = $repoName . '-preview.webp';

        $localDir = __DIR__ . '/../../' . self::LOCAL_IMAGE_DIR;
        if (!is_dir($localDir) && !mkdir($localDir, 0755, true) && !is_dir($localDir)) {
            Tools::logError("Не удалось создать директорию {$localDir}");
            return null;
        }

        $localPath = $localDir . $filename;
        if (file_exists($localPath)) {
            return $filename;
        }

        $url = "https://api.github.com/repos/{$this->username}/{$repoName}/contents/{$filePath}";
        $response = $this->makeRequest($url);
        if ($response === null || !isset($response['content'])) {
            Tools::logError("Файл {$filePath} не найден в репозитории {$repoName}");
            return null;
        }

        $content = base64_decode($response['content']);
        if ($content === false) {
            Tools::logError("Ошибка декодирования файла {$filePath} из репозитория {$repoName}");
            return null;
        }

        if (file_put_contents($localPath, $content) === false) {
            Tools::logError("Ошибка записи файла в кеш: {$localPath}");
            return null;
        }

        return $filename;
    }

    /**
     * Получает список языков из репозитория.
     *
     * @param string $repoName
     * @return array|null
     */
    private function fetchLanguages(string $repoName): ?array
    {
        $url = "https://api.github.com/repos/{$this->username}/{$repoName}/languages";
        $response = $this->makeRequest($url);

        if ($response === null || !is_array($response) || empty($response)) {
            return null;
        }

        $totalBytes = array_sum($response);
        if ($totalBytes === 0) {
            return null;
        }

        $languagesPercent = [];
        foreach ($response as $lang => $bytes) {
            $percent = round(($bytes / $totalBytes) * 100, 1);
            $languagesPercent[] = "{$lang} - {$percent}%";
        }

        return $languagesPercent;
    }
}
