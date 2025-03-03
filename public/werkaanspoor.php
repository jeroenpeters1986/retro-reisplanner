<?php
require_once __DIR__ . '/../private/bootstrap.php';

// Storingen (en daarmee ook werkzaamheden) worden al opgehaald in bootstrap.php
unset($storingen['DISRUPTION']);
unset($storingen['CALAMITY']);

$plannedDisruption = getOrFetchCachedNsApiResult('/disruptions/v3?isActive=false&type=maintenance');
$plannedMaintenance = sortDisruptions($plannedDisruption);
$storingen['PLANNED'] = $plannedMaintenance['MAINTENANCE'] ?? [];

// Render de template en geef de data door
echo $twig->render('werkaanspoor.html.twig', [
    'disruptions'       => $storingen,
    'aantalStoringen'   => $aantalStoringen,
    'page'              => 'werkaanspoor',
    'titles'             => [
        'MAINTENANCE' => 'Actieve werkzaamheden',
        'PLANNED' => 'Geplande werkzaamheden',
    ]
]);
