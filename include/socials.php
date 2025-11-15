<?php

use core\Tools;

global $LANG, $MESS;

$socials = Tools::getData('socials-list');
?>

<?php if ($socials): ?>
    <ul class="socials__list">
        <?php foreach ($socials as $social):
            $link = $social['link'];
            if (!$link) {
                continue;
            }

            $icon = $social['icon'];
            $title = $social['title'];
            ?>
            <li class="socials__item">
                <a class="socials__item-link link"
                   href="<?= $link ?>"
                   target="_blank"
                    <?php if ($title): ?>
                        title="<?= $title ?>"
                        aria-label="<?= $title ?>"
                    <?php endif; ?>
                >
                    <?php if ($icon): ?>
                        <svg class="socials__item-icon" viewBox="0 0 50 50" width="50" height="50"
                             xmlns="http://www.w3.org/2000/svg">
                            <use xlink:href="/public/img/sprite.svg#icon-<?= $icon ?>"/>
                        </svg>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
