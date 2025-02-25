<?php

/**
 * Voert een GET-aanvraag uit naar de NS API met een gegeven endpoint en queryparameters.
 *
 * @param string $endpoint Het API-endpoint (bijvoorbeeld "/api/v3/trips")
 * @param array  $params   Associatieve array met queryparameters
 * @return mixed           Gedecodeerde JSON-response of een array met een error-key bij een fout
 */
function nsApiGetRequest($endpoint, $params = []) {
    $url = NS_API_BASE_URL . $endpoint;
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Ocp-Apim-Subscription-Key: ' . NS_API_KEY,
        'Accept: application/json'
    ]);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $error = 'cURL error: ' . curl_error($ch);
        curl_close($ch);
        return ['error' => $error];
    }

    curl_close($ch);

    return $response;
}

function getOrFetchCachedNsApiResult($api_route)
{
    $cache_file = __DIR__ . '/../cache/' . md5($api_route) . '.cache';

    // Controleer of het cachebestand bestaat en of het jonger is dan de cachetijd
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < API_CACHE_TIME) {
        $json_data = file_get_contents($cache_file);
    } else {
        $json_data = nsApiGetRequest($api_route);
        file_put_contents($cache_file, $json_data);
    }

    $data = json_decode($json_data, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Fout bij het decoderen van JSON: " . json_last_error_msg() . "\n\nRegel " . __LINE__);
    }

    return $data;
}

function sortDisruptions($disruptions)
{
    $sortedDisruptions = [];
    foreach($disruptions as $disruption) {
        if(! isset($sortedDisruptions[$disruption['type']])) {
            $sortedDisruptions[$disruption['type']] = [];
        }

        $sortedDisruptions[$disruption['type']][] = $disruption;
    }
    return $sortedDisruptions;
}