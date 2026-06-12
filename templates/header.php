<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmaFefo – <?= htmlspecialchars($pageTitle ?? '') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100">

<?php
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$role = $_SESSION['role'] ?? '';

$navLinks = [
    '/dashboard'       => ['label' => '🏠 Tableau de bord', 'roles' => ['PREPARATEUR','PHARMACIEN','ADMIN']],
    '/stock/reception' => ['label' => '📦 Réception',       'roles' => ['PREPARATEUR','PHARMACIEN','ADMIN']],
    '/alertes'         => ['label' => '🔔 Alertes',         'roles' => ['PREPARATEUR','PHARMACIEN','ADMIN']],
    '/dispense'        => ['label' => '💊 Dispensation',    'roles' => ['PREPARATEUR','PHARMACIEN','ADMIN']],
    '/rapport'         => ['label' => '📊 Rapport',         'roles' => ['PHARMACIEN','ADMIN']],
];

// Détecte si un lien est actif malgré le préfixe de sous-dossier
function isActive(string $path): bool {
    $scriptDir  = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    $base       = dirname($scriptDir);
    if ($base === '/' || $base === '\\') $base = '';
    $full = $base . $path;
    $current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return $current === $full || rtrim($current, '/') === rtrim($full, '/');
}
?>

<nav class="bg-teal-700 text-white shadow">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between h-14">

            <a href="<?= url('/dashboard') ?>" class="font-bold text-lg tracking-wide">FarmaFefo</a>

            <div class="hidden md:flex items-center gap-1">
                <?php foreach ($navLinks as $path => $item): ?>
                    <?php if (in_array($role, $item['roles'])): ?>
                        <a href="<?= url($path) ?>"
                           class="px-3 py-1.5 rounded-lg text-sm font-medium transition
                                  <?= isActive($path) ? 'bg-white text-teal-700' : 'text-teal-100 hover:bg-teal-600' ?>">
                            <?= $item['label'] ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="flex items-center gap-3 text-sm">
                <span class="hidden sm:inline opacity-80 text-xs">
                    <?= htmlspecialchars($_SESSION['nom'] ?? '') ?>
                    <span class="ml-1 bg-teal-600 px-1.5 py-0.5 rounded text-xs">
                        <?= htmlspecialchars($role) ?>
                    </span>
                </span>
                <a href="<?= url('/logout') ?>"
                   class="bg-white text-teal-700 hover:bg-teal-50 font-semibold px-3 py-1.5 rounded-lg text-xs transition">
                    Déconnexion
                </a>
            </div>
        </div>

        <!-- Menu mobile -->
        <div class="md:hidden pb-3 flex flex-wrap gap-1">
            <?php foreach ($navLinks as $path => $item): ?>
                <?php if (in_array($role, $item['roles'])): ?>
                    <a href="<?= url($path) ?>"
                       class="px-3 py-1 rounded-lg text-xs font-medium transition
                              <?= isActive($path) ? 'bg-white text-teal-700' : 'text-teal-100 hover:bg-teal-600' ?>">
                        <?= $item['label'] ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</nav>

<main class="max-w-6xl mx-auto px-4 py-8">
