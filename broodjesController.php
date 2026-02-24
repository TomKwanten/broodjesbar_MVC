<?php
// broodjesController.php

require_once __DIR__ . '/config/init.php';
require_once __DIR__ . '/config/initTwig.php';

use Business\BroodjeService;

$broodjeService = new BroodjeService();

try {
    $broodjesOverzicht = $broodjeService->haalAlleBroodjesOp();

    echo $twig->render("broodjesOverzicht.twig", [
        'broodjes' => $broodjesOverzicht,
    ]);

} catch (\Exception $e) {
    echo $twig->render('fout.twig', [
        'fout' => $e->getMessage()
    ]);
}