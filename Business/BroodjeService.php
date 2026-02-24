<?php
// Business/BroodjeService.php
namespace Business;

use Data\BroodjeDAO;

class BroodjeService {

    private BroodjeDAO $broodjeDAO;

    public function __construct() {
        $this->broodjeDAO = new BroodjeDAO();
    }

    public function haalAlleBroodjesOp(): ?array {
        return $this->broodjeDAO->getAlleBroodjes();
    }

    public function haalBroodjeById(int $id) {
        return $this->broodjeDAO->getBroodjeById($id);
    }

    public function updateBroodje(int $id, string $naam, float $prijs, string $omschrijving) {
        $this->broodjeDAO->updateBroodje($id, $naam, $prijs, $omschrijving);
    }

    public function createBroodje(string $naam, float $prijs, string $omschrijving): int {
        return $this->broodjeDAO->createBroodje($naam, $prijs, $omschrijving);
    }

    public function deleteBroodje(int $id): bool {
        return $this->broodjeDAO->deleteBroodje($id);
    }
}