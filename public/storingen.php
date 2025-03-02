<?php
require_once __DIR__ . '/../private/bootstrap.php';


// Storingen worden al opgehaald in bootstrap.php

// Render de template en geef de data door
echo $twig->render('storingen.html.twig', [
    'disruptions'       => $storingen,
    'aantalStoringen'   => count($storingen['CALAMITY']),
    'page'              => 'storingen',
    'names'             => [
        'DISRUPTION' => 'Storing',
        'CALAMITY' => 'Calamiteit',
        'MAINTENANCE' => 'Werk aan het spoor',
    ]
]);
