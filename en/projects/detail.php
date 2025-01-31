<?php
global $MESS, $LANG;
$url = $_SERVER['REQUEST_URI'];
$projectsList = Tools::getData('projects-list');
$project = Tools::getDetailData($url, $projectsList);

if (!$project) {
    header("Location: /404/");
    exit();
}

$pageTitle = $MESS['PROJECT'] . $project['title-' . $LANG] . ' - ' . $MESS['PAGE_TITLE'];
$pageDescription = $project['textSecond-' . $LANG];
?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/header.php"; ?>

<?php Tools::includeFile('/pages/projects-detail'); ?>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/include/footer.php"; ?>
