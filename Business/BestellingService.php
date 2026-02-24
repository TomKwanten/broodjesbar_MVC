<?php
// Business/BestellingService.php
namespace Business;

use Data\BestellingDAO;
use Entities\Bestelling;
use Entities\Status;

class BestellingService {

    private BestellingDAO $bestellingDAO;

    public function __construct() {
        // DAO inintialiseren zodat service ermee kan werken
        $this->bestellingDAO = new BestellingDAO();
    }

    // nieuwe bestelling plaatsen
    public function plaatsBestelling(
        int $broodjeId,     
        int $gebruikerId,
        string $afhaalmoment
    ): Bestelling {

        // controle broodjeId
        if ($broodjeId <= 0){
            throw new \Exception("Broodje ongeldig");                       
        }

        // controle afhaalmoment
        $afhaal = new \DateTime($afhaalmoment);
        $nu = new \DateTime();
        if ($afhaal < $nu) {
            throw new \Exception("Afhaalmoment moet in de toekomst liggen");
        }

        // status en datum automatisch invullen
        $status = new Status(1, 'Besteld');

        // DAO-aanroep en return
        return $this->bestellingDAO->createBestelling(
            $broodjeId,
            $gebruikerId,
            $status,
            $afhaalmoment
        );
    }

    // wijzig de status van een bestelling (controller mag geen status meegeven)
    public function wijzigStatus(
        int $bestellingId
    ): Bestelling {
        // bestelling ophalen via DAO
        $bestelling = $this->bestellingDAO->getById($bestellingId);

        // controleer businessregels
        // alleen toegestaan om status te verhogen
        // huidige status ophalen
        $huidigeStatus = $bestelling->getStatus()->getStatusId();

        // bepaal nieuwe status (vb: Nieuw -> Gemaakt -> Afgehaald)
        if ($huidigeStatus === 1) {
            $bestelling->setStatus(new Status(2, "Gemaakt"));
        } elseif ($huidigeStatus === 2) {
            $bestelling->setStatus(new Status(3, "Afgehaald"));
        } else {
            throw new \Exception("Status kan niet verder gewijzigd worden");
        }   

        // DAO aanroepen om database bij te werken
        $this->bestellingDAO->updateStatus($bestelling);

        // return geüpdate object
        return $bestelling;
    
    }

    public function haalBestellingOpById(int $id): Bestelling
    {
        return $this->bestellingDAO->getById($id);
    }

    public function toonOverzichtBestellingen(): array
    {
        return $this->bestellingDAO->toonAlleBestellingen();
    }

    public function gaNaarVolgendeStatus(int $bestellingId): void
    {
        $huidig = $this->bestellingDAO->getStatusByBestellingId($bestellingId);

        if ($huidig === 1) {
            $volgende = 2;
        } elseif ($huidig === 2) {
            $volgende = 3;
        } else {
            $volgende = 3; //afgehaald blijft afgehaald
        }

        $this->bestellingDAO->updateStatusByID($bestellingId, $volgende);
    }

    public function deleteBestelling(int $bestellingId): bool
    {
        return $this->bestellingDAO->deleteBestelling($bestellingId);
    }
}
