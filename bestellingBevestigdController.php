<?php
// bestellingBevestigdController.php

require_once __DIR__ . '/config/init.php';
require_once __DIR__ . '/config/initTwig.php';

use Business\BestellingService;

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    echo $twig->render('fout.twig', [
        'fout' => 'Bestelling ID is ongeldig'
    ]);
    exit;
}

$bestellingService = new BestellingService();

try {
    $bestelling = $bestellingService->haalBestellingOpById($id);
} catch (\Exception $e) {
    echo $twig->render('fout.twig', [
        'fout' => 'Bestelling niet gevonden'
    ]);
    exit;
}

echo $twig->render('bestellingBevestigd.twig', [
    'bestelling' => $bestelling
]);
