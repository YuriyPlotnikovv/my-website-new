<?php
global $LANG, $MESS;

$quotesList = Tools::randomSort(Tools::getData('quotes-list'));
$photosList = Tools::randomSort(Tools::getData('photos-list'));
$skillsList = Tools::getData('skills-list');
$wayList = Tools::getData('way-list');
?>

<section class="about-page__intro section intro">
    <div class="section__wrapper intro__wrapper intro__wrapper--about">
        <div class="intro__content">
            <h1 class="intro__title"><?= $MESS['ABOUT'] ?></h1>

            <p class="intro__text intro__text--first"><?= $MESS['ABOUT_TEXT_FIRST'] ?></p>

            <p class="intro__text intro__text--second"><?= $MESS['ABOUT_TEXT_SECOND'] ?></p>
        </div>


        <svg class="intro__image" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
            <use xlink:href="/public/img/sprite.svg#page-about"/>
        </svg>

        <?php Tools::includeFile('scroll-button') ?>
    </div>
</section>

<section class="about-page__quotes-list section quotes-list">
    <div class="section__wrapper quotes-list__wrapper slider">
        <h2 class="section__title quotes-list__title"><?= $MESS['QUOTES_TITLE'] ?>:</h2>

        <div class="slider__wrapper swiper-container" data-single data-pagination>
            <ul class="quotes-list__list slider__list swiper-wrapper">
                <?php foreach ($quotesList as $item):
                    $text = $item['text-' . $LANG];
                    ?>
                    <li class="quotes-list__item slider__item swiper-slide">
                        <p class="quotes-list__item-text">
                            <?= $text ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="slider__pagination swiper-pagination"></div>
        </div>
    </div>
</section>

<section class="about-page__photo-list section photo-list">
    <div class="section__wrapper photo-list__wrapper slider">
        <h2 class="section__title photo-list__title"><?= $MESS['GALLERY_TITLE'] ?>:</h2>

        <div class="slider__wrapper swiper-container" data-slides data-pagination>
            <ul class="photo-list__list slider__list swiper-wrapper">
                <?php foreach ($photosList as $i => $item):
                    if ($i > 9) break;

                    $photoUrl = null;
                    $photoFullUrl = null;

                    if (isset($item['sizes'])) {
                        foreach ($item['sizes'] as $size) {
                            if ($size['type'] === 'q') {
                                $photoUrl = $size['url'];
                            }
                            if ($size['type'] === 'z') {
                                $photoFullUrl = $size['url'];
                            }
                        }
                    }

                    if (!$photoUrl && isset($item['orig_photo']['url'])) {
                        $photoUrl = $item['orig_photo']['url'];
                    }
                    if (!$photoFullUrl && isset($item['orig_photo']['url'])) {
                        $photoFullUrl = $item['orig_photo']['url'];
                    }

                    $title = $item['text'] ?? '';
                    ?>
                    <li class="photo-list__item slider__item swiper-slide" data-src="<?= htmlspecialchars($photoFullUrl, ENT_QUOTES) ?>">
                        <img class="photo-list__item-image" src="<?= htmlspecialchars($photoUrl, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($title, ENT_QUOTES) ?>" width="250" height="300" loading="lazy">
                    </li>
                <?php endforeach; ?>

            </ul>

            <div class="slider__pagination swiper-pagination"></div>
        </div>
    </div>
</section>

<section class="about-page__skills-list section skills-list">
    <div class="section__wrapper skills-list__wrapper">
        <h2 class="section__title skills-list__title"><?= $MESS['SKILLS_TITLE'] ?>:</h2>

        <ul class="skills-list__list">
            <?php foreach ($skillsList as $item):
                $name = $item['name'];
                $icon = $item['icon'];
                $link = $item['link'];
                ?>
                <li class="skills-list__item">
                    <a class="skills-list__item-link" href="<?= $link ?>" title="<?= $name ?>" target="_blank">
                        <svg class="skills-list__item-icon" width="60" height="60"
                             xmlns="http://www.w3.org/2000/svg">
                            <use xlink:href="/public/img/sprite.svg#<?= $icon ?>"/>
                        </svg>

                        <div class="visually-hidden">
                            <?= $name ?>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<section class="about-page__my-way section my-way">
    <div class="section__wrapper my-way__wrapper">
        <h2 class="section__title my-way__title"><?= $MESS['WAY_TITLE'] ?>:</h2>

        <ol class="my-way__list">
            <?php foreach ($wayList as $i => $item):
                $number = ++$i;
                $text = $item['text-' . $LANG];
                ?>
                <li class="my-way__item" data-number="<?= $number ?>">
                    <p class="my-way__item-text">
                        <?= $text ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
