<?php
global $MESS;
$pageTitle = $MESS['CONTACTS'] . ' - ' . $MESS['PAGE_TITLE'];
$pageDescription = $MESS['CONTACTS_PAGE_DESCRIPTION'];

require_once $_SERVER['DOCUMENT_ROOT'] . "/include/header.php"; ?>

<?php Tools::includeFile('/pages/contacts'); ?>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/footer.php"; ?>
