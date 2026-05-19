<?php
global $MESS;
?>

<div class="cookie-banner" id="cookieBanner" hidden>
    <div class="cookie-banner__main" id="cookieMain">
        <p class="cookie-banner__text">
            <?= $MESS['COOKIE_BANNER_TEXT'] ?>
        </p>

        <div class="cookie-banner__buttons">
            <button class="cookie-banner__button button button--shadow"
                    type="button"
                    data-cookie-action="acceptAll"
            >
                <?= $MESS['COOKIE_ACCEPT_ALL'] ?>
            </button>

            <button class="cookie-banner__button button button--shadow"
                    type="button"
                    data-cookie-action="acceptNecessary"
            >
                <?= $MESS['COOKIE_ACCEPT_NECESSARY'] ?>
            </button>

            <button class="cookie-banner__button button button--shadow"
                    type="button"
                    data-cookie-action="showSettings"
            >
                <?= $MESS['COOKIE_SETTINGS'] ?>
            </button>
        </div>
    </div>

    <div class="cookie-banner__settings" id="cookieSettings" hidden>
        <button class="cookie-banner__close"
                type="button"
                data-cookie-action="hideSettings"
                aria-label="<?= $MESS['COOKIE_CLOSE_SETTINGS'] ?>"
        >
            ✕
        </button>

        <div class="cookie-banner__categories">
            <label class="cookie-banner__category">
                <input class="visually-hidden" type="checkbox" name="necessary" checked disabled>

                <span>
                    <span class="cookie-banner__category-title">
                        <?= $MESS['COOKIE_CATEGORY_NECESSARY'] ?>
                    </span>

                    <span class="cookie-banner__category-description">
                        <?= $MESS['COOKIE_CATEGORY_NECESSARY_DESC'] ?>
                    </span>
                </span>
            </label>

            <label class="cookie-banner__category">
                <input class="visually-hidden" type="checkbox" name="performance" data-cookie-category="performance">

                <span>
                    <span class="cookie-banner__category-title">
                        <?= $MESS['COOKIE_CATEGORY_PERFORMANCE'] ?>
                    </span>

                    <span class="cookie-banner__category-description">
                        <?= $MESS['COOKIE_CATEGORY_PERFORMANCE_DESC'] ?>
                    </span>
                </span>
            </label>

            <label class="cookie-banner__category">
                <input class="visually-hidden" type="checkbox" name="functional" data-cookie-category="functional">

                <span>
                    <span class="cookie-banner__category-title">
                        <?= $MESS['COOKIE_CATEGORY_FUNCTIONAL'] ?>
                    </span>

                    <span class="cookie-banner__category-description">
                        <?= $MESS['COOKIE_CATEGORY_FUNCTIONAL_DESC'] ?>
                    </span>
                </span>
            </label>

            <label class="cookie-banner__category">
                <input class="visually-hidden" type="checkbox" name="targeting" data-cookie-category="targeting">

                <span>
                    <span class="cookie-banner__category-title">
                        <?= $MESS['COOKIE_CATEGORY_TARGETING'] ?>
                    </span>

                    <span class="cookie-banner__category-description">
                        <?= $MESS['COOKIE_CATEGORY_TARGETING_DESC'] ?>
                    </span>
                </span>
            </label>
        </div>

        <div class="cookie-banner__buttons">
            <button class="cookie-banner__button button button--shadow"
                    type="button"
                    data-cookie-action="saveSelected"
            >
                <?= $MESS['COOKIE_SAVE_SELECTED'] ?>
            </button>

            <button class="cookie-banner__button button button--shadow"
                    type="button"
                    data-cookie-action="acceptAll"
            >
                <?= $MESS['COOKIE_ACCEPT_ALL'] ?>
            </button>

            <button class="cookie-banner__button button button--shadow"
                    type="button"
                    data-cookie-action="acceptNecessary"
            >
                <?= $MESS['COOKIE_ACCEPT_NECESSARY'] ?>
            </button>
        </div>
    </div>
</div>
