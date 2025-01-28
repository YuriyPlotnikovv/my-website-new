<?php
global $MESS, $PAGE_TYPE;
?>

</main>

<footer class="page__footer footer">
    <div class="footer__wrapper">
        <span class="footer__copyright">© 2023 - <?= date('Y') ?> <?= $MESS['FOOTER_AUTHOR'] ?></span>

        <nav class="footer__navigation navigation">
            <?php Tools::includeFile('navigation'); ?>
        </nav>

        <a class="footer__icons link" href="https://icons8.com/" target="_blank"
           title="<?= $MESS['FOOTER_ICONS_AUTHOR'] ?>">
            <span class="footer__icons-text"><?= $MESS['FOOTER_ICONS'] ?></span>

            <svg class="footer__icons-image" viewBox="0 0 25 25" width="25" height="25"
                 xmlns="http://www.w3.org/2000/svg">
                <use xlink:href="/public/img/sprite.svg#icons-author"/>
            </svg>
        </a>
    </div>
</footer>

<?php if ($PAGE_TYPE === 'about'): ?>
    <script src="/public/js/vendor/lightgallery.min.js"></script>
    <script src="/public/js/vendor/lg-zoom.min.js"></script>
    <script src="/public/js/vendor/swiper-bundle.js"></script>
<?php endif; ?>
<script src="/public/js/script.min.js"></script>
</body>
</html>
