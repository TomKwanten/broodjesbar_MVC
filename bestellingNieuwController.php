<?php
// bestellingNieuwController.php

require_once __DIR__ . '/config/init.php';
require_once __DIR__ . '/config/initTwig.php';

use Business\BroodjeService;
use Business\BestellingService;

// defaults
$broodjeId = (int)($_GET['broodjeId'] ?? 0);
$naam = '';
$afhaalmoment = '';
$errors = [];

// data dat nodig is
$broodjeService = new BroodjeService();
$broodjes = $broodjeService->haalAlleBroodjesOp();

// POST-verwerking
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';

if ($isPost) {

    $broodjeId = (int)($_POST['broodjeId'] ?? 0);
    $naam = $_POST['naam'] ?? '';
    $afhaalmoment = $_POST['afhaalmoment'] ?? '';
    
    if ($broodjeId <= 0) {
        $errors[] = 'Ongeldig broodje ID';
    }
    if ($naam === '') {
        $errors[] = 'Naam is verplicht';
    }
    if ($afhaalmoment === '') {
        $errors[] = 'Afhaalmoment is verplicht';
    } else {
        $afhaalTs = strtotime($afhaalmoment);
        
        if ($afhaalTs === false) {
            $errors[] = 'Ongeldig afhaalmoment';
        } elseif ($afhaalTs < time() + 30 * 60) {
            $errors[] = 'Afhaalmoment moet minimaal 30 minuten in de toekomst zijn';
        }
    }

    if ($isPost && empty($errors)) {
        $bestellingService = new BestellingService();
        $afhaalmomentDb = str_replace('T', ' ', $afhaalmoment) . ':00';
        $bestelling = $bestellingService->plaatsBestelling(
            $broodjeId,
            1,
            $afhaalmomentDb
        ); 

        header("Location: bestellingBevestigdController.php?id=" . $bestelling->getIdBestelling());
        exit;

    }
}

// GET-verwerking
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $broodjeId <= 0) {
    echo $twig->render('fout.twig', [
        'fout' => 'Broodje ID is ongeldig'
    ]);
    exit;
}

// naam broodje zoeken
$broodjeNaam = '';
foreach ($broodjes as $broodje) {
    if ((int)$broodje['id'] === $broodjeId) {
        $broodjeNaam = $broodje['naam'];
        break;
    }
}

// formulier renderen
echo $twig->render('bestellen.twig', [
    'broodjeId' => $broodjeId,
    'broodjes' => $broodjes,
    'broodjeNaam' => $broodjeNaam,
    'naam' => $naam,
    'afhaalmoment' => $afhaalmoment,
    'errors' => $errors
]);



