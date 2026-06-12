<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class RapportController
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function index(string $mois = ''): array
    {
        if ($mois === '') {
            $mois = date('Y-m');
        }

        $debut = $mois . '-01';
        $fin   = date('Y-m-t', strtotime($debut));

        $stmt = $this->pdo->prepare('
            SELECT
                ms.type,
                ms.quantite,
                ms.effectue_le,
                m.nom_commercial,
                l.numero_lot,
                u.nom AS nom_utilisateur
            FROM MOUVEMENT_STOCK ms
            JOIN LOT l         ON l.id = ms.lot_id
            JOIN MEDICAMENT m  ON m.id = ms.medicament_id
            JOIN UTILISATEUR u ON u.id = ms.effectue_par
            WHERE ms.effectue_le BETWEEN ? AND ?
            ORDER BY ms.effectue_le DESC
        ');
        $stmt->execute([$debut . ' 00:00:00', $fin . ' 23:59:59']);

        return [
            'mouvements' => $stmt->fetchAll(),
            'mois'       => $mois,
        ];
    }
}
