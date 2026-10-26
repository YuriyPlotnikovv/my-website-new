<?php
require_once __DIR__ . '/bootstrap.php';

use core\VkPhotoFetcher;
use core\GitHubProjectsFetcher;
use core\Env;

$username = 'YuriyPlotnikovv';

$vkPhotoFetcher = new VkPhotoFetcher(Env::get('OWNER_ID'), Env::get('ALBUM_ID'), Env::get('VK_API_KEY'));
$vkPhotoFetcher->getPhotos();

$gitHubProjectsFetcher = new GitHubProjectsFetcher($username, Env::get('GIT_API_KEY'));
$gitHubProjectsFetcher->getProjects();
