<?php
global $MESS, $PATH, $LANG;

$allowed_languages = ['en', 'ru'];

function getLangFromBrowser($allowed_languages)
{
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $browser_languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

        foreach ($browser_languages as $lang) {
            $lang = substr($lang, 0, 2);
            if (in_array($lang, $allowed_languages)) {
                return $lang;
            }
        }
    }
    return 'en';
}

function getLangFromUrl($uri, $allowed_languages)
{
    $path = parse_url($uri, PHP_URL_PATH);
    $segments = explode('/', trim($path, '/'));

    if (isset($segments[0]) && preg_match('/^[a-zA-Z]{2}$/', $segments[0]) && in_array($segments[0], $allowed_languages)) {
        return $segments[0];
    } else {
        return 'ru';
    }
}

$langFromUrl = getLangFromUrl($_SERVER['REQUEST_URI'], $allowed_languages);
//$langFromBrowser = getLangFromBrowser($allowed_languages);

$lang = $langFromUrl;

$LANG = $lang;
$PATH = $lang === 'ru' ? '/' : '/' . $lang . '/';
$MESS = include $_SERVER['DOCUMENT_ROOT'] . "/lang/{$lang}.php";
