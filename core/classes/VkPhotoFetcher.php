<?php

class VkPhotoFetcher
{
    private $ownerId;
    private $albumId;
    private $accessToken;
    private $count;

    private const CACHE_FILE = '/data/photos-list.json';
    private const CACHE_DURATION = 259200;
    private const LOG_FILE = '/errors.log';

    public function __construct($ownerId, $albumId, $accessToken, $count = 100)
    {
        $this->ownerId = $ownerId;
        $this->albumId = $albumId;
        $this->accessToken = $accessToken;
        $this->count = $count;
    }

    public function getPhotos()
    {
        if ($this->isCacheValid()) {
            $photos = $this->getCache();
            if ($photos !== null) {
                return $photos;
            }
        }

        $url = "https://api.vk.com/method/photos.get?owner_id={$this->ownerId}&album_id={$this->albumId}&access_token={$this->accessToken}&count={$this->count}&v=5.199";
        $response = @file_get_contents($url);

        if ($response === FALSE) {
            $this->logError('Ошибка при выполнении запроса к VK API.');
            return null;
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logError("JSON decode error: " . json_last_error_msg());
            return null;
        }

        if (isset($data['error'])) {
            $this->logError('Ошибка VK API: ' . $data['error']['error_msg']);
            return null;
        }

        if (isset($data['response'])) {
            $this->setCache($data['response']['items']);
            return $data['response']['items'];
        }

        $this->logError('Неизвестная ошибка.');
        return null;
    }

    private function isCacheValid()
    {
        $cacheFilePath = $_SERVER['DOCUMENT_ROOT'] . self::CACHE_FILE;
        return file_exists($cacheFilePath) && (time() - filemtime($cacheFilePath) < self::CACHE_DURATION);
    }

    private function getCache()
    {
        $cacheFilePath = $_SERVER['DOCUMENT_ROOT'] . self::CACHE_FILE;
        $cachedData = file_get_contents($cacheFilePath);
        $data = json_decode($cachedData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logError("JSON decode error: " . json_last_error_msg());
            return null;
        }

        return $data;
    }

    private function setCache($data)
    {
        $cacheFilePath = $_SERVER['DOCUMENT_ROOT'] . self::CACHE_FILE;
        if (!file_put_contents($cacheFilePath, json_encode($data))) {
            $this->logError("Ошибка записи в файл кеша: " . $cacheFilePath);
        }
    }

    private function logError($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[{$timestamp}] {$message}\n";
        error_log($formattedMessage, 3, $_SERVER['DOCUMENT_ROOT'] . self::LOG_FILE);
    }
}
