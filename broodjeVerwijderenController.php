<?php
// broodjeVerwijderenController.php

require_once __DIR__ . '/config/init.php';
require_once __DIR__ . '/config/initTwig.php';

use Business\BroodjeService;

$broodjeService = new BroodjeService();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Ongeldige request.");
    }

    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    if ($id <= 0) {
        throw new Exception("Ongeldig broodje id.");
    }

    $deleted = $broodjeService->deleteBroodje($id);

    if (!$deleted) {
        throw new Exception("Broodje niet gevonden of al verwijderd.");
    }

    header("Location: broodjesController.php");
    exit;

} catch (Exception $e) {
    print($twig->render('fout.twig', [
        'fout' => $e->getMessage()
    ]));
}
