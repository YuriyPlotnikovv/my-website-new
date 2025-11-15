<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/core/autoload.php';

use core\FormHelper;

$form = new FormHelper(true);
$form->help();