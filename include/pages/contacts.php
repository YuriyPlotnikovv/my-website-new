<?php

use core\Tools;

global $LANG, $MESS;

$socialsList = Tools::getData('socials-list');
?>

<section class="contacts-page__intro section intro">
    <div class="section__wrapper intro__wrapper">
        <div class="intro__content">
            <h1 class="intro__title">
                <?= $MESS['CONTACTS'] ?>
            </h1>

            <p class="intro__text intro__text--first">
                <?= $MESS['CONTACTS_TEXT_FIRST'] ?>
            </p>

            <p class="intro__text intro__text--second">
                <?= $MESS['CONTACTS_TEXT_SECOND'] ?>
            </p>
        </div>


        <svg class="intro__image" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
            <use xlink:href="/public/img/sprite.svg#page-contacts"/>
        </svg>

        <?php Tools::includeFile('scroll-button') ?>
    </div>
</section>

<?php if ($socialsList): ?>
    <section class="contacts-page__socials section contacts">
        <div class="section__wrapper contacts__wrapper">
            <h2 class="section__title contacts__title">
                <?= $MESS['CONTACTS_SOCIALS_TITLE'] ?>:
            </h2>

            <ul class="contacts__list">
                <?php foreach ($socialsList as $social):
                    $link = $social['link'];
                    if (!$link) {
                        continue;
                    }

                    $icon = $social['icon'];
                    $title = $social['title'];
                    ?>
                    <li class="contacts__item">
                        <a class="contacts__item-link link"
                           href="<?= $link ?>"
                           target="_blank"
                            <?php if ($title): ?>
                                title="<?= $title ?>"
                            <?php endif; ?>
                        >
                            <?php if ($icon): ?>
                                <svg class="contacts__item-icon" viewBox="0 0 50 50" width="50" height="50"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <use xlink:href="/public/img/sprite.svg#icon-<?= $icon ?>"/>
                                </svg>
                            <?php endif; ?>

                            <?php if ($title): ?>
                                <span class="contacts__item-title">
                                    <?= $title ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
<?php endif; ?>

<?php Tools::includeFile('feedback-form') ?>
