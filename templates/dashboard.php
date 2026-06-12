<?php $pageTitle = 'Tableau de bord'; require __DIR__ . '/header.php'; ?>

<h1 class="text-2xl font-semibold text-slate-700 mb-6">Tableau de bord</h1>

<?php if (!empty($rouge)): ?>
    <div class="bg-red-50 border border-red-300 text-red-800 rounded-xl px-5 py-3 mb-6 flex items-center justify-between">
        <span>⚠️ <strong><?= count($rouge) ?> lot(s)</strong> expirent dans moins de 30 jours</span>
        <a href="<?= url('/alertes') ?>?filtre=rouge" class="text-sm underline">Voir →</a>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-medium text-slate-700 mb-1">📦 Réception de lot</h2>
        <p class="text-sm text-slate-400 mb-4">Enregistrer un nouveau lot fournisseur</p>
        <a href="<?= url('/stock/reception') ?>" class="inline-block bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            Enregistrer un lot
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-medium text-slate-700 mb-1">🔔 Alertes péremption</h2>
        <p class="text-sm text-slate-400 mb-4">
            <?php
                 $rouge = $rouge ?? [];
                 $orange = $orange ?? [];
            ?>
            <span class="inline-block bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full mr-1">
                <?= count($rouge) ?> rouge
            </span>
            <span class="inline-block bg-orange-100 text-orange-700 text-xs px-2 py-0.5 rounded-full">
                <?= count($orange) ?> orange
            </span>
        </p>
        <a href="<?= url('/alertes') ?>" class="inline-block bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            Voir les alertes
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-medium text-slate-700 mb-1">💊 Dispensation FEFO</h2>
        <p class="text-sm text-slate-400 mb-4">Sortie automatique du lot le plus proche d'expiration</p>
        <a href="<?= url('/dispense') ?>" class="inline-block bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            Dispenser
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-medium text-slate-700 mb-1">📊 Rapport mensuel</h2>
        <p class="text-sm text-slate-400 mb-4">Générer le rapport des mouvements du mois</p>
        <a href="<?= url('/rapport') ?>" class="inline-block bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            Générer
        </a>
    </div>

</div>

<?php require __DIR__ . '/footer.php'; ?>
