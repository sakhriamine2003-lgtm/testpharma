<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>FarmaFefo – Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-900 flex items-center justify-center p-4">

<div class="w-full max-w-md">

    <div class="text-center mb-8">
        <h1 class="text-4xl text-white mb-1">FarmaFefo</h1>
        <p class="text-slate-400 text-sm">Gestion de stock pharmaceutique (FEFO)</p>
    </div>

    <div class="bg-white rounded-2xl shadow-2xl p-8">

        <h2 class="text-2xl text-slate-800 mb-6">Connexion</h2>

        <?php if (!empty($error)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 mb-5 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('/login') ?>" class="space-y-5">

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Email</label>
                <input type="email" name="email" required autocomplete="email"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                    placeholder="exemple@pharmacie.com">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase">Mot de passe</label>
                <input type="password" name="password" required autocomplete="current-password"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                    placeholder="••••••••">
            </div>

            <button type="submit"
                class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 rounded-xl transition">
                Se connecter
            </button>

        </form>

        <div class="mt-6 pt-5 border-t text-xs text-slate-400">
            <p class="font-semibold mb-1">Rôles système :</p>
            <p>ADMIN · PHARMACIEN · PREPARATEUR</p>
        </div>

    </div>
</div>

</body>
</html>
