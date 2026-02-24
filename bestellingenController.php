<?php
// bestellingenController.php

require_once __DIR__ . '/config/init.php';
require_once __DIR__ . '/config/initTwig.php';

use Business\BestellingService;

$bestellingService = new BestellingService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bestellingId = isset($_POST['bestellingId']) ? (int)$_POST['bestellingId'] : 0;

    if ($bestellingId > 0) {
        $bestellingService->gaNaarVolgendeStatus($bestellingId);
    }

    header('Location: bestellingenController.php');
    exit;
}

try {
    $bestellingOverzicht = $bestellingService->toonOverzichtBestellingen();

    // 1) lees filter uit URL
    $activeStatus = $_GET['status'] ?? 'all';

    // 2) filter de array in PHP (zodat Twig alleen nog moet tonen)
    if ($activeStatus !== 'all') {
        $bestellingOverzicht = array_values(array_filter(
            $bestellingOverzicht,
            fn($b) => ($b['status'] ?? '') === $activeStatus
        ));
    }

    echo $twig->render('bestellingOverzicht.twig', [
        'bestellingOverzicht' => $bestellingOverzicht,
        'activeStatus' => $activeStatus
    ]);

} catch (\Exception $e) {
    echo $twig->render('fout.twig', [
        'fout' => $e->getMessage()
    ]);
}
