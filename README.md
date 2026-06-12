# PharmaFEFO — Version corrigée

## Identifiants de test
| Email | Mot de passe | Rôle |
|---|---|---|
| jean.dupont@pharmacie.com | password | PHARMACIEN |
| alice.martin@pharmacie.com | password | PREPARATEUR |
| admin@pharmacie.com | password | ADMIN |

## Installation
1. Importer `script/database.sql` dans MySQL
2. Vérifier les paramètres dans `config/database.php` (host, user, pass)
3. Pointer le Document Root Apache/Nginx vers `public/`
4. Accéder à `http://localhost/login`

## Corrections apportées

### 🔴 Bugs critiques (projet ne fonctionnait pas du tout)

| Fichier | Problème | Correction |
|---|---|---|
| `public/index.php` | Appel `Database::getInstance()` inexistant | Remplacé par `Database::getConnection()` |
| `Controller/DashboardController.php` | Classe nommée `AuthController` au lieu de `DashboardController` | Renommée + logique dashboard ajoutée |
| `Controller/StockController.php` | Utilisation de `TypeMouvement` (enum PHP 8.1 non déclaré) | Remplacé par des constantes string `'ENTREE'`, `'SORTIE_DISPENSATION'` |
| `Controller/StockController.php` | `new Lot(null, $medicamentId, $numLot, $d, $qte, $prix)` → mauvais nombre de params | Corrigé : `new Lot(null, $medicamentId, $numLot, $d, $qte, 'DISPONIBLE', $userId)` |
| `Model/Lot.php` | Méthodes `getQuantite()`, `getMedicamentId()`, `decremente()` manquantes (appelées par repo et controller) | Ajoutées |
| `Model/MouvementStock.php` | Méthodes `getLotId()`, `getMedicamentId()` manquantes (appelées par `LotRepository::saveMouvement()`) | Ajoutées |
| `Model/Utilisateur.php` | Champ `mot_de_passe` absent du constructeur (utilisé par `UserRepository`) | Ajouté |
| `repository/LotRepository.php` | Constructeur `__construct(private PDO $pdo)` mais instanciation `new LotRepository()` sans argument | Corrigé : connexion via `Database::getConnection()` en interne |
| `repository/LotRepository.php` | `require_once '../Model/MouvementStock.php'` → mauvais nom de fichier (`Mouvement_Stock.php`) | Corrigé en `MouvementStock.php` |
| `repository/UserRepository.php` | Même problème constructeur PDO | Corrigé |
| `script/database.sql` | Colonne `mot_de_passe` absente de la table `UTILISATEUR` | Ajoutée + données de test avec hash `password_hash` |

### 🟠 Bugs importants

| Fichier | Problème | Correction |
|---|---|---|
| `public/index.php` | `include '../templates/login.php'` (minuscule) mais fichier est `Login.php` (majuscule) → erreur sur Linux | Uniformisé |
| `public/index.php` | `header('Location:/login')` (manque espace) | Corrigé : `'Location: /login'` |
| `public/index.php` | Résultat de `receptionnerLot()` affiché avec `print_r()` dans le HTML | Stocké dans `$result` et affiché proprement dans le template |
| `Controller/StockController.php` | `sortieFEFO()` ne consommait que les lots `DISPONIBLE`, pas `ALERTE_ORANGE`/`ALERTE_ROUGE` | Requête FEFO corrigée dans `LotRepository` |
| `Model/Lot.php` | `decremente()` ne mettait pas à jour le statut à `PERIME` quand quantité = 0 | Ajouté |

### 🟡 Améliorations

| Fichier | Amélioration |
|---|---|
| `repository/LotRepository.php` | UUID v4 correct au lieu de `uniqid()` (collision possible) |
| `repository/LotRepository.php` | `findAlertes()` retourne maintenant `jours_restants` |
| `Model/Medicament.php` | Faute de frappe corrigée : `Medicalment.php` → `Medicament.php` |
| `script/database.sql` | Ajout d'un lot en alerte rouge pour tester les alertes dashboard |
