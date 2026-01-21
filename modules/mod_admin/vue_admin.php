<?php
class vue_admin {
    public function displayList($buvettes) {
        ?>
        <div class="max-w-7xl mx-auto px-4 py-8">
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-black text-gray-900">Administration Buvettes</h1>
                <a href="index.php?page=admin&action=form_add" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-black uppercase text-xs tracking-widest shadow-lg hover:bg-indigo-700 transition">
                    Nouvelle Buvette
                </a>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($buvettes as $b): ?>
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col">
                        <div class="h-40 bg-gray-100 rounded-2xl mb-4 overflow-hidden relative">
                            <?php if (!empty($b['photo'])): ?>
                                <img src="<?= htmlspecialchars($b['photo']) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h2 class="text-xl font-black text-gray-900 mb-1"><?= htmlspecialchars($b['nom']) ?></h2>
                        <p class="text-gray-500 text-sm mb-4"><?= htmlspecialchars($b['adresse']) ?></p>
                        <div class="mt-auto flex gap-2">
                            <a href="index.php?page=admin&action=form_edit&id=<?= $b['id'] ?>" class="flex-1 py-2 text-center bg-gray-50 text-indigo-600 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-50 transition">Modifier</a>
                            <a href="index.php?page=admin&action=manage_staff&id=<?= $b['id'] ?>" class="flex-1 py-2 text-center bg-indigo-50 text-indigo-700 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-100 transition">Staff</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    public function displayForm($buvette = null) {
        $isEdit = $buvette !== null;
        $action = $isEdit ? "edit&id=" . $buvette['id'] : "add";
        ?>
        <div class="max-w-xl mx-auto px-4 py-8">
            <header class="mb-8">
                <a href="index.php?page=admin" class="text-indigo-600 font-bold text-xs uppercase flex items-center mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Retour
                </a>
                <h1 class="text-3xl font-black text-gray-900"><?= $isEdit ? 'Modifier' : 'Ajouter' ?> une buvette</h1>
            </header>

            <form action="index.php?page=admin&action=<?= $action ?>" method="post" enctype="multipart/form-data" class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100 space-y-6">
                
                <div>
                    <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Nom</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($buvette['nom'] ?? '') ?>" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
                </div>

                <div>
                    <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Adresse</label>
                    <input type="text" name="adresse" value="<?= htmlspecialchars($buvette['adresse'] ?? '') ?>"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($buvette['email'] ?? '') ?>"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
                    </div>
                    <div>
                        <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Téléphone</label>
                        <input type="tel" name="telephone" value="<?= htmlspecialchars($buvette['telephone'] ?? '') ?>"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Prix Adhésion (€)</label>
                    <input type="number" step="0.01" name="prix" value="<?= htmlspecialchars($buvette['prix_adhesion'] ?? '10.00') ?>" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
                </div>

                <div>
                    <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Photo</label>
                    <?php if ($isEdit && !empty($buvette['photo'])): ?>
                        <div class="mb-2">
                            <img src="<?= htmlspecialchars($buvette['photo']) ?>" class="h-20 w-20 object-cover rounded-lg">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="photo" accept="image/*"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-sm tracking-widest shadow-lg shadow-indigo-100 active:scale-95 transition-transform">
                    Enregistrer
                </button>
            </form>
        </div>
        <?php
    }

    public function displayStaffForm($buvette, $staff) {
        ?>
        <div class="max-w-xl mx-auto px-4 py-8">
            <header class="mb-8">
                <a href="index.php?page=admin" class="text-indigo-600 font-bold text-xs uppercase flex items-center mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Retour
                </a>
                <h1 class="text-3xl font-black text-gray-900">Gérer le Staff</h1>
                <p class="text-gray-500 font-medium"><?= htmlspecialchars($buvette['nom']) ?></p>
            </header>

            <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100 mb-8">
                <h2 class="text-xl font-black text-gray-900 mb-6">Ajouter un membre</h2>
                <form action="index.php?page=admin&action=assign_role&id=<?= $buvette['id'] ?>" method="post" class="space-y-4">
                    <div>
                        <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Email de l'utilisateur</label>
                        <input type="email" name="email" required placeholder="user@example.com"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
                    </div>
                    <div>
                        <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Rôle</label>
                        <select name="role" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
                            <option value="ROLE_GESTION">Gestionnaire</option>
                            <option value="ROLE_BARMAN">Barman</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-sm tracking-widest shadow-lg shadow-indigo-100 active:scale-95 transition-transform">
                        Assigner le rôle
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100">
                <h2 class="text-xl font-black text-gray-900 mb-6">Staff Actuel</h2>
                <?php if (empty($staff)): ?>
                    <p class="text-gray-400 italic text-center">Aucun membre du staff assigné.</p>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($staff as $member): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <div>
                                    <p class="font-black text-gray-900"><?= htmlspecialchars($member['prenom'] . ' ' . $member['nom']) ?></p>
                                    <p class="text-xs text-gray-500"><?= htmlspecialchars($member['email']) ?></p>
                                </div>
                                <span class="px-3 py-1 bg-white border border-gray-200 rounded-lg text-[10px] font-black uppercase text-indigo-600 tracking-widest">
                                    <?= $member['role'] === 'ROLE_GESTION' ? 'Gestionnaire' : 'Barman' ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
?>