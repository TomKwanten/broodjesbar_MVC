<?php
// broodjeBewerkenController.php

require_once __DIR__ . '/config/init.php';
require_once __DIR__ . '/config/initTwig.php';

use Business\BroodjeService;

$broodjeService = new BroodjeService();

try {
    // 1. Bepaal request type
    $isPost = ($_SERVER['REQUEST_METHOD'] === 'POST');

    if (!$isPost) { // als het geen POST is => dus GET => toon formulier

        // 2. Lees id uit de URL
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id <= 0) {
            throw new Exception("Ongeldig broodje id.");
        }

        // 3. Haal broodje op uit DB
        $broodje = $broodjeService->haalBroodjeById($id);
        if (!$broodje) {
            throw new Exception("Broodje niet gevonden.");
        }

        // 4. Render form met broodje-data
        print($twig->render("broodjeForm.twig", [
            'broodje' => $broodje,
            'formAction' => "broodjeBewerkenController.php?id=" . $id
        ]));
        exit;
    }

    // POST-flow

    // 5. Lees id en velden uit POST
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $naam = isset($_POST['naam']) ? trim($_POST['naam']) : '';
    $omschrijving = isset($_POST['omschrijving']) ? trim($_POST['omschrijving']) : '';
    $prijsRaw = isset($_POST['prijs']) ? trim($_POST['prijs']) : '';

    // 6. Validatie
    $errors = [];
    $fieldErrors = [];

    if ($id <= 0) {
        $errors[] = "Ongeldig broodje id.";
    }

    if ($naam === '') {
        $fieldErrors['naam'] = "Naam is verplicht.";
        $errors[] = "Naam is verplicht.";
    }

    if ($prijsRaw === '' || !is_numeric($prijsRaw)) {
        $fieldErrors['prijs'] = "Prijs moet een geldig getal zijn.";
        $errors[] = "Prijs moet een geldig getal zijn.";
    } else {
        $prijs = (float) $prijsRaw;
        if ($prijs < 0) {
            $fieldErrors['prijs'] = "Prijs mag niet negatief zijn.";
            $errors[] = "Prijs mag niet negatief zijn.";
        }
    }

    // 7. Als errors: toon form opnieuw + behoud input
    if (!empty($errors)) {
        $broodje = $id > 0 ? $broodjeService->haalBroodjeById($id) : null;

        print($twig->render("broodjeForm.twig", [
            'broodje' => $broodje,
            'errors' => $errors,
            'fieldErrors' => $fieldErrors,
            'old' => [
                'naam' => $naam,
                'omschrijving' => $omschrijving,
                'prijs' => $prijsRaw
            ],
            'formAction' => "broodjeBewerkenController.php?id=" . $id
        ]));
        exit;
    }

    // 8. Opslaan via service 
    $broodjeService->updateBroodje($id, $naam, (float)$prijsRaw, $omschrijving);

    // 9. Redirect naar overzicht (POST/Redirect/GET)
    header("Location: broodjesController.php");
    exit;

} catch (Exception $e) {
    print($twig->render('fout.twig', [
        'fout' => $e->getMessage()
    ]));
}
