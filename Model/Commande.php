<?php

declare(strict_types=1);

class Commande
{
    private ?string $id;
    private string  $reference;
    private string  $fournisseur;
    private string  $statut;   // EN_ATTENTE | RECEPTIONNEE
    private ?string $dateReception;
    private ?string $receptionneePar;

    public function __construct(
        ?string $id,
        string  $reference,
        string  $fournisseur,
        string  $statut,
        ?string $dateReception   = null,
        ?string $receptionneePar = null
    ) {
        $this->id              = $id;
        $this->reference       = $reference;
        $this->fournisseur     = $fournisseur;
        $this->statut          = $statut;
        $this->dateReception   = $dateReception;
        $this->receptionneePar = $receptionneePar;
    }

    public function getId(): ?string             { return $this->id; }
    public function getReference(): string       { return $this->reference; }
    public function getFournisseur(): string     { return $this->fournisseur; }
    public function getStatut(): string          { return $this->statut; }
    public function getDateReception(): ?string  { return $this->dateReception; }
    public function getReceptionneePar(): ?string{ return $this->receptionneePar; }

    public function setId(string $v): void              { $this->id = $v; }
    public function setStatut(string $v): void          { $this->statut = $v; }
    public function setDateReception(?string $v): void  { $this->dateReception = $v; }
    public function setReceptionneePar(?string $v): void{ $this->receptionneePar = $v; }
}
