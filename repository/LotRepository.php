<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../Model/Lot.php';
require_once __DIR__ . '/../Model/MouvementStock.php';

class LotRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /* ---- Helpers ---- */

    private function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function uuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    private function rowToLot(array $r): Lot
    {
        return new Lot(
            $r['id'],
            $r['medicament_id'],
            $r['numero_lot'],
            new DateTime($r['date_peremption']),
            (int) $r['quantite_disponible'],
            $r['statut'],
            $r['cree_par']
        );
    }

    /* ---- CRUD ---- */

    public function save(Lot $lot): string
    {
        $id = $this->uuid();

        $this->pdo->prepare('
            INSERT INTO LOT
                (id, medicament_id, numero_lot, date_peremption,
                 quantite_disponible, statut, cree_par)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ')->execute([
            $id,
            $lot->getMedicamentId(),
            $lot->getNumeroLot(),
            $lot->getDatePeremption()->format('Y-m-d'),
            $lot->getQuantite(),
            $lot->getStatut(),
            $lot->getCreePar(),
        ]);

        return $id;
    }

    public function update(Lot $lot): void
    {
        $this->pdo->prepare('
            UPDATE LOT
            SET quantite_disponible = ?, statut = ?
            WHERE id = ?
        ')->execute([
            $lot->getQuantite(),
            $lot->getStatut(),
            $lot->getId(),
        ]);
    }

    /**
     * Retourne tous les médicaments (pour les listes déroulantes).
     */
    public function findAllMedicaments(): array
    {
        return $this->fetchAll('SELECT id, nom_commercial FROM MEDICAMENT ORDER BY nom_commercial ASC');
    }

    /* ---- Requêtes métier ---- */

    /**
     * Retourne les lots disponibles d'un médicament triés par date de péremption
     * croissante (logique FEFO).
     */
    public function findByMedicamentFEFO(string $medicamentId): array
    {
        $rows = $this->fetchAll('
            SELECT * FROM LOT
            WHERE medicament_id = ?
              AND statut IN ("DISPONIBLE", "ALERTE_ORANGE", "ALERTE_ROUGE")
              AND quantite_disponible > 0
            ORDER BY date_peremption ASC
        ', [$medicamentId]);

        return array_map(fn($r) => $this->rowToLot($r), $rows);
    }

    /**
     * Retourne les lots proches de la péremption avec leur niveau de criticité.
     */
    public function findAlertes(int $joursOrange = 90, int $joursRouge = 30): array
    {
        $rows = $this->fetchAll('
            SELECT l.*, m.nom_commercial
            FROM LOT l
            JOIN MEDICAMENT m ON m.id = l.medicament_id
            WHERE l.statut IN ("DISPONIBLE", "ALERTE_ORANGE", "ALERTE_ROUGE")
              AND l.quantite_disponible > 0
              AND l.date_peremption <= DATE_ADD(NOW(), INTERVAL ? DAY)
            ORDER BY l.date_peremption ASC
        ', [$joursOrange]);

        foreach ($rows as &$row) {
            $jours = (int)(new DateTime())->diff(new DateTime($row['date_peremption']))->days;
            $row['criticite'] = $jours <= $joursRouge ? 'rouge' : 'orange';
            $row['jours_restants'] = $jours;
        }

        return $rows;
    }

    /* ---- Mouvements ---- */

    public function saveMouvement(MouvementStock $m): void
    {
        $this->pdo->prepare('
            INSERT INTO MOUVEMENT_STOCK
                (id, lot_id, medicament_id, type, quantite, effectue_par)
            VALUES (?, ?, ?, ?, ?, ?)
        ')->execute([
            $this->uuid(),
            $m->getLotId(),
            $m->getMedicamentId(),
            $m->getType(),
            $m->getQuantite(),
            $m->getEffectuePar(),
        ]);
    }
}
