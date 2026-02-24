<?php
// Entities/Status.php
namespace Entities;

class Status {

    private int $id;
    private string $naam;

    public function __construct(int $id, string $naam) {
        $this->id = $id;
        $this->naam = $naam;
    }

    public function getStatusId(): int {
        return $this->id;
    }

    public function getNaam(): string {
        return $this->naam;
    }
}
