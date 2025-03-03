<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.php';

setlocale(LC_ALL, 'nl_NL.UTF-8');

use Dotenv\Dotenv;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;

// .env vars
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

define('NS_API_BASE_URL', $_ENV['NS_API_BASE_URL']);
define('NS_API_KEY', $_ENV['NS_API_KEY']);
define('API_CACHE_TIME', $_ENV['API_CACHE_TIME']);

$disruptionsApiResponse = getOrFetchCachedNsApiResult('/disruptions/v3?isActive=true');
$storingen = sortDisruptions($disruptionsApiResponse);
$aantalStoringen = count($storingen['CALAMITY'] ?? []) + count($storingen['DISRUPTION'] ?? []);

store_theme();

$determineCss = new TwigFunction('getTheme', function () {
    if(isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'classic') {
        return 'classic';
    }
    return 'modern';
});

// Template engine
$templateLoader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($templateLoader, [
    //'cache' => __DIR__ . '/../cache/twigcache',
    'cache' => __DIR__ . '/../cache/twigcache',
    'auto_reload' => true,
]);
$twig->addFunction($determineCss);
