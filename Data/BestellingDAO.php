<?php 
// Data/BestellingDAO.php
namespace Data;

use Exception;
use \PDO;
use Entities\Bestelling;
use Entities\Status;

class BestellingDAO {

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

    public function createBestelling(
        int $broodjeId, 
        int $gebruikerId, 
        Status $status, 
        string $afhaalmoment
    ): Bestelling {

        $this->connect();

        try {
            $this->dbh->beginTransaction();

            $stmt = $this->dbh->prepare("
                INSERT INTO bestellingen 
                (broodjeId, gebruikerId, datum, statusID, afhalingsmoment)
                VALUES 
                (:broodjeId, :gebruikerId, NOW(), :statusId, :afhaalmoment)
            ");

            $stmt->execute([
                'broodjeId' => $broodjeId,
                'gebruikerId' => $gebruikerId,
                'statusId' => $status->getStatusId(),
                'afhaalmoment' => $afhaalmoment
            ]);

            $id = (int)$this->dbh->lastInsertId();

            // Zelfde DB-verbinding gebruiken binnen de transactie
            $bestelling = $this->getByIdWithDbh($id);

            $this->dbh->commit();

            return $bestelling;

        } catch (\Exception $e) {
            if ($this->dbh !== null && $this->dbh->inTransaction()) {
                $this->dbh->rollBack();
            }
            throw $e;
        } finally {
            $this->disconnect();
        }
    }

    public function getById(int $id): Bestelling {
        $this->connect();

        try {
            return $this->getByIdWithDbh($id);
        } finally {
            $this->disconnect();
        }
    }

    private function getByIdWithDbh(int $id): Bestelling {
        // bestelling id ophalen
        $stmt = $this->dbh->prepare(" 
            SELECT 
                b.id,
                b.broodjeId,
                b.gebruikerId,
                b.datum,
                b.afhalingsmoment,
                s.statusID AS statusId,
                s.status AS statusNaam
            FROM bestellingen b
            JOIN statussen s ON b.statusID = s.statusID
            WHERE b.id = :id
        ");

        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new \Exception("Bestelling niet gevonden");
        }

        $status = new Status(
            (int)$row['statusId'], 
            $row['statusNaam']
        );

        return new Bestelling(
            $row['id'], 
            $row['broodjeId'],
            $row['gebruikerId'], 
            $row['datum'], 
            $status, 
            $row['afhalingsmoment']
        );
    }

    public function toonAlleBestellingen(): array {
        $this->connect();

        try {
            $stmt = $this->dbh->prepare(" 
                SELECT
                    bestellingen.id AS bestellingId,
                    bestellingen.afhalingsmoment,
                    statussen.status,
                    broodjes.Naam AS broodjeNaam,
                    broodjes.Omschrijving AS broodjeOmschrijving,
                    broodjes.Prijs AS broodjePrijs,
                    gebruikers.naam AS gebruikerNaam
                FROM bestellingen
                INNER JOIN broodjes
                    ON bestellingen.broodjeId = broodjes.id
                INNER JOIN gebruikers
                    ON bestellingen.gebruikerId = gebruikers.id
                INNER JOIN statussen
                    ON bestellingen.statusID = statussen.statusID
                WHERE
                    bestellingen.afhalingsmoment IS NOT NULL
                    AND bestellingen.afhalingsmoment <> '0000-00-00 00:00:00'
                    AND bestellingen.statusID IS NOT NULL
                ORDER BY
                    bestellingen.afhalingsmoment ASC
            ");   
            
            $stmt->execute();
            $array = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$array) {
                throw new Exception("Er zijn nog geen bestellingen");
            }

            return $array;
        } finally {
            $this->disconnect();
        }
    }

    public function updateStatus(Bestelling $bestelling): void {
        $this->connect();

        try {
            $this->dbh->beginTransaction();

            $stmt = $this->dbh->prepare("
                UPDATE bestellingen 
                SET statusID = :statusId 
                WHERE id = :id
            ");

            $stmt->execute([
                'id' => $bestelling->getIdBestelling(),
                'statusId' => $bestelling->getStatus()->getStatusId(),
            ]);

            $this->dbh->commit();

        } catch (\Exception $e) {
            if ($this->dbh !== null && $this->dbh->inTransaction()) {
                $this->dbh->rollBack();
            }
            throw $e;
        } finally {
            $this->disconnect();
        }
    }

    public function getStatusByBestellingId(int $bestellingId): int {
        $this->connect();

        try {
            $stmt = $this->dbh->prepare("
                SELECT statusID
                FROM bestellingen
                WHERE id = :id
            ");
            $stmt->execute(['id' => $bestellingId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$row || $row['statusID'] === null) {
                throw new Exception('Status niet gevonden voor bestelling ' . $bestellingId);
            }

            return $row['statusID'];
        } finally {
            $this->disconnect();
        }
    }

    public function updateStatusByID(int $bestellingId, int $nieuweStatusId): void {
        $this->connect();

        try {
            $stmt = $this->dbh->prepare("
                UPDATE bestellingen 
                SET statusID = :statusId 
                WHERE id = :id
            ");

            $stmt->execute([
                'id' => $bestellingId,
                'statusId' => $nieuweStatusId,
            ]);
        } finally {
            $this->disconnect();
        }
    }

    public function deleteBestelling(int $bestellingId): bool {
        $this->connect();

        try {
            $stmt = $this->dbh->prepare("
                DELETE FROM bestellingen 
                WHERE id = :id");
            $stmt->execute(['id' => $bestellingId]);
            return $stmt->rowCount() > 0;
        } finally {
            $this->disconnect();
        }
    }
}
