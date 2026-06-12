<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../repository/LotRepository.php';
require_once __DIR__ . '/../Model/Lot.php';
require_once __DIR__ . '/../Model/MouvementStock.php';

class StockController
{
    private LotRepository $repo;

    public function __construct()
    {
        $this->repo = new LotRepository();
    }

    /**
     * Réceptionne un nouveau lot et enregistre le mouvement ENTREE.
     */
    public function receptionnerLot(
        string $medicamentId,
        string $numLot,
        string $date,
        int    $qte,
        float  $prix,
        string $userId
    ): array {

        // Validation de la date
        $d = DateTime::createFromFormat('Y-m-d', $date);

        if (!$d || $d <= new DateTime()) {
            return [
                'success' => false,
                'message' => 'La date de péremption doit être dans le futur.',
            ];
        }

        if ($qte <= 0) {
            return [
                'success' => false,
                'message' => 'La quantité doit être supérieure à 0.',
            ];
        }

        // Création et sauvegarde du lot
        $lot = new Lot(
            null,
            $medicamentId,
            $numLot,
            $d,
            $qte,
            'DISPONIBLE',
            $userId
        );

        $lotId = $this->repo->save($lot);

        // Mouvement d'entrée
        $this->repo->saveMouvement(new MouvementStock(
            null,
            $lotId,
            $medicamentId,
            'ENTREE',
            $qte,
            $userId
        ));

        return ['success' => true, 'message' => 'Lot réceptionné avec succès.'];
    }

    /**
     * Sortie FEFO : consomme les lots dans l'ordre croissant de péremption.
     */
    public function sortieFEFO(
        string $medicamentId,
        int    $qteDemandee,
        string $userId,
        string $typeSortie = 'SORTIE_DISPENSATION'
    ): array {

        if ($qteDemandee <= 0) {
            return ['success' => false, 'message' => 'Quantité invalide.'];
        }

        $reste = $qteDemandee;

        foreach ($this->repo->findByMedicamentFEFO($medicamentId) as $lot) {

            if ($reste <= 0) break;

            $qte = min($reste, $lot->getQuantite());

            $lot->decremente($qte);
            $this->repo->update($lot);

            $this->repo->saveMouvement(new MouvementStock(
                null,
                $lot->getId(),
                $medicamentId,
                $typeSortie,
                $qte,
                $userId
            ));

            $reste -= $qte;
        }

        if ($reste > 0) {
            return [
                'success' => false,
                'message' => "Stock insuffisant. Manque : $reste unité(s).",
            ];
        }

        return ['success' => true, 'message' => 'Sortie FEFO enregistrée.'];
    }
}
