<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

if (is_file(__DIR__ . '/../.env')) {
    (new Dotenv())->load(__DIR__ . '/../.env');
}
