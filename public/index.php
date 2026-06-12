<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/LotRepository.php';
require_once __DIR__ . '/../Controller/DashboardController.php';
require_once __DIR__ . '/../Controller/StockController.php';
require_once __DIR__ . '/../Controller/RapportController.php';

// ---------------------------------------------------------------
// Détection automatique du sous-dossier XAMPP
// Ex: /PharmaFEFO_nav/login  →  $uri = /login
// Ex: /login                 →  $uri = /login
// ---------------------------------------------------------------
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');  // /PharmaFEFO_nav/public  ou  ""
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = dirname($scriptDir); // /PharmaFEFO_nav  ou  ""

// Supprime le préfixe du sous-dossier pour obtenir la route propre
if ($base !== '/' && $base !== '' && str_starts_with($requestUri, $base)) {
    $uri = substr($requestUri, strlen($base));
} else {
    $uri = $requestUri;
}
if ($uri === '' || $uri === false) $uri = '/';

// ---------------------------------------------------------------
// Helper : génère une URL absolue selon l'environnement
// ---------------------------------------------------------------
function url(string $path): string
{
    $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    $base = dirname($scriptDir);
    if ($base === '/' || $base === '\\') $base = '';
    return $base . $path;
}

/* ---- Guard ---- */
if (!isset($_SESSION['user_id']) && $uri !== '/login') {
    header('Location: ' . url('/login'));
    exit;
}

/* ---- Routeur ---- */
switch ($uri) {

    /* ---------- LOGIN ---------- */
    case '/login':
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $repo = new UserRepository();
            $u    = $repo->findByEmail(trim($_POST['email'] ?? ''));

            if ($u && password_verify($_POST['password'] ?? '', $u->getMotDePasse())) {
                $_SESSION['user_id'] = $u->getId();
                $_SESSION['nom']     = $u->getNom();
                $_SESSION['role']    = $u->getRole();
                header('Location: ' . url('/dashboard'));
                exit;
            }
            $error = 'Email ou mot de passe incorrect.';
        }

        include __DIR__ . '/../templates/Login.php';
        break;

    /* ---------- LOGOUT ---------- */
    case '/logout':
        session_destroy();
        header('Location: ' . url('/login'));
        exit;

    /* ---------- DASHBOARD ---------- */
    case '/dashboard':
        $ctrl   = new DashboardController();
        $data   = $ctrl->index();
        $rouge  = $data['rouge'];
        $orange = $data['orange'];
        include __DIR__ . '/../templates/dashboard.php';
        break;

    /* ---------- RÉCEPTION ---------- */
    case '/stock/reception':
        $result = null;
        $repo   = new LotRepository();
        $medicaments = $repo->findAllMedicaments();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ctrl   = new StockController();
            $result = $ctrl->receptionnerLot(
                trim($_POST['medicament_id']   ?? ''),
                trim($_POST['numero_lot']       ?? ''),
                trim($_POST['date_peremption']  ?? ''),
                (int)   ($_POST['quantite']     ?? 0),
                (float) ($_POST['prix_achat']   ?? 0),
                $_SESSION['user_id']
            );
        }

        include __DIR__ . '/../templates/reception.php';
        break;

    /* ---------- ALERTES ---------- */
    case '/alertes':
        $repo    = new LotRepository();
        $alertes = $repo->findAlertes(90, 30);
        $rouge   = array_values(array_filter($alertes, fn($a) => $a['criticite'] === 'rouge'));
        $orange  = array_values(array_filter($alertes, fn($a) => $a['criticite'] === 'orange'));
        $filtre  = $_GET['filtre'] ?? '';
        include __DIR__ . '/../templates/alertes.php';
        break;

    /* ---------- DISPENSATION FEFO ---------- */
    case '/dispense':
        $result = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ctrl   = new StockController();
            $result = $ctrl->sortieFEFO(
                trim($_POST['medicament_id'] ?? ''),
                (int) ($_POST['quantite']    ?? 0),
                $_SESSION['user_id'],
                $_POST['type_sortie'] ?? 'SORTIE_DISPENSATION'
            );
        }

        include __DIR__ . '/../templates/dispense.php';
        break;

    /* ---------- RAPPORT ---------- */
    case '/rapport':
        if (!in_array($_SESSION['role'] ?? '', ['PHARMACIEN', 'ADMIN'])) {
            header('Location: ' . url('/dashboard'));
            exit;
        }
        $ctrl       = new RapportController();
        $data       = $ctrl->index($_GET['mois'] ?? '');
        $mouvements = $data['mouvements'];
        $mois       = $data['mois'];
        include __DIR__ . '/../templates/rapport.php';
        break;

    /* ---------- 404 ---------- */
    default:
        http_response_code(404);
        echo '<h1 style="font-family:sans-serif;text-align:center;margin-top:4rem">404 – Page introuvable</h1>';
        echo '<p style="text-align:center"><a href="' . url('/dashboard') . '">Retour au tableau de bord</a></p>';
}
