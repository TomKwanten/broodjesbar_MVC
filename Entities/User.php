<?php
// Entities/User.php
namespace Entities;

class User {
    private ?int $id;
    private ?string $naam;

    public function __construct(?int $id, ?string $naam) {
        $this->id = $id;
        $this->naam = $naam;
    }

    public function getIdGebruiker(): ?int {
        return $this->id;
    }

    public function getNaamGebruiker(): ?string {
        return $this->naam;
    }
}
?>
