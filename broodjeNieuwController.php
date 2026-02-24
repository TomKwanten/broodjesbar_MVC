<?php
// broodjeNieuwController.php

require_once __DIR__ . '/config/init.php';
require_once __DIR__ . '/config/initTwig.php';

use Business\BroodjeService;

$broodjeService = new BroodjeService();

try {
    $isPost = ($_SERVER['REQUEST_METHOD'] === 'POST');

    if (!$isPost) {
        // GET: toon leeg formulier
        print($twig->render("broodjeForm.twig", [
            'formAction' => "broodjeNieuwController.php"
        ]));
        exit;
    }

    // POST: verwerk formulier

    $naam = isset($_POST['naam']) ? trim($_POST['naam']) : '';
    $omschrijving = isset($_POST['omschrijving']) ? trim($_POST['omschrijving']) : '';
    $prijsRaw = isset($_POST['prijs']) ? trim($_POST['prijs']) : '';

    $prijsRawNormalized = str_replace(',', '.', $prijsRaw); 

    $errors = [];
    $fieldErrors = [];

    if ($naam === '') {
        $fieldErrors['naam'] = "Naam is verplicht.";
        $errors[] = "Naam is verplicht.";
    }

    if ($prijsRawNormalized === '' || !is_numeric($prijsRawNormalized)) {
        $fieldErrors['prijs'] = "Prijs moet een geldig getal zijn.";
        $errors[] = "Prijs moet een geldig getal zijn.";
    } else {
        $prijs = (float) $prijsRawNormalized;
        if ($prijs < 0) {
            $fieldErrors['prijs'] = "Prijs mag niet negatief zijn.";
            $errors[] = "Prijs mag niet negatief zijn.";
        }
    }

    if (!empty($errors)) {
        // Render form opnieuw + behoud input
        print($twig->render("broodjeForm.twig", [
            'errors' => $errors,
            'fieldErrors' => $fieldErrors,
            'old' => [
                'naam' => $naam,
                'omschrijving' => $omschrijving,
                'prijs' => $prijsRaw
            ],
            'formAction' => "broodjeNieuwController.php"
        ]));
        exit;
    }

    // Opslaan
    $newId = $broodjeService->createBroodje($naam, (float) $prijsRawNormalized, $omschrijving);

    // Redirect naar overzicht
    header("Location: broodjesController.php");
    exit;

} catch (Exception $e) {
    print($twig->render('fout.twig', [
        'fout' => $e->getMessage()
    ]));
}
