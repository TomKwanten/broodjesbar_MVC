<?php
// Data/BroodjeDAO.php
namespace Data;

use \PDO;

class BroodjeDAO
{
    private ?PDO $dbh = null;

    private function connect(): void {
        if ($this->dbh === null) {
            $this->dbh = new PDO(
                DBConfig::$DB_CONNSTRING,
                DBConfig::$DB_USERNAME,
                DBConfig::$DB_PASSWORD
            );
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    private function disconnect(): void {
        $this->dbh = null;
    }

    public function getAlleBroodjes(): array {
        $this->connect();

        try {
            $stmt = $this->dbh->prepare("
                SELECT 
                    ID AS id,
                    Naam AS naam, 
                    Prijs AS prijs,
                    Omschrijving AS omschrijving
                FROM broodjes 
                ORDER BY ID
            ");

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } finally {
            $this->disconnect();
        }
    }

    public function getBroodjeById(int $id): ?array {
        $this->connect();

        try {
            $stmt = $this->dbh->prepare("
                SELECT 
                    ID AS id,
                    Naam AS naam, 
                    Prijs AS prijs,
                    Omschrijving AS omschrijving
                FROM broodjes 
                WHERE ID = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } finally {
            $this->disconnect();
        }
    }

    public function updateBroodje(int $id, string $naam, float $prijs, string $omschrijving) {
        $this->connect();

        try {
            $stmt = $this->dbh->prepare("
                UPDATE broodjes 
                SET 
                    Naam = :naam,
                    Prijs = :prijs,
                    Omschrijving = :omschrijving
                WHERE ID = :id
            ");
            $stmt->execute([
                'id' => $id,
                'naam' => $naam,
                'prijs' => $prijs,
                'omschrijving' => $omschrijving
            ]);
        } finally {
            $this->disconnect();
        }
    }

    public function createBroodje(string $naam, float $prijs, string $omschrijving) {
        $this->connect();

        try {
            $stmt = $this->dbh->prepare("
                INSERT INTO broodjes (Naam, Prijs, Omschrijving)
                VALUES (:naam, :prijs, :omschrijving)
            ");
            $stmt->execute([
                'naam' => $naam,
                'prijs' => $prijs,
                'omschrijving' => $omschrijving
            ]);

            return (int) $this->dbh->lastInsertId();
        } finally {
            $this->disconnect();
        }
    }

    public function deleteBroodje(int $id): bool {
        $this->connect();

        try {
            $stmt = $this->dbh->prepare("DELETE FROM broodjes WHERE ID = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->rowCount() > 0;
        } finally {
            $this->disconnect();
        }
    }
}