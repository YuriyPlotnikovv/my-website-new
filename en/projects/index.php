<?php
use core\Tools;
global $MESS;

$pageTitle = $MESS['PROJECTS'] . ' - ' . $MESS['PAGE_TITLE'];
$pageDescription = $MESS['PROJECTS_PAGE_DESCRIPTION'];

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/header.php'; ?>

<?php Tools::includeFile('/pages/projects-main'); ?>


<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/include/footer.php'; ?>
