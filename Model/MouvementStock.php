<?php

declare(strict_types=1);

class MouvementStock
{
    private ?string $id;
    private string  $lotId;
    private string  $medicamentId;
    private string  $type;   // ENTREE | SORTIE_VENTE | SORTIE_DISPENSATION | PERTE_PEREMPTION
    private int     $quantite;
    private string  $effectuePar;
    private ?string $effectueLe;

    public function __construct(
        ?string $id,
        string  $lotId,
        string  $medicamentId,
        string  $type,
        int     $quantite,
        string  $effectuePar,
        ?string $effectueLe = null
    ) {
        $this->id           = $id;
        $this->lotId        = $lotId;
        $this->medicamentId = $medicamentId;
        $this->type         = $type;
        $this->quantite     = $quantite;
        $this->effectuePar  = $effectuePar;
        $this->effectueLe   = $effectueLe;
    }

    public function getId(): ?string         { return $this->id; }
    public function getLotId(): string       { return $this->lotId; }
    public function getMedicamentId(): string{ return $this->medicamentId; }
    public function getType(): string        { return $this->type; }
    public function getQuantite(): int       { return $this->quantite; }
    public function getEffectuePar(): string { return $this->effectuePar; }
    public function getEffectueLe(): ?string { return $this->effectueLe; }
}
