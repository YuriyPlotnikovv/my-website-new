<?php
global $LANG, $MESS;
$contactsList = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/data/contacts-list.json'), associative: true);
?>

<ul class="socials__list">
    <?php foreach ($contactsList as $i => $contact):
        if ($contact['isContact'] === true) continue;
        $link = $contact['link'];
        $icon = $contact['icon'];
        $title = $contact['title'];
        ?>
        <li class="socials__item">
            <a class="socials__item-link link" href="<?= $link ?>" target="_blank" title="<?= $title ?>">
                <svg class="socials__item-icon" viewBox="0 0 50 50" width="50" height="50"
                     xmlns="http://www.w3.org/2000/svg">
                    <use xlink:href="/public/img/sprite.svg#<?= $icon ?>"/>
                </svg>

                <div class="visually-hidden">
                    <?= $title ?>
                </div>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
