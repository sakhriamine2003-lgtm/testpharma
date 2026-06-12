<?php $pageTitle = 'Alertes péremption'; require __DIR__ . '/header.php'; ?>

<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-slate-700">🔔 Alertes péremption</h1>
    <div class="flex gap-2 text-sm">
        <a href="<?= url('/alertes') ?>?filtre=rouge"
           class="px-3 py-1 rounded-lg font-medium transition
                  <?= ($filtre === 'rouge') ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200' ?>">
            Rouge (<?= count($rouge) ?>)
        </a>
        <a href="<?= url('/alertes') ?>?filtre=orange"
           class="px-3 py-1 rounded-lg font-medium transition
                  <?= ($filtre === 'orange') ? 'bg-orange-500 text-white' : 'bg-orange-100 text-orange-700 hover:bg-orange-200' ?>">
            Orange (<?= count($orange) ?>)
        </a>
        <a href="<?= url('/alertes') ?>"
           class="px-3 py-1 rounded-lg font-medium transition
                  <?= ($filtre === '') ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
            Tous
        </a>
    </div>
</div>

<?php
$affichage = match($filtre) {
    'rouge'  => $rouge,
    'orange' => $orange,
    default  => array_merge($rouge, $orange),
};
?>

<?php if (empty($affichage)): ?>
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-6 text-center">
        ✅ Aucune alerte péremption pour le moment.
    </div>
<?php else: ?>
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Médicament</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">N° lot</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Date péremption</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Jours restants</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Qté</th>
                    <th class="text-left px-5 py-3 font-semibold text-slate-600">Criticité</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($affichage as $lot): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-medium text-slate-800">
                            <?= htmlspecialchars($lot['nom_commercial']) ?>
                        </td>
                        <td class="px-5 py-3 text-slate-600 font-mono text-xs">
                            <?= htmlspecialchars($lot['numero_lot']) ?>
                        </td>
                        <td class="px-5 py-3 text-slate-600">
                            <?= htmlspecialchars($lot['date_peremption']) ?>
                        </td>
                        <td class="px-5 py-3 font-semibold
                            <?= $lot['criticite'] === 'rouge' ? 'text-red-600' : 'text-orange-600' ?>">
                            <?= $lot['jours_restants'] ?> j
                        </td>
                        <td class="px-5 py-3 text-slate-600"><?= $lot['quantite_disponible'] ?></td>
                        <td class="px-5 py-3">
                            <?php if ($lot['criticite'] === 'rouge'): ?>
                                <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full font-semibold">🔴 ROUGE</span>
                            <?php else: ?>
                                <span class="bg-orange-100 text-orange-700 text-xs px-2 py-0.5 rounded-full font-semibold">🟠 ORANGE</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/footer.php'; ?>
