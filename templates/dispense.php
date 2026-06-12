<?php $pageTitle = 'Dispensation FEFO'; require __DIR__ . '/header.php'; ?>

<div class="max-w-xl mx-auto">

    <h1 class="text-2xl font-semibold text-slate-700 mb-6">💊 Dispensation FEFO</h1>

    <?php if (isset($result)): ?>
        <div class="rounded-xl px-5 py-3 mb-6 text-sm font-medium
            <?= $result['success']
                ? 'bg-green-50 border border-green-300 text-green-800'
                : 'bg-red-50 border border-red-300 text-red-800' ?>">
            <?= htmlspecialchars($result['message']) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow p-8">
        <p class="text-sm text-slate-500 mb-6">
            Le système sélectionne automatiquement le lot le plus proche de péremption (logique FEFO).
        </p>
        <form method="POST" action="<?= url('/dispense') ?>" class="space-y-5">

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Médicament (ID)</label>
                <input type="text" name="medicament_id" required
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                    placeholder="UUID du médicament">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Quantité à dispenser</label>
                <input type="number" name="quantite" required min="1"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                    placeholder="ex: 10">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Type de sortie</label>
                <select name="type_sortie"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="SORTIE_DISPENSATION">Dispensation</option>
                    <option value="SORTIE_VENTE">Vente</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 rounded-xl transition">
                    Valider la sortie FEFO
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
