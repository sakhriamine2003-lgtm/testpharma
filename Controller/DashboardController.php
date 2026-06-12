<?php

declare(strict_types=1);

require_once __DIR__ . '/../repository/LotRepository.php';

class DashboardController
{
    private LotRepository $lotRepo;

    public function __construct()
    {
        $this->lotRepo = new LotRepository();
    }

    /**
     * Prépare les données pour la vue dashboard.
     * Retourne un tableau avec les alertes rouge et orange.
     */
    public function index(): array
    {
        $alertes = $this->lotRepo->findAlertes(90, 30);

        $rouge  = array_filter($alertes, fn($a) => $a['criticite'] === 'rouge');
        $orange = array_filter($alertes, fn($a) => $a['criticite'] === 'orange');

        return [
            'rouge'  => array_values($rouge),
            'orange' => array_values($orange),
        ];
    }
}
