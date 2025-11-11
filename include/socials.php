<?php
global $LANG, $MESS;
$contactsList = Tools::getData('contacts-list');
?>

<? if ($contactsList): ?>
    <ul class="socials__list">
        <?php foreach ($contactsList as $contact):
            if ($contact['isContact']) {
                continue;
            }

            $link = $contact['link'];
            if (!$link) {
                continue;
            }

            $icon = $contact['icon'];
            $title = $contact['title'];
            ?>
            <li class="socials__item">
                <a class="socials__item-link link"
                   href="<?= $link ?>"
                   target="_blank"
                    <? if ($title): ?>
                        title="<?= $title ?>"
                        aria-label="<?= $title ?>"
                    <? endif; ?>
                >
                    <? if ($icon): ?>
                        <svg class="socials__item-icon" viewBox="0 0 50 50" width="50" height="50"
                             xmlns="http://www.w3.org/2000/svg">
                            <use xlink:href="/public/img/sprite.svg#icon-<?= $icon ?>"/>
                        </svg>
                    <? endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<? endif; ?>
