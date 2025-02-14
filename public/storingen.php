<?php
require_once __DIR__ . '/../private/bootstrap.php';


// Variabele waarin de API-response komt te staan
$responseData = getOrFetchCachedNsApiResult('/disruptions/v3?isActive=true');


// Render de template en geef de data door
echo $twig->render('storingen.html.twig', [
    'disruptions'   => getOrFetchCachedNsApiResult('/disruptions/v3?isActive=true'),
    'page'          => 'storingen'
]);
