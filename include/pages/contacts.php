<?php
global $LANG, $MESS;
$contactsList = Tools::getData('contacts-list');
?>

<section class="contacts-page__intro section intro">
    <div class="section__wrapper intro__wrapper">
        <div class="intro__content">
            <h1 class="intro__title"><?= $MESS['CONTACTS'] ?></h1>

            <p class="intro__text intro__text--first"><?= $MESS['CONTACTS_TEXT_FIRST'] ?></p>

            <p class="intro__text intro__text--second"><?= $MESS['CONTACTS_TEXT_SECOND'] ?></p>
        </div>


        <svg class="intro__image" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
            <use xlink:href="/public/img/sprite.svg#page-contacts"/>
        </svg>

        <?php Tools::includeFile('scroll-button') ?>
    </div>
</section>

<section class="contacts-page__socials section contacts">
    <div class="section__wrapper contacts__wrapper">
        <h2 class="section__title contacts__title"><?= $MESS['CONTACTS_SOCIALS_TITLE'] ?>:</h2>

        <ul class="contacts__list">
            <?php foreach ($contactsList as $i => $item):
                if ($item['isContact'] === true) continue;
                $link = $item['link'];
                $icon = $item['icon'];
                $title = $item['title'];
                ?>
                <li class="contacts__item">
                    <a class="contacts__item-link link" href="<?= $link ?>" target="_blank" title="<?= $title ?>">
                        <svg class="contacts__item-icon" viewBox="0 0 50 50" width="50" height="50"
                             xmlns="http://www.w3.org/2000/svg">
                            <use xlink:href="/public/img/sprite.svg#<?= $icon ?>"/>
                        </svg>

                        <h2 class="contacts__item-title">
                            <?= $title ?>
                        </h2>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<section class="contacts-page__contacts section contacts">
    <div class="section__wrapper contacts__wrapper">
        <h2 class="section__title contacts__title"><?= $MESS['CONTACTS_LINKS_TITLE'] ?>:</h2>

        <ul class="contacts__list">
            <?php foreach ($contactsList as $i => $item):
                if ($item['isContact'] !== true) continue;
                $link = $item['link'];
                $icon = $item['icon'];
                $title = $item['title'];
                ?>
                <li class="contacts__item">
                    <a class="contacts__item-link link" href="<?= $link ?>" target="_blank" title="<?= $title ?>">
                        <svg class="contacts__item-icon" viewBox="0 0 50 50" width="50" height="50"
                             xmlns="http://www.w3.org/2000/svg">
                            <use xlink:href="/public/img/sprite.svg#<?= $icon ?>"/>
                        </svg>

                        <h2 class="contacts__item-title">
                            <?= $title ?>
                        </h2>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
