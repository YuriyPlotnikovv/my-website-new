<?php

namespace core;
require_once __DIR__ . '/Tools.php';

use core\Tools;

/**
 * Class VkPhotoFetcher
 *
 * Получение и сохранение списка фотографий из альбома ВК.
 */
class VkPhotoFetcher
{
    private const CACHE_FILE = '/data/photos-list.json';
    private string|int $ownerId;
    private string $albumId;
    private string $accessToken;
    private int $count;

    /**
     * VkPhotoFetcher constructor.
     *
     * @param int|string $ownerId Id владельца альбома
     * @param string $albumId Id альбома
     * @param string $accessToken Токен доступа VK
     * @param int $count Количество запрашиваемых фото
     */
    public function __construct(int|string $ownerId, string $albumId, string $accessToken, int $count = 100)
    {
        $this->ownerId = $ownerId;
        $this->albumId = $albumId;
        $this->accessToken = $accessToken;
        $this->count = $count;
    }

    /**
     * Запрашивает данные из VK и сохраняет в кеш.
     *
     * @return array|null
     */
    public function getPhotos(): ?array
    {
        $url = "https://api.vk.ru/method/photos.get?owner_id={$this->ownerId}&album_id={$this->albumId}&access_token={$this->accessToken}&count={$this->count}&v=5.199";
        $response = @file_get_contents($url);

        if ($response === false) {
            Tools::logError('Ошибка при выполнении запроса к VK API.');
            return null;
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Tools::logError('JSON decode error: ' . json_last_error_msg());
            return null;
        }

        if (isset($data['error'])) {
            Tools::logError('Ошибка VK API: ' . $data['error']['error_msg']);
            return null;
        }

        if (isset($data['response']['items'])) {
            Tools::setCache(self::CACHE_FILE, $data['response']['items']);
            Tools::logError('Успешно выполнен импорт фото');
            return $data['response']['items'];
        }

        Tools::logError('Неизвестная ошибка при получении фотографий VK.');
        return null;
    }
}
