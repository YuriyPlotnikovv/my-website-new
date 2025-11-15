<?php
require_once __DIR__ . '/apiKeys.php';
require_once __DIR__ . '/classes/Tools.php';
require_once __DIR__ . '/classes/VkPhotoFetcher.php';
require_once __DIR__ . '/classes/GitHubProjectsFetcher.php';

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
