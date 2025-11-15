<?php
global $LANG;
use core\Tools;

$currentUrl = $_SERVER['REQUEST_URI'];
$name = $LANG === 'ru' ? 'en' : 'ru';
$link = Tools::toggleLanguage($currentUrl);
?>

<a class="header__lang-switcher" href="<?= $link ?>" title="<?= $name ?>">
    <?= $name ?>
</a>
