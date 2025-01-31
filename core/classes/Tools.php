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

    public static function addTimestampToFile($filePath) {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $filePath)) {
            $timestamp = filemtime($_SERVER['DOCUMENT_ROOT'] . $filePath);
            return $filePath . '?v=' . $timestamp;
        } else {
            return $filePath;
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


    public static function toggleLanguage($url): string
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

    public static function getCurrentUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        $requestUri = $_SERVER['REQUEST_URI'];

        return $protocol . $host . $requestUri;
    }

    public static function getHrefLang(): string {
        $url = self::getCurrentUrl();
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $server = $protocol . "://" . $host;

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

    public static function getOpenGraphMetaTags($pageTitle, $pageDescription): string {
        global $LANG;

        $metaTags = [
            '<meta property="og:type" content="website" />',
            '<meta property="og:title" content="' . htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . '" />',
            '<meta property="og:description" content="' . htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') . '" />',
            '<meta property="og:url" content="' . htmlspecialchars(self::getCurrentUrl(), ENT_QUOTES, 'UTF-8') . '" />',
            '<meta property="og:locale" content="' . mb_strtolower($LANG) . '_' . mb_strtoupper($LANG) . '" />',
            '<meta property="og:image" content="https://' . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_QUOTES, 'UTF-8') . '/public/img/og-image.png" />',
            '<meta property="og:image:type" content="image/png">',
            '<meta property="og:image:width" content="1200">',
            '<meta property="og:image:height" content="630">',
        ];

        return implode("\n", $metaTags) . "\n";
    }
}