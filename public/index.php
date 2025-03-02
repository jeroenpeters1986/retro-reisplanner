<?php
require_once __DIR__ . '/../private/bootstrap.php';


// Variabele waarin de API-response komt te staan
$responseData = '';

// Zet de formuliervelden in een array zodat we ze terug kunnen geven aan de template.
$formData = [
    'fromStation' => $_POST['fromStation'] ?? '',
    'toStation'   => $_POST['toStation'] ?? '',
    'viaStation'  => $_POST['viaStation'] ?? '',
    'date'        => $_POST['date'] ?? date('Y-m-d'),
    'time'        => $_POST['time'] ?? date('H:i'),
    'departure'   => $_POST['departure'] ?? 'true',
    'sortOption'  => $_POST['sortOption'] ?? 'goedkoopste'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Haal en trim formulierwaarden
    $fromStation = trim($_POST['fromStation'] ?? '');
    $toStation   = trim($_POST['toStation'] ?? '');
    $viaStation  = trim($_POST['viaStation'] ?? '');
    $date        = trim($_POST['date'] ?? '');
    $time        = trim($_POST['time'] ?? '');
    $departure   = $_POST['departure'] ?? 'true';
    $sortOption  = $_POST['sortOption'] ?? 'goedkoopste';

    // Gebruik standaardwaarden als datum of tijd niet ingevuld is
    if (!$date) {
        $date = date('Y-m-d');
    }
    if (!$time) {
        $time = date('H:i');
    }
    // Combineer datum en tijd tot een RFC3339-string
    $dateTime = $date . 'T' . $time . ':00';

    // Bouw de queryparameters voor de API-aanroep
    $params = [
        'fromStation' => $fromStation,
        'toStation'   => $toStation,
        'dateTime'    => $dateTime,
        'lang'        => 'nl'
    ];
    if (!empty($viaStation)) {
        $params['viaStation'] = $viaStation;
    }
    // Als gekozen is voor aankomsttijd, geef dit door
    if ($departure === 'false') {
        $params['searchForArrival'] = 'true';
    }
    // Sorteerparameter (pas aan indien de API een andere parameter verwacht)
    $params['sort'] = ($sortOption === 'snelste') ? 'fastest' : 'cheapest';

    // Roep het /api/v3/trips-endpoint aan
    $responseData = nsApiGetRequest('/reisinformatie-api/api/v3/trips', $params);
}

// Render de template en geef de data door
echo $twig->render('reisadvies.html.twig', [
    'reisInformatie'    => json_decode($responseData ?? '', true),
    'aantalStoringen'   => count($storingen['CALAMITY']),
    'page'              => 'index',
    'formData'          => $formData,
]);
