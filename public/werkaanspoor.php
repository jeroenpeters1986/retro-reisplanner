<?php
require_once __DIR__ . '/../private/bootstrap.php';


// Storingen (en daarmee ook werkzaamheden) worden al opgehaald in bootstrap.php

// Render de template en geef de data door
echo $twig->render('werkaanspoor.html.twig', [
    'disruptions'       => $storingen,
    'aantalStoringen'   => count($storingen['CALAMITY']),
    'page'              => 'werkaanspoor',
]);
