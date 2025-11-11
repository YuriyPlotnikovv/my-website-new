<?php
global $LANG, $MESS;

$url = $_SERVER['REQUEST_URI'];
$projectsList = Tools::getData('projects-list');
$technologiesList = Tools::getData('technologies-list');
$project = Tools::getDetailData($url, $projectsList);

$title = $project['title'] ? $project['title'][$LANG] : null;
$image = $project['image'] ? '/data/img/projects/' . $project['image'] : null;
$date = $project['date'] ?? null;
$textFirst = $project['textFirst'] ? $project['textFirst'][$LANG] : null;
$textSecond = $project['textSecond'] ? $project['textSecond'][$LANG] : null;
$technologies = $project['technologies'] ?? null;
$functionality = $project['functionality'] ? $project['functionality'][$LANG] : null;
$pages = $project['pages'] ? $project['pages'][$LANG] : null;
$notImplemented = $project['notImplemented'] ? $project['notImplemented'][$LANG] : null;
$languages = $project['languages'] ?? null;
$deployLink = $project['deploy'] ?? null;
$githubLink = $project['repository'] ?? null;
?>

<section class="projects-detail-page__intro section intro">
    <div class="section__wrapper intro__wrapper">
        <div class="intro__content">
            <? if ($title): ?>
                <h1 class="intro__title">
                    <?= $title ?>
                </h1>
            <? endif; ?>

            <? if ($textFirst): ?>
                <p class="intro__text intro__text--first">
                    <?= $textFirst ?>
                </p>
            <? endif; ?>

            <? if ($textSecond): ?>
                <p class="intro__text intro__text--second">
                    <?= $textSecond ?>
                </p>
            <? endif; ?>
        </div>

        <? if ($image): ?>
            <img class="intro__image"
                 src="<?= $image ?>"
                <? if ($title): ?>
                    alt="<?= $title ?>"
                <? endif; ?>
            >
        <? endif; ?>

        <?php Tools::includeFile('scroll-button') ?>
    </div>
</section>

<? if ($technologies): ?>
    <section class="projects-detail-page__project-technologies section project-technologies">
        <div class="section__wrapper project-technologies__wrapper">
            <h2 class="section__title project-technologies__title">
                <?= $MESS['PROJECTS_TECHNOLOGIES_TITLE'] ?>:
            </h2>

            <ul class="project-technologies__list">
                <?php foreach ($technologies as $item):
                    $technology = array_find($technologiesList, static function ($technology) use ($item) {
                        return $technology['code'] === $item;
                    });
                    if (!$technology) {
                        continue;
                    }

                    $code = $technology['code'];
                    if (!$code) {
                        continue;
                    }

                    $name = $technology['name'];
                    $link = $technology['link'];
                    ?>
                    <li class="project-technologies__item">
                        <a class="project-technologies__item-link"
                            <? if ($link): ?>
                                href="<?= $link ?>"
                            <? endif; ?>
                            <? if ($name): ?>
                                title="<?= $name ?>"
                                aria-label="<?= $name ?>"
                            <? endif; ?>
                           target="_blank"
                        >
                            <svg class="project-technologies__item-icon"
                                 width="60"
                                 height="60"
                                 xmlns="http://www.w3.org/2000/svg"
                            >
                                <use xlink:href="/public/img/sprite.svg#icon-<?= $code ?>"/>
                            </svg>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
<? endif; ?>

<section class="projects-detail-page__project-info section project-info">
    <div class="section__wrapper project-info__wrapper">
        <h2 class="section__title project-info__title">
            <?= $MESS['PROJECTS_ABOUT_PROJECT_TITLE'] ?>:
        </h2>

        <ul class="project-info__features">
            <?php if ($date): ?>
                <li class="project-info__features-item">
                    <h3 class="project-info__features-title">
                        <?= $MESS['PROJECTS_DATE_TITLE'] ?>
                    </h3>

                    <div class="project-info__features-description">
                        <?= $date ?>
                    </div>
                </li>
            <?php endif; ?>

            <?php if ($pages): ?>
                <li class="project-info__features-item">
                    <h3 class="project-info__features-title">
                        <?= $MESS['PROJECTS_PAGES_TITLE'] ?>
                    </h3>

                    <div class="project-info__features-description">
                        <ul class="project-info__features-description-list">
                            <?php foreach ($pages as $item): ?>
                                <li class="project-info__features-description-item">
                                    <?= $item ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>

            <?php if ($functionality): ?>
                <li class="project-info__features-item">
                    <h3 class="project-info__features-title">
                        <?= $MESS['PROJECTS_FUNCTIONALITY_TITLE'] ?>
                    </h3>

                    <div class="project-info__features-description">
                        <ul class="project-info__features-description-list">
                            <?php foreach ($functionality as $item): ?>
                                <li class="project-info__features-description-item">
                                    <?= $item ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>

            <?php if ($notImplemented): ?>
                <li class="project-info__features-item">
                    <h3 class="project-info__features-title">
                        <?= $MESS['PROJECTS_NOT_IMPLEMENTED_TITLE'] ?>
                    </h3>

                    <div class="project-info__features-description">
                        <ul class="project-info__features-description-list">
                            <?php foreach ($notImplemented as $item): ?>
                                <li class="project-info__features-description-item">
                                    <?= $item ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>

            <?php if ($languages): ?>
                <li class="project-info__features-item">
                    <h3 class="project-info__features-title">
                        <?= $MESS['PROJECTS_LANGUAGES_TITLE'] ?>
                    </h3>

                    <div class="project-info__features-description">
                        <ul class="project-info__features-description-list">
                            <?php foreach ($languages as $item): ?>
                                <li class="project-info__features-description-item">
                                    <?= $item ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>
        </ul>

        <?php if ($deployLink || $githubLink): ?>
            <ul class="project-info__links-list">
                <?php if ($deployLink): ?>
                    <li class="project-info__links-item">
                        <a class="project-info__links-item-link" href="<?= $deployLink ?>" target="_blank">
                            <?= $MESS['PROJECTS_DEPLOY_TITLE'] ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($githubLink): ?>
                    <li class="project-info__links-item">
                        <a class="project-info__links-item-link" href="<?= $githubLink ?>" target="_blank">
                            <?= $MESS['PROJECTS_GITHUB_TITLE'] ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
