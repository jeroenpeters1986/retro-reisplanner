<?php
require_once __DIR__ . '/../private/bootstrap.php';

$stationId    = '';
$error        = '';
$departures   = '';
$stationQuery = $_POST['from'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stationQuery = trim($stationQuery);
    if (!empty($stationQuery)) {
        $params = [
            'q' => $stationQuery,
            'includeNonPlannableStations' => false,
            'limit' => 1,      // We vragen hier 1 resultaat op
            'lang'  => 'nl'
        ];

        // Roep de NS Stations API aan via de endpoint '/nsapp-stations/v3'
        $responseData = nsApiGetRequest('/nsapp-stations/v3', $params);
        $result = json_decode($responseData, true);

        if (isset($result['payload']) && count($result['payload']) > 0) {
            $station = $result['payload'][0];
            if (isset($station['id'])) {
                // In dit voorbeeld gebruiken we de "code" als ID
                $stationId = $station['id']['uicCode'] ?? 'ID niet beschikbaar';
            } else {
                $error = 'Station ID niet gevonden in de API-response.';
            }
        } else {
            $error = 'Geen station gevonden met de naam: ' . htmlspecialchars($stationQuery);
        }
    } else {
        $error = 'Vul alstublieft een stationnaam in.';
    }

    if ($stationId) {
        // API-aanroep voor vertrektijden op basis van UIC-code
        $depParams = [
            'uicCode' => $stationId,
            'lang'    => 'nl',
            'maxJourneys' => 10 // of een ander gewenst aantal
        ];

        $responseDep = nsApiGetRequest('/reisinformatie-api/api/v2/departures', $depParams);
        $departures = json_decode($responseDep, true);
    } else {
        $departures = null;
    }
}

echo $twig->render('vertrektijden.html.twig', [
    'page'              => 'actvertrektijden',
    'stationQuery'      => $stationQuery,
    'departures'        => $departures,
    'stationName'       => $station['names']['long'] ?? htmlspecialchars($stationQuery),
    'aantalStoringen'   => count($storingen['CALAMITY']),
    'stationId'         => $stationId,
    'error'             => $error,
]);
