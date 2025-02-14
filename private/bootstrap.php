<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.php';

use Dotenv\Dotenv;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// .env vars
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

define('NS_API_BASE_URL', $_ENV['NS_API_BASE_URL']);
define('NS_API_KEY', $_ENV['NS_API_KEY']);
define('API_CACHE_TIME', $_ENV['API_CACHE_TIME']);

$storingen = getOrFetchCachedNsApiResult('/disruptions/v3?isActive=true');

// Template engine
$templateLoader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($templateLoader, [
    'cache' => false,
]);
