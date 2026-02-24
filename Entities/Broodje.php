<?php
// Entities/Broodje.php
namespace Entities;

class Broodje {
    private ?int $id;
    private ?string $naam;
    private ?string $omschrijving;
    private ?float $prijs;

    public function __construct(?int $id, ?string $naam, ?string $omschrijving, ?float $prijs) {
        $this->id = $id;
        $this->naam = $naam;
        $this->omschrijving = $omschrijving;
        $this->prijs = $prijs;
    }

    public function getIdBroodje(): ?int {
        return $this->id;
    }

    public function getNaamBroodje(): ?string {
        return $this->naam;
    }

    public function getOmschrijving(): ?string {
        return $this->omschrijving;
    }

    public function getPrijs(): ?float {
        return $this->prijs;
    }
}
