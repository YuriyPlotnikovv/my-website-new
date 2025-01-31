<?php
global $LANG, $MESS, $PATH;
$projectsList = Tools::getData('projects-list');
?>

<section class="main-page__intro section intro">
    <div class="section__wrapper intro__wrapper intro__wrapper--main">
        <div class="intro__content">
            <h1 class="intro__title intro__title--main"><?= $MESS['MAIN_TITLE'] ?></h1>

            <p class="intro__text intro__text--first"><?= $MESS['MAIN_TEXT_FIRST'] ?></p>

            <p class="intro__text intro__text--second"><?= $MESS['MAIN_TEXT_SECOND'] ?></p>
        </div>

        <img class="intro__image intro__image--main" src="/public/img/page-main.webp" alt="" width="700" height="885">

        <svg class="intro__background" viewBox="0 0 960 600" xmlns="http://www.w3.org/2000/svg">
            <use xlink:href="/public/img/sprite.svg#main-bg"/>
        </svg>

        <?php Tools::includeFile('scroll-button') ?>
    </div>
</section>

<section class="main-page__quote section quote">
    <div class="section__wrapper quote__wrapper">
        <h2 class="section__title quote__title"><?= $MESS['MAIN_QUOTES_TITLE'] ?>:</h2>

        <p class="quote__text">
            <?php
            $quote = Tools::getRandomElement('quotes-list');
            $text = $quote['text-' . $LANG];
            ?>
            <?= $text ?>
        </p>
    </div>
</section>

<section class="main-page__works section works">
    <div class="section__wrapper works__wrapper">
        <h2 class="section__title works__title"><?= $MESS['MAIN_WORKS_TITLE'] ?>:</h2>

        <ul class="works__list">
            <?php foreach ($projectsList as $i => $item):
                if ($i > 2) break;
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
