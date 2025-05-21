<?php
global $LANG, $MESS;

$url = $_SERVER['REQUEST_URI'];
$projectsList = Tools::getData('projects-list');
$project = Tools::getDetailData($url, $projectsList);

$name = $project['title-' . $LANG];
$image = $project['image'];
$textFirst = $project['textFirst-' . $LANG];
$textSecond = $project['textSecond-' . $LANG];
$technologiesList = $project['technologies'];
$featuresList = $project['features'];
$deployLink = $project['deploy'];
$githubLink = $project['repository'];
?>

<section class="projects-detail-page__intro section intro">
    <div class="section__wrapper intro__wrapper">
        <div class="intro__content">
            <h1 class="intro__title"><?= $name ?></h1>

            <p class="intro__text intro__text--first"><?= $textFirst ?></p>

            <p class="intro__text intro__text--second"><?= $textSecond ?></p>
        </div>

        <img class="intro__image" src="<?= $image ?>" alt="">

        <?php Tools::includeFile('scroll-button') ?>
    </div>
</section>


<section class="projects-detail-page__project-technologies section project-technologies">
    <div class="section__wrapper project-technologies__wrapper">
        <h2 class="section__title project-technologies__title"><?= $MESS['PROJECTS_TECHNOLOGIES_TITLE'] ?>:</h2>

        <ul class="project-technologies__list">
            <?php foreach ($technologiesList as $item):
                $name = $item['name'];
                $icon = $item['icon'];
                $link = $item['link'];
                ?>
                <li class="project-technologies__item">
                    <a class="project-technologies__item-link" href="<?= $link ?>" title="<?= $name ?>" target="_blank">
                        <svg class="project-technologies__item-icon" width="60" height="60"
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

<section class="projects-detail-page__project-info section project-info">
    <div class="section__wrapper project-info__wrapper">
        <h2 class="section__title project-info__title"><?= $MESS['PROJECTS_ABOUT_PROJECT_TITLE'] ?>:</h2>

        <ul class="project-info__features">
            <?php foreach ($featuresList as $i => $item):
                $title = $item['name-' . $LANG];
                $description = $item['description-' . $LANG];
                ?>
                <li class="project-info__features-item">
                    <h3 class="project-info__features-title"><?= $title ?></h3>

                    <div class="project-info__features-description">
                        <?php if (is_array($description)): ?>
                            <ul class="project-info__features-description-list">
                                <?php foreach ($description as $value): ?>
                                    <li class="project-info__features-description-item">
                                        <?= $value ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <?= $description ?>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
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
