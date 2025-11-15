<?php
use core\Tools;
use DateTime;

global $LANG, $MESS, $PATH;

$projectsList = Tools::getData('projects-list');
$technologiesList = Tools::getData('technologies-list');
?>

<section class="projects-page__intro section intro">
    <div class="section__wrapper intro__wrapper">
        <div class="intro__content">
            <h1 class="intro__title">
                <?= $MESS['PROJECTS'] ?>
            </h1>

            <p class="intro__text intro__text--first">
                <?= $MESS['PROJECTS_TEXT_FIRST'] ?>
            </p>

            <p class="intro__text intro__text--second">
                <?= $MESS['PROJECTS_TEXT_SECOND'] ?>
            </p>
        </div>


        <svg class="intro__image" viewBox="0 0 500 500" xmlns="http://www.w3.org/2000/svg">
            <use xlink:href="/public/img/sprite.svg#page-projects"/>
        </svg>

        <?php Tools::includeFile('scroll-button') ?>
    </div>
</section>

<section class="projects-page__works section works">
    <div class="section__wrapper works__wrapper">
        <h2 class="section__title works__title">
            <?= $MESS['PROJECTS_PROJECTS_TITLE'] ?>:
        </h2>

        <?php if ($projectsList): ?>
            <div class="works__filters">
                <div class="works__filters-categories categories">
                    <span class="categories__label">
                        <?= $MESS['PROJECTS_FILTERS_CATEGORIES'] ?>:
                    </span>

                    <ul class="categories__list">
                        <li class="categories__item">
                            <button class="categories__item-button categories__item-button--active"
                                    type="button"
                                    data-filter-type="category"
                                    data-filter-value="all"
                            >
                                <?= $MESS['PROJECTS_FILTERS_CATEGORIES_ALL'] ?>
                            </button>
                        </li>

                        <li class="categories__item">
                            <button class="categories__item-button"
                                    type="button"
                                    data-filter-type="category"
                                    data-filter-value="personal"
                            >
                                <?= $MESS['PROJECTS_FILTERS_CATEGORIES_PERSONAL'] ?>
                            </button>
                        </li>

                        <li class="categories__item">
                            <button class="categories__item-button"
                                    type="button"
                                    data-filter-type="category"
                                    data-filter-value="training"
                            >
                                <?= $MESS['PROJECTS_FILTERS_CATEGORIES_TRAINING'] ?>
                            </button>
                        </li>

                        <li class="categories__item">
                            <button class="categories__item-button"
                                    type="button"
                                    data-filter-type="category"
                                    data-filter-value="commercial"
                            >
                                <?= $MESS['PROJECTS_FILTERS_CATEGORIES_COMMERCIAL'] ?>
                            </button>
                        </li>
                    </ul>
                </div>

                <?php if ($technologiesList): ?>
                    <div class="works__filters-technologies technologies">
                        <span class="technologies__label">
                            <?= $MESS['PROJECTS_FILTERS_TECHNOLOGIES'] ?>:
                        </span>

                        <ul class="technologies__list">
                            <?php foreach ($technologiesList as $technology):
                                $code = $technology['code'];
                                if (!$code) {
                                    continue;
                                }

                                $name = $technology['name'];
                                ?>
                                <li class="technologies__item">
                                    <button class="technologies__item-button"
                                            type="button"
                                            data-filter-type="technology"
                                            data-filter-value="<?= $code ?>"
                                    >
                                        <?= $name ?>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="works__filters-sorting sorting">
                    <span class="sorting__label">
                        <?= $MESS['PROJECTS_FILTERS_SORTING'] ?>:
                    </span>

                    <button class="sorting__current"
                            type="button"
                            aria-haspopup="listbox"
                            aria-expanded="false"
                            id="sortingButton"
                    >
                        <?= $MESS['PROJECTS_FILTERS_SORTING_DATE_TO_LOW'] ?>

                        <svg class="sorting__icon" width="10" height="10">
                            <use xlink:href="/public/img/sprite.svg#icon-arrow-down"/>
                        </svg>
                    </button>

                    <ul class="sorting__list" role="listbox" tabindex="-1" aria-labelledby="sortingButton">
                        <li class="sorting__option sorting__option--active" role="option" data-value="date-to-low">
                            <?= $MESS['PROJECTS_FILTERS_SORTING_DATE_TO_LOW'] ?>
                        </li>

                        <li class="sorting__option" role="option" data-value="date-to-high">
                            <?= $MESS['PROJECTS_FILTERS_SORTING_DATE_TO_HIGH'] ?>
                        </li>

                        <li class="sorting__option" role="option" data-value="complexity-to-low">
                            <?= $MESS['PROJECTS_FILTERS_SORTING_COMPLEXITY_TO_LOW'] ?>
                        </li>

                        <li class="sorting__option" role="option" data-value="complexity-to-high">
                            <?= $MESS['PROJECTS_FILTERS_SORTING_COMPLEXITY_TO_HIGH'] ?>
                        </li>
                    </ul>
                </div>
            </div>

            <ul class="works__list">
                <?php foreach ($projectsList as $i => $item):
                    $link = $item['name'] ? $PATH . 'projects/' . $item['name'] . '/' : '';
                    $image = $item['image'] ? '/data/img/projects/' . $item['image'] : null;
                    $title = $item['title'][$LANG] ?? null;
                    $text = $item['textFirst'][$LANG] ?? null;
                    $complexity = $item['complexity'] ?? null;

                    $category = $item['category'] ?? null;
                    $technologies = $item['technologies'] ? implode(',', $item['technologies']) : [];

                    $date = '';
                    $dateTime = $item['date'] ? DateTime::createFromFormat('m.Y', $item['date']) : null;
                    if ($dateTime) {
                        $date = $dateTime->format('Y-m');
                    }
                    ?>
                    <li class="works__item"
                        <?php if ($date): ?>
                            data-date="<?= $date ?>"
                        <?php endif; ?>
                        <?php if ($complexity): ?>
                            data-complexity="<?= $complexity ?>"
                        <?php endif; ?>
                        <?php if ($category): ?>
                            data-category="<?= $category ?>"
                        <?php endif; ?>
                        <?php if (!empty($technologies)): ?>
                            data-technologies="<?= $technologies ?>"
                        <?php endif; ?>
                    >
                        <a class="works__item-link link"
                            <?php if ($link): ?>
                                href="<?= $link ?>"
                            <?php endif; ?>
                            <?php if ($title): ?>
                                title="<?= $title ?>"
                            <?php endif; ?>
                        >
                            <?php if ($image): ?>
                                <img class="works__item-image"
                                     src="<?= $image ?>"
                                    <?php if ($title): ?>
                                        alt="<?= $title ?>"
                                    <?php endif; ?>
                                     loading="lazy"
                                >
                            <?php endif; ?>

                            <?php if ($title || $text): ?>
                                <div class="works__item-content">
                                    <?php if ($title): ?>
                                        <h3 class="works__item-title">
                                            <?= $title ?>
                                        </h3>
                                    <?php endif; ?>

                                    <?php if ($text): ?>
                                        <p class="works__item-text">
                                            <?= $text ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
