<?php

declare(strict_types=1);

class Medicament
{
    private ?string $id;
    private string  $nomCommercial;
    private string  $codeCip13;
    private float   $prixAchatUnitaire;
    private int     $seuilAlerteStock;

    public function __construct(
        ?string $id,
        string  $nomCommercial,
        string  $codeCip13,
        float   $prixAchatUnitaire,
        int     $seuilAlerteStock
    ) {
        $this->id                = $id;
        $this->nomCommercial     = $nomCommercial;
        $this->codeCip13         = $codeCip13;
        $this->prixAchatUnitaire = $prixAchatUnitaire;
        $this->seuilAlerteStock  = $seuilAlerteStock;
    }

    public function getId(): ?string            { return $this->id; }
    public function getNomCommercial(): string  { return $this->nomCommercial; }
    public function getCodeCip13(): string      { return $this->codeCip13; }
    public function getPrixAchatUnitaire(): float { return $this->prixAchatUnitaire; }
    public function getSeuilAlerteStock(): int  { return $this->seuilAlerteStock; }

    public function setId(string $id): void                   { $this->id = $id; }
    public function setNomCommercial(string $v): void         { $this->nomCommercial = $v; }
    public function setCodeCip13(string $v): void             { $this->codeCip13 = $v; }
    public function setPrixAchatUnitaire(float $v): void      { $this->prixAchatUnitaire = $v; }
    public function setSeuilAlerteStock(int $v): void         { $this->seuilAlerteStock = $v; }
}
