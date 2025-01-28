<?php

class Tools
{
    public static function includeFile(string $fileName): void
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/include/' . $fileName . '.php';

        if (file_exists($filePath)) {
            include $filePath;
        } else {
            error_log("File not found: " . $filePath, 3, $_SERVER['DOCUMENT_ROOT'] . '/errors.log');
        }
    }

    public static function getData(string $fileName): mixed
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/data/' . $fileName . '.json';
        if (!file_exists($filePath)) {
            error_log("File not found: " . $filePath, 3, $_SERVER['DOCUMENT_ROOT'] . '/errors.log');
            return null;
        }

        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON decode error: " . json_last_error_msg(), 3, $_SERVER['DOCUMENT_ROOT'] . '/errors.log');
            return null;
        }
        return $data;
    }

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

    public static function getRandomElement(string $fileName): mixed
    {
        $data = self::getData($fileName);

        if (is_array($data) && !empty($data)) {
            $randomKey = array_rand($data);
            return $data[$randomKey];
        }
        return null;
    }

    public static function randomSort(array $array): array
    {
        $shuffledArray = $array;

        shuffle($shuffledArray);

        return $shuffledArray;
    }

    public static function getPhotosFormVk(): array
    {
        $ownerId = '';
        $albumId = '';
        $accessToken = '';

        $vkPhotoFetcher = new VkPhotoFetcher($ownerId, $albumId, $accessToken);
        return $vkPhotoFetcher->getPhotos();
    }
}