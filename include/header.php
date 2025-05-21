<?php
global $MESS, $PAGE_TYPE, $PATH, $LANG;
?>

<!DOCTYPE html>
<html class="page" lang="<?= $LANG ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <?php
    if (!isset($pageTitle)) {
        $pageTitle = $MESS['PAGE_TITLE'];
    }

    if (!isset($pageDescription)) {
        $pageDescription = $MESS['PAGE_DESCRIPTION'];
    }
    ?>

    <title><?= $pageTitle ?></title>
    <meta name="description" content="<?= $pageDescription ?>">
    <meta name="keywords" content="<?= $MESS['PAGE_KEYWORDS'] ?>">

    <?= Tools::getHrefLang(); ?>
    <?= Tools::getOpenGraphMetaTags($pageTitle, $pageDescription); ?>
    <?= Tools::getSchemaOrgTags($pageTitle, $pageDescription) ?>

    <link rel="preload" href="/public/fonts/ProximaNova-Bold.woff2" as="font"
          type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/public/fonts/ProximaNova-Light.woff2" as="font"
          type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/public/fonts/ProximaNova-LightIt.woff2" as="font"
          type="font/woff2" crossorigin="anonymous">
    <link rel="preload" href="/public/fonts/ProximaNovaCond-Black.woff2" as="font"
          type="font/woff2" crossorigin="anonymous">
    <link rel="prefetch" href="/public/css/vendor/swiper-bundle.css" as="style">
    <link rel="prefetch" href="/public/css/vendor/lightgallery.min.css" as="style">

    <?php if ($PAGE_TYPE === 'main'): ?>
        <link rel="preload" href="/public/img/page-main.webp" as="image">
    <?php endif; ?>

    <link rel="apple-touch-icon" href="/apple-touch-icon-180x180.png">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="/favicon.ico">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="application-name" content="<?= $MESS['PAGE_TITLE'] ?>>">

    <?php if ($PAGE_TYPE === 'about'): ?>
        <link href="/public/css/vendor/swiper-bundle.css" rel="stylesheet">
        <link href="/public/css/vendor/lightgallery.min.css" rel="stylesheet">
    <?php endif; ?>
    <link href="<?= Tools::addTimestampToFile('/public/css/style.min.css') ?>" rel="stylesheet">

    <?php if ($PAGE_TYPE === 'about'): ?>
        <script src="/public/js/vendor/lightgallery.min.js" defer></script>
        <script src="/public/js/vendor/lg-zoom.min.js" defer></script>
        <script src="/public/js/vendor/swiper-bundle.js" defer></script>
    <?php endif; ?>
    <script src="<?= Tools::addTimestampToFile('/public/js/script.min.js') ?>" defer></script>
</head>

<body class="page__body">

<header class="page__header header">
    <div class="header__wrapper">
        <a href="<?= $PATH ?>" class="header__logo-link link" title="<?= $MESS['MAIN'] ?>">
            <svg class="header__logo-image" viewBox="0 0 70 70" width="70" height="70"
                 xmlns="http://www.w3.org/2000/svg">
                <use xlink:href="/public/img/sprite.svg#logo-image"/>
            </svg>

            <svg class="header__logo-text" width="240" height="30" viewBox="0 0 1018 133"
                 xmlns="http://www.w3.org/2000/svg">
                <use xlink:href="/public/img/sprite.svg#logo-text"/>
            </svg>
        </a>

        <nav class="header__navigation navigation">
            <?php Tools::includeFile('navigation'); ?>
        </nav>

        <div class="header__socials socials">
            <?php Tools::includeFile('socials'); ?>
        </div>

        <?php Tools::includeFile('lang-switcher'); ?>

        <button class="header__menu-button" type="button">
            <span class="visually-hidden"><?= $MESS['MENU'] ?></span>

            <span class="header__menu-button-icon"></span>
        </button>
    </div>
</header>

<main class="page__main <?= $PAGE_TYPE . '-page' ?>">
