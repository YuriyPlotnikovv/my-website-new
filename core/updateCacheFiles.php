<?php
require_once __DIR__ . '/apiKeys.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/autoload.php';
use core\VkPhotoFetcher;
use core\GitHubProjectsFetcher;

global $ownerId;
global $albumId;
global $vkApiKey;
global $gitHubApiKey;
$username = 'YuriyPlotnikovv';

$vkPhotoFetcher = new VkPhotoFetcher($ownerId, $albumId, $vkApiKey);
$vkPhotoFetcher->getPhotos();

$gitHubProjectsFetcher = new GitHubProjectsFetcher($username, $gitHubApiKey);
$gitHubProjectsFetcher->getProjects();
