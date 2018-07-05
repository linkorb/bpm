<?php

use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$envFilename = '.env';
if (file_exists($envFilename)) {
    $dotenv = new Dotenv();
    $dotenv->load($envFilename);
}
