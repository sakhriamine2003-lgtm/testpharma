<?php $pageTitle = 'Réception de lot'; require __DIR__ . '/header.php'; ?>

<div class="max-w-xl mx-auto">

    <h1 class="text-2xl font-semibold text-slate-700 mb-6">📦 Réception d'un nouveau lot</h1>

    <?php if (isset($result)): ?>
        <div class="rounded-xl px-5 py-3 mb-6 text-sm font-medium
            <?= $result['success'] ? 'bg-green-50 border border-green-300 text-green-800' : 'bg-red-50 border border-red-300 text-red-800' ?>">
            <?= htmlspecialchars($result['message']) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow p-8">
        <form method="POST" action="<?= url('/stock/reception') ?>" class="space-y-5">

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Médicament</label>
                <select name="medicament_id" required
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($medicaments as $m): ?>
                        <option value="<?= htmlspecialchars($m['id']) ?>">
                            <?= htmlspecialchars($m['nom_commercial']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Numéro de lot</label>
                <input type="text" name="numero_lot" required
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                    placeholder="ex: LOT-2026-001">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Date de péremption</label>
                <input type="date" name="date_peremption" required
                    min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Quantité</label>
                <input type="number" name="quantite" required min="1"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                    placeholder="100">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Prix d'achat unitaire (MAD)</label>
                <input type="number" name="prix_achat" required min="0" step="0.01"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                    placeholder="1.50">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 rounded-xl transition">
                    Enregistrer le lot
                </button>
                <a href="<?= url('/dashboard') ?>"
                    class="flex-1 text-center border border-slate-300 text-slate-600 hover:bg-slate-50 font-medium py-3 rounded-xl transition">
                    Annuler
                </a>
            </div>

        </form>
    </div>

</div>

<?php require __DIR__ . '/footer.php'; ?>
