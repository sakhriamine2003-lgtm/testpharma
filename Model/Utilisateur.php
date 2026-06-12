<?php

declare(strict_types=1);

class Utilisateur
{
    private ?string $id;
    private string  $nom;
    private string  $email;
    private string  $motDePasse;
    private string  $role;   // PREPARATEUR | PHARMACIEN | ADMIN
    private bool    $actif;

    public function __construct(
        ?string $id,
        string  $nom,
        string  $email,
        string  $motDePasse,
        string  $role,
        bool    $actif = true
    ) {
        $this->id         = $id;
        $this->nom        = $nom;
        $this->email      = $email;
        $this->motDePasse = $motDePasse;
        $this->role       = $role;
        $this->actif      = $actif;
    }

    public function getId(): ?string       { return $this->id; }
    public function getNom(): string       { return $this->nom; }
    public function getEmail(): string     { return $this->email; }
    public function getMotDePasse(): string{ return $this->motDePasse; }
    public function getRole(): string      { return $this->role; }
    public function isActif(): bool        { return $this->actif; }

    public function setId(string $id): void         { $this->id = $id; }
    public function setNom(string $v): void         { $this->nom = $v; }
    public function setEmail(string $v): void       { $this->email = $v; }
    public function setMotDePasse(string $v): void  { $this->motDePasse = $v; }
    public function setRole(string $v): void        { $this->role = $v; }
    public function setActif(bool $v): void         { $this->actif = $v; }
}
