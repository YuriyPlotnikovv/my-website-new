<?php
global $MESS;
$pageTitle = $MESS['ABOUT'] . ' - ' . $MESS['PAGE_TITLE'];
$pageDescription = $MESS['ABOUT_PAGE_DESCRIPTION'];

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/header.php';
use core\Tools;
?>

<?php Tools::includeFile('/pages/about'); ?>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/include/footer.php'; ?>
