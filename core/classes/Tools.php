<?php

/**
 * Class Tools
 *
 * Набор утилит для работы с файлами, данными и URL.
 */
class Tools
{
    /**
     * Подключает PHP-файл из папки /include по имени файла (без расширения).
     *
     * @param string $fileName Имя файла без расширения
     * @return void
     */
    public static function includeFile(string $fileName): void
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/include/' . $fileName . '.php';

        if (file_exists($filePath)) {
            include $filePath;
        } else {
            error_log('File not found: ' . $filePath, 3, $_SERVER['DOCUMENT_ROOT'] . '/errors.log');
        }
    }

    /**
     * Добавляет к пути файла параметр с временной меткой последнего изменения файла.
     *
     * @param string $filePath Относительный путь к файлу (от DOCUMENT_ROOT)
     * @return string
     */
    public static function addTimestampToFile(string $filePath): string
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath)) {
            $timestamp = filemtime($_SERVER['DOCUMENT_ROOT'] . $filePath);
            return $filePath . '?v=' . $timestamp;
        } else {
            return $filePath;
        }
    }

    /**
     * Получает и декодирует JSON-данные из файла в папке /data.
     *
     * @param string $fileName Имя файла без расширения .json
     * @return mixed|null
     */
    public static function getData(string $fileName): mixed
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/data/' . $fileName . '.json';
        if (!file_exists($filePath)) {
            error_log('File not found: ' . $filePath, 3, $_SERVER['DOCUMENT_ROOT'] . '/errors.log');
            return null;
        }

        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('JSON decode error: ' . json_last_error_msg(), 3, $_SERVER['DOCUMENT_ROOT'] . '/errors.log');
            return null;
        }
        return $data;
    }

    /**
     * Получает детальные данные из массива по URL.
     *
     * @param string $url URL для анализа
     * @param array $data Массив данных для поиска
     * @return mixed|null
     */
    public static function getDetailData(string $url, array $data): mixed
    {
        $urlParts = explode('/', trim($url, '/'));

        if (count($urlParts) < 2) {
            return null;
        }

        if (count($urlParts) > 2) {
            array_shift($urlParts);
        }

        $secondPart = $urlParts[1];

        foreach ($data as $object) {
            if (isset($object['name']) && $object['name'] === $secondPart) {
                return $object;
            }
        }
        return null;
    }

    /**
     * Получает случайный элемент из JSON-файла в папке /data.
     *
     * @param string $fileName Имя файла без расширения .json
     * @return mixed|null
     */
    public static function getRandomElement(string $fileName): mixed
    {
        $data = self::getData($fileName);

        if (is_array($data) && !empty($data)) {
            $randomKey = array_rand($data);
            return $data[$randomKey];
        }
        return null;
    }

    /**
     * Перемешивает массив случайным образом и возвращает новый массив.
     *
     * @param array $array Входной массив
     * @return array
     */
    public static function randomSort(array $array): array
    {
        $shuffledArray = $array;
        shuffle($shuffledArray);
        return $shuffledArray;
    }

    /**
     * Переключает язык в URL, добавляя или убирая сегмент 'en' в начале пути.
     *
     * @param string $url Исходный URL
     * @return string
     */
    public static function toggleLanguage(string $url): string
    {
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? '';
        $segments = explode('/', trim($path, '/'));

        if (isset($segments[0]) && $segments[0] === 'en') {
            array_shift($segments);
        } else {
            array_unshift($segments, 'en');
        }

        $newPath = '/' . implode('/', $segments);

        if (!str_ends_with($newPath, '/')) {
            $newPath .= '/';
        }

        return (isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '') .
            ($parsedUrl['host'] ?? '') .
            $newPath .
            (isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '') .
            (isset($parsedUrl['fragment']) ? '#' . $parsedUrl['fragment'] : '');
    }

    /**
     * Возвращает текущий URL страницы.
     *
     * @return string
     */
    public static function getCurrentUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $requestUri = $_SERVER['REQUEST_URI'];

        return $protocol . $host . $requestUri;
    }

    /**
     * Формирует теги Hreflang для SEO.
     *
     * @return string
     */
    public static function getHrefLang(): string
    {
        $url = self::getCurrentUrl();
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $server = $protocol . $host;

        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'] ?? '';
        $segments = explode('/', trim($path, '/'));

        if (isset($segments[0]) && $segments[0] === 'en') {
            array_shift($segments);
        }

        $newPath = '/' . implode('/', $segments);

        if (!str_ends_with($newPath, '/')) {
            $newPath .= '/';
        }

        $hrefLangTags = [
            '<link rel="alternate" hreflang="x-default" href="' . htmlspecialchars($server) . '/" />',
            '<link rel="alternate" hreflang="ru" href="' . htmlspecialchars($server . $newPath) . '" />',
            '<link rel="alternate" hreflang="en" href="' . htmlspecialchars($server . '/en' . $newPath) . '" />'
        ];

        return implode("\n", $hrefLangTags) . "\n";
    }

    /**
     * Формирует теги Open Graph для SEO.
     *
     * @param string $pageTitle Заголовок страницы
     * @param string $pageDescription Описание страницы
     * @return string
     */
    public static function getOpenGraphMetaTags(string $pageTitle, string $pageDescription): string
    {
        global $LANG;

        $title = htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8');
        $locale = mb_strtolower($LANG) . '_' . mb_strtoupper($LANG);
        $currentUrl = htmlspecialchars(self::getCurrentUrl(), ENT_QUOTES, 'UTF-8');
        $host = htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES, 'UTF-8');

        $metaTags = [
            '<meta property="og:type" content="website" />',
            '<meta property="og:title" content="' . $title . '" />',
            '<meta property="og:description" content="' . $description . '" />',
            '<meta property="og:url" content="' . $currentUrl . '" />',
            '<meta property="og:locale" content="' . $locale . '" />',
            '<meta property="og:image" content="https://' . $host . '/public/img/og-image.png" />',
            '<meta property="og:image:type" content="image/png" />',
            '<meta property="og:image:width" content="1200" />',
            '<meta property="og:image:height" content="630" />',
        ];

        return implode("\n", $metaTags) . "\n";
    }

    /**
     * Формирует разметку Schema.org для SEO.
     *
     * @param string $name Название сайта или страницы
     * @param string $description Описание сайта или страницы
     * @return string
     */
    public static function getSchemaOrgTags(string $name, string $description): string
    {
        $currentUrl = self::getCurrentUrl();

        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $name,
            'url' => $currentUrl,
            'description' => $description,
            'applicationCategory' => 'UtilitiesApplication',
            'operatingSystem' => 'All',
            'browserRequirements' => 'Modern browser with JavaScript support',
            'creator' => [
                '@type' => 'Person',
                'name' => 'Yuriy Plotnikov',
                'url' => 'https://yuriyplotnikovv.ru/',
            ],
        ];

        $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return "<script type=\"application/ld+json\">\n" . $json . "\n</script>\n";
    }

    /**
     * Записывает данные в кеш.
     *
     * @param string $fileName
     * @param array $data Данные для записи
     * @return void
     */
    public static function setCache(string $fileName, array $data): void
    {
        $cacheFilePath = __DIR__ . '/../../' . ltrim($fileName, '/');
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if ($json === false) {
            Tools::logError('Ошибка кодирования данных в JSON для кеша');
            return;
        }

        if (file_put_contents($cacheFilePath, $json) === false) {
            Tools::logError('Ошибка записи в файл кеша: ' . $cacheFilePath);
        }
    }

    /**
     * Записывает сообщение об ошибке в лог.
     *
     * @param string $message Сообщение об ошибке
     * @return void
     */
    public static function logError(string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[{$timestamp}] {$message}\n";
        @error_log($formattedMessage, 3, __DIR__ . '/../../' . '/errors.log');
    }
}
