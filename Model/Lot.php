<?php

declare(strict_types=1);

class Lot
{
    private ?string $id;
    private string  $medicamentId;
    private string  $numeroLot;
    private DateTime $datePeremption;
    private int     $quantiteDisponible;
    private string  $statut;
    private string  $creePar;

    public function __construct(
        ?string  $id,
        string   $medicamentId,
        string   $numeroLot,
        DateTime $datePeremption,
        int      $quantiteDisponible,
        string   $statut,
        string   $creePar
    ) {
        $this->id                 = $id;
        $this->medicamentId       = $medicamentId;
        $this->numeroLot          = $numeroLot;
        $this->datePeremption     = $datePeremption;
        $this->quantiteDisponible = $quantiteDisponible;
        $this->statut             = $statut;
        $this->creePar            = $creePar;
    }

    /* ---- Getters ---- */

    public function getId(): ?string          { return $this->id; }
    public function getMedicamentId(): string { return $this->medicamentId; }
    public function getNumeroLot(): string    { return $this->numeroLot; }
    public function getDatePeremption(): DateTime { return $this->datePeremption; }
    public function getQuantite(): int        { return $this->quantiteDisponible; }
    public function getStatut(): string       { return $this->statut; }
    public function getCreePar(): string      { return $this->creePar; }

    /* ---- Setters ---- */

    public function setId(string $id): void          { $this->id = $id; }
    public function setStatut(string $s): void       { $this->statut = $s; }
    public function setQuantite(int $q): void        { $this->quantiteDisponible = $q; }

    /**
     * Décrémente la quantité disponible (logique FEFO sortie).
     * Passe le lot à PERIME si la quantité tombe à 0.
     */
    public function decremente(int $qte): void
    {
        $this->quantiteDisponible -= $qte;
        if ($this->quantiteDisponible <= 0) {
            $this->quantiteDisponible = 0;
            $this->statut = 'PERIME';
        }
    }
}
