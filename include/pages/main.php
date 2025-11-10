<?php
global $LANG, $MESS, $PATH;

$quote = Tools::getRandomElement('quotes-list');
$text = $quote ? $quote['text-' . $LANG] : null;
$projectsList = Tools::getData('projects-list');
?>

<section class="main-page__intro section intro">
    <div class="section__wrapper intro__wrapper intro__wrapper--main">
        <div class="intro__content">
            <h1 class="intro__title intro__title--main">
                <?= $MESS['MAIN_TITLE'] ?>
            </h1>

            <p class="intro__text intro__text--first">
                <?= $MESS['MAIN_TEXT_FIRST'] ?>
            </p>

            <p class="intro__text intro__text--second">
                <?= $MESS['MAIN_TEXT_SECOND'] ?>
            </p>
        </div>

        <img class="intro__image intro__image--main" src="/public/img/page-main.webp" alt="" width="700" height="885">

        <img class="intro__background" src="/public/img/page-main-bg.svg" alt="" width="960" height="600">

        <?php Tools::includeFile('scroll-button') ?>
    </div>
</section>

<? if ($text): ?>
    <section class="main-page__quote section quote">
        <div class="section__wrapper quote__wrapper">
            <h2 class="section__title quote__title">
                <?= $MESS['MAIN_QUOTES_TITLE'] ?>:
            </h2>

            <p class="quote__text">
                <?= $text ?>
            </p>
        </div>
    </section>
<? endif; ?>

<? if ($projectsList): ?>
    <section class="main-page__works section works">
        <div class="section__wrapper works__wrapper">
            <h2 class="section__title works__title">
                <?= $MESS['MAIN_WORKS_TITLE'] ?>:
            </h2>

            <ul class="works__list">
                <?php foreach ($projectsList as $i => $item):
                    if ($i > 2) {
                        break;
                    }
                    $link = $item['name'] ? $PATH . 'projects/' . $item['name'] . '/' : null;
                    $image = $item['image'] ? '/data/img/projects/' . $item['image'] : null;
                    $title = $item['title'] ? $item['title'][$LANG] : null;
                    $text = $item['textFirst'] ? $item['textFirst'][$LANG] : null;
                    ?>
                    <li class="works__item">
                        <a class="works__item-link link"
                            <? if ($link): ?>
                                href="<?= $link ?>"
                            <? endif; ?>

                            <? if ($title): ?>
                                title="<?= $title ?>"
                            <? endif; ?>
                        >
                            <? if ($image): ?>
                                <img class="works__item-image"
                                     src="<?= $image ?>"

                                    <? if ($title): ?>
                                        alt="<?= $title ?>"
                                    <? endif; ?>

                                     loading="lazy"
                                >
                            <? endif; ?>

                            <? if ($title || $text): ?>
                                <div class="works__item-content">
                                    <? if ($title): ?>
                                        <h3 class="works__item-title">
                                            <?= $title ?>
                                        </h3>
                                    <? endif; ?>

                                    <? if ($text): ?>
                                        <p class="works__item-text">
                                            <?= $text ?>
                                        </p>
                                    <? endif; ?>
                                </div>
                            <? endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
<? endif; ?>
