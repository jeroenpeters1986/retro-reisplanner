<?php
require_once __DIR__ . '/../private/bootstrap.php';


// Storingen worden al opgehaald in bootstrap.php

unset($storingen['MAINTENANCE']);

echo $twig->render('storingen.html.twig', [
    'disruptions'       => $storingen,
    'aantalStoringen'   => $aantalStoringen,
    'page'              => 'storingen',
    'titles'            => [
        'DISRUPTION' => 'Storing',
        'CALAMITY' => 'Calamiteit',
    ]
]);
