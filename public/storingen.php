<?php
require_once __DIR__ . '/../private/bootstrap.php';


// Variabele waarin de API-response komt te staan
$responseData = getOrFetchCachedNsApiResult('/disruptions/v3?isActive=true');

$disruptionsApiResponse = getOrFetchCachedNsApiResult('/disruptions/v3?isActive=true');

$disruptions = sortDisruptions($disruptionsApiResponse);

// Render de template en geef de data door
echo $twig->render('storingen.html.twig', [
    'disruptions'       => $disruptions,
    'aantalStoringen'   => count($disruptions['CALAMITY']),
    'page'              => 'storingen',
    'names'             => [
        'DISRUPTION' => 'Storing',
        'CALAMITY' => 'Calamiteit',
        'MAINTENANCE' => 'Werk aan het spoor',
    ]
]);
