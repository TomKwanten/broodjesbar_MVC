<?php
// index.php
require_once __DIR__ . '/config/initTwig.php';
require_once __DIR__ . '/Business/BroodjeService.php';
require_once __DIR__ . '/Data/BroodjeDAO.php';
require_once __DIR__ . '/Data/DBConfig.php';

use Business\BroodjeService;

$service = new BroodjeService();
$broodjes = $service->haalAlleBroodjesOp();

echo $twig->render('broodjes.twig', [
    'broodjes' => $broodjes
]);
