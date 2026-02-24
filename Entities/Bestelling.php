<?php
// Entities/Bestelling.php
namespace Entities;

class Bestelling {
    private ?int $id;
    private ?int $broodjeId;
    private ?int $gebruikerId;
    private ?string $datum;
    private Status $status;
    private ?string $afhaalmoment;

    public function __construct(
        ?int $id, 
        ?int $broodjeId, 
        ?int $gebruikerId, 
        ?string $datum, 
        Status $status,
        ?string $afhaalmoment = null
    ) {
        $this->id = $id;
        $this->broodjeId = $broodjeId;
        $this->gebruikerId = $gebruikerId;
        $this->datum = $datum;
        $this->status = $status;
        $this->afhaalmoment = $afhaalmoment;
    }

    public function getIdBestelling(): ?int {
        return $this->id;
    }

    public function getBroodjeId(): ?int {
        return $this->broodjeId;
    }

    public function getGebruikerId(): ?int {
        return $this->gebruikerId;
    }

    public function getDatum(): ?string {
        return $this->datum;
    }

    public function getStatus(): Status {
        return $this->status;
    }

    public function setStatus(Status $status): void {
        $this->status = $status;
    }

    public function getAfhaalmoment(): ?string { 
        return $this->afhaalmoment; 
    }
}
