<?php

$pageTypes = [
  '#^/(?:[a-zA-Z]{2}/)?$#' => [
    'type' => 'main',
  ],
  '#^/(?:[a-zA-Z]{2}/)?about/$#' => [
    'type' => 'about',
  ],
  '#^/(?:[a-zA-Z]{2}/)?contacts/$#' => [
    'type' => 'contacts',
  ],
  '#^/(?:[a-zA-Z]{2}/)?projects/$#' => [
    'type' => 'projects',
  ],
  '#^/(?:[a-zA-Z]{2}/)?projects/[^/]+/$#' => [
    'type' => 'projects-detail',
  ],
];

$currentUrl = $_SERVER['REQUEST_URI'];

global $PAGE_TYPE;
$PAGE_TYPE = null;

function determinePageType($url, $pageTypes)
{
  foreach ($pageTypes as $pattern => $value) {
    if (preg_match($pattern, $url)) {
      return $value['type'];
    }
  }
  return null;
}

$PAGE_TYPE = determinePageType($currentUrl, $pageTypes);
