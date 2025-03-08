<?php
require_once __DIR__ . '/../private/bootstrap.php';

// Render de template en geef de data door
echo $twig->render('over.html.twig', [
    'aantalStoringen'   => $aantalStoringen,
    'page'              => 'over',
]);
