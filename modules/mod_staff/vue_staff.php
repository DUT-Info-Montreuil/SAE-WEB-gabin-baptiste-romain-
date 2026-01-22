<?php
class vue_staff {
    public function displayStaffList($roles) {
        ?>
        <div class="max-w-4xl mx-auto px-4 py-8 pb-24">
            <header class="mb-8">
                <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Espace Staff</h1>
                <p class="text-gray-500 font-medium">Accédez à vos outils de gestion</p>
            </header>

            <?php if (empty($roles)): ?>
                <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 mb-2">Aucun accès staff</h3>
                    <p class="text-gray-500">Vous n'avez aucun rôle de gestion ou de barman pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($roles as $role): ?>
                        <a href="<?= $role['link'] ?>" class="group block bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:border-indigo-500 hover:shadow-lg transition-all relative overflow-hidden">
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="inline-block px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                        <?php
                                        switch($role['type']) {
                                            case 'admin': echo 'bg-purple-100 text-purple-600'; break;
                                            case 'gestion': echo 'bg-blue-100 text-blue-600'; break;
                                            case 'barman': echo 'bg-orange-100 text-orange-600'; break;
                                        }
                                        ?>">
                                        <?= htmlspecialchars($role['title']) ?>
                                    </span>
                                    <svg class="w-5 h-5 text-gray-300 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 group-hover:text-indigo-600 transition-colors mb-1"><?= htmlspecialchars($role['buvette_name']) ?></h3>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Accéder au tableau de bord</p>
                            </div>
                            
                            <!-- Decorative Icon Background -->
                            <div class="absolute -right-6 -bottom-6 text-gray-50 group-hover:text-indigo-50 transition-colors rotate-12">
                                <?php if($role['type'] === 'admin'): ?>
                                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"></path></svg>
                                <?php elseif($role['type'] === 'gestion'): ?>
                                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"></path></svg>
                                <?php else: ?>
                                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M21 5V3H3v2l8 9v5H6v2h12v-2h-5v-5l8-9z"></path></svg>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
?>