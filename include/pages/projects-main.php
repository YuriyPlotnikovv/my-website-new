<?php
global $LANG, $MESS, $PATH;

$projectsList = Tools::getData('projects-list');
?>

<section class="projects-page__intro section intro">
    <div class="section__wrapper intro__wrapper">
        <div class="intro__content">
            <h1 class="intro__title"><?= $MESS['PROJECTS'] ?></h1>

            <p class="intro__text intro__text--first"><?= $MESS['PROJECTS_TEXT_FIRST'] ?></p>

            <p class="intro__text intro__text--second"><?= $MESS['PROJECTS_TEXT_SECOND'] ?></p>
        </div>


        <svg class="intro__image" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
            <use xlink:href="/public/img/sprite.svg#page-projects"/>
        </svg>

        <?php Tools::includeFile('scroll-button') ?>
    </div>
</section>

<section class="projects-page__works section works">
    <div class="section__wrapper works__wrapper">
        <h2 class="section__title works__title"><?= $MESS['PROJECTS_PROJECTS_TITLE'] ?>:</h2>

        <ul class="works__list">
            <?php foreach ($projectsList as $i => $item):
                $link = $PATH . 'projects/' . $item['name'] . '/';
                $image = $item['image'];
                $title = $item['title-' . $LANG];
                $text = $item['textFirst-' . $LANG];
                ?>
                <li class="works__item">
                    <a class="works__item-link link" href="<?= $link ?>" title="<?= $title ?>">
                        <img class="works__item-image" src="<?= $image ?>" alt="<?= $title ?>" loading="lazy">

                        <div class="works__item-content">
                            <h3 class="works__item-title"><?= $title ?></h3>

                            <p class="works__item-text"><?= $text ?></p>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
