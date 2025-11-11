<?php
global $MESS, $PATH;
?>
<ul class="navigation__list">
    <li class="navigation__item">
        <a class="navigation__item-link link" href="<?= $PATH ?>about/">
            <?= $MESS['ABOUT'] ?>
        </a>
    </li>

    <li class="navigation__item">
        <a class="navigation__item-link link" href="<?= $PATH ?>projects/">
            <?= $MESS['PROJECTS'] ?>
        </a>
    </li>

    <li class="navigation__item">
        <a class="navigation__item-link link" href="<?= $PATH ?>contacts/">
            <?= $MESS['CONTACTS'] ?>
        </a>
    </li>
</ul>
