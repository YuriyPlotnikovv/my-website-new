<?php
global $LANG, $MESS;
use core\Tools;

$info = Tools::getData('info');
$photosList = Tools::randomSort(Tools::getData('photos-list'));
$technologiesList = Tools::getData('skills-list');
$wayList = Tools::getData('way-list');
?>

<section class="about-page__intro section intro">
    <div class="section__wrapper intro__wrapper intro__wrapper--about">
        <div class="intro__content">
            <h1 class="intro__title">
                <?= $MESS['ABOUT'] ?>
            </h1>

            <p class="intro__text intro__text--first">
                <?= $MESS['ABOUT_TEXT_FIRST'] ?>
            </p>

            <p class="intro__text intro__text--second">
                <?= $MESS['ABOUT_TEXT_SECOND'] ?>
            </p>
        </div>


        <svg class="intro__image" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
            <use xlink:href="/public/img/sprite.svg#page-about"/>
        </svg>

        <?php Tools::includeFile('scroll-button') ?>
    </div>
</section>

<?php if ($photosList): ?>
    <section class="about-page__photo-list section photo-list">
        <div class="section__wrapper photo-list__wrapper slider">
            <h2 class="section__title photo-list__title">
                <?= $MESS['GALLERY_TITLE'] ?>:
            </h2>

            <div class="slider__wrapper swiper-container" data-slides data-pagination>
                <ul class="photo-list__list slider__list swiper-wrapper">
                    <?php foreach ($photosList as $i => $item):
                        if ($i > 9) {
                            break;
                        }

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
                        <li class="photo-list__item slider__item swiper-slide"
                            data-src="<?= htmlspecialchars($photoFullUrl, ENT_QUOTES) ?>"
                        >
                            <img class="photo-list__item-image"
                                 src="<?= htmlspecialchars($photoUrl, ENT_QUOTES) ?>"
                                 alt="<?= htmlspecialchars($title, ENT_QUOTES) ?>"
                                 width="250"
                                 height="300"
                                 loading="lazy"
                            >
                        </li>
                    <?php endforeach; ?>

                </ul>

                <div class="slider__pagination swiper-pagination"></div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if ($technologiesList): ?>
    <section class="about-page__skills-list section skills-list">
        <div class="section__wrapper skills-list__wrapper">
            <h2 class="section__title skills-list__title">
                <?= $MESS['SKILLS_TITLE'] ?>:
            </h2>

            <ul class="skills-list__list">
                <?php foreach ($technologiesList as $item):
                    $code = $item['code'];
                    if (!$code) {
                        continue;
                    }

                    $name = $item['name'];
                    $link = $item['link'];
                    ?>
                    <li class="skills-list__item">
                        <a class="skills-list__item-link"
                            <?php if ($link): ?>
                                href="<?= $link ?>"
                            <?php endif; ?>
                            <?php if ($name): ?>
                                title="<?= $name ?>"
                                aria-label="<?= $name ?>"
                            <?php endif; ?>
                           target="_blank"
                        >
                            <svg class="skills-list__item-icon" width="60" height="60"
                                 xmlns="http://www.w3.org/2000/svg">
                                <use xlink:href="/public/img/sprite.svg#icon-<?= $code ?>"/>
                            </svg>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
<?php endif; ?>

<?php if ($info['about'][$LANG]): ?>
    <section class="about-page__info section info">
        <div class="section__wrapper info__wrapper">
            <h2 class="section__title info__title">
                <?= $MESS['INFO_TITLE'] ?>:
            </h2>

            <div class="info__text-wrapper">
                <?php $text = explode('<br>', $info['about'][$LANG]); ?>
                <?php if ($text): ?>
                    <?php foreach ($text as $item): ?>
                        <p class="info__text">
                            <?= $item ?>
                        </p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if (count($info['facts']) > 0): ?>
    <section class="about-page__info-facts section info-facts">
        <div class="section__wrapper info-facts__wrapper">
            <h2 class="section__title info-facts__title">
                <?= $MESS['INFO_FACTS_TITLE'] ?>:
            </h2>

            <ul class="info-facts__list">
                <?php foreach ($info['facts'] as $item):
                    $title = $item['name'][$LANG];
                    $text = $item['text'][$LANG];
                    ?>
                    <li class="info-facts__item">
                        <h3 class="info-facts__item-title">
                            <?= $title ?>
                        </h3>

                        <p class="info-facts__item-text">
                            <?= $text ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
<?php endif; ?>

<?php if ($info['into'][$LANG]): ?>
    <section class="about-page__info-into section info-into">
        <div class="section__wrapper info-into__wrapper">
            <h2 class="section__title info-into__title">
                <?= $MESS['INFO_INTO_TITLE'] ?>:
            </h2>

            <div class="info-into__text-wrapper">
                <?php $text = explode('<br>', $info['into'][$LANG]); ?>
                <?php if ($text): ?>
                    <?php foreach ($text as $item): ?>
                        <p class="info-into__text">
                            <?= $item ?>
                        </p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if ($wayList): ?>
    <section class="about-page__my-way section my-way">
        <div class="section__wrapper my-way__wrapper">
            <h2 class="section__title my-way__title">
                <?= $MESS['WAY_TITLE'] ?>:
            </h2>

            <ol class="my-way__list">
                <?php foreach ($wayList as $item):
                    $text = $item['text'][$LANG] ?? null;
                    if (!$text) {
                        continue;
                    }

                    $date = $item['date'][$LANG] ?? null;
                    ?>
                    <li class="my-way__item"
                        <?php if ($date): ?>
                            data-date="<?= $date ?>"
                        <?php endif; ?>
                    >
                        <p class="my-way__item-text">
                            <?= $text ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </section>
<?php endif; ?>
