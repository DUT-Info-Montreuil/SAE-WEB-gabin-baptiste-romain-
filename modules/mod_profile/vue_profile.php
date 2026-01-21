<?php
class vue_profile {
    public function form_profile($user, $roles = [], $message = null) {
        ?>
        <div class="max-w-xl mx-auto px-4 py-8 pb-32">
            <header class="mb-8 md:hidden">
                <a href="index.php" class="text-indigo-600 font-bold text-xs uppercase flex items-center mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Retour
                </a>
                <h1 class="text-3xl font-black text-gray-900">Paramètres Profil</h1>
            </header>
            
            <?php if ($message): ?>
                <div class="bg-indigo-50 text-indigo-600 p-4 rounded-2xl border-2 border-indigo-100 mb-6 font-bold text-center">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100">
                <div class="flex flex-col items-center mb-8">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h2 class="text-xl font-black text-gray-900"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest"><?= htmlspecialchars($user['email']) ?></p>
                </div>

                <form method="post" action="index.php?page=profile&action=update" class="space-y-6">
                    <div>
                        <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Nom</label>
                        <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold transition-all">
                    </div>
                    
                    <div>
                        <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Prénom</label>
                        <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold transition-all">
                    </div>
                    
                    <div>
                        <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Email</label>
                        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-400 font-bold cursor-not-allowed">
                        <p class="text-[10px] text-gray-400 mt-2 italic">L'adresse email ne peut pas être modifiée.</p>
                    </div>

                    <button type="submit" 
                            class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-sm tracking-widest shadow-lg shadow-indigo-100 active:scale-95 transition-transform mt-4">
                        Mettre à jour
                    </button>
                </form>

                <?php if (!empty($roles)): ?>
                    <div class="mt-10 pt-8 border-t border-gray-50">
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-4 ml-1">Mes Accès Staff</h3>
                        <div class="space-y-3">
                            <?php foreach ($roles as $row): ?>
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                    <div class="flex-grow pr-4 text-left">
                                        <p class="font-black text-gray-900 leading-tight truncate"><?= htmlspecialchars($row['buvette_name']) ?></p>
                                        <p class="text-[10px] font-bold uppercase text-indigo-500 mt-0.5">
                                            <?= $row['role'] === 'ROLE_GESTION' ? 'Gestionnaire' : 'Barman' ?>
                                        </p>
                                    </div>
                                    <a href="index.php?page=<?= $row['role'] === 'ROLE_GESTION' ? 'gestion' : 'barman' ?>&id=<?= $row['id'] ?>" 
                                       class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100 active:scale-95 transition-transform">
                                        Accéder
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mt-10 pt-0 border-t border-gray-50">
                    <a href="index.php?page=auth&action=logout" 
                       class="block w-full py-4 bg-red-50 text-red-600 rounded-2xl font-black uppercase text-sm tracking-widest text-center transition active:scale-95 active:bg-red-100 border border-red-100">
                        Déconnexion
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
