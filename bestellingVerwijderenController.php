<?php
// bestellingVerwijderenController.php

require_once __DIR__ . '/config/init.php';
require_once __DIR__ . '/config/initTwig.php';

use Business\BestellingService;

$bestellingService = new BestellingService();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Ongeldige request.");
    }

    $id = isset($_POST['bestellingId']) ? (int) $_POST['bestellingId'] : 0;
    if ($id <= 0) {
        throw new Exception("Ongeldige bestelling id.");
    }

    $deleted = $bestellingService->deleteBestelling($id);

    if (!$deleted) {
        throw new Exception("Bestelling niet gevonden of kon niet verwijderd worden.");
    }

    header("Location: bestellingenController.php");
    exit;

} catch (Exception $e) {
    print($twig->render('fout.twig', [
        'fout' => $e->getMessage()
    ]));
}
