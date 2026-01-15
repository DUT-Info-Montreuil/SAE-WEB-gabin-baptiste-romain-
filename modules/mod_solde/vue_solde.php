<?php
class vue_solde {
    public function displaySolde($balance, $history, $message = null) {
        ?>
        <div id="app-solde">
            <div class="max-w-xl mx-auto px-4 py-8 pb-32">
                <header class="mb-8 flex justify-between items-start md:hidden">
                    <div>
                        <a href="index.php" class="text-indigo-600 font-bold text-xs uppercase flex items-center mb-4">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            Retour
                        </a>
                        <h1 class="text-3xl font-black text-gray-900">Solde & Historique</h1>
                    </div>
                </header>

                <div class="hidden md:flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-black text-gray-900">Solde & Historique</h1>
                </div>

                <?php if ($message): ?>
                    <div class="bg-green-50 text-green-600 p-4 rounded-2xl border-2 border-green-100 mb-6 font-bold text-center">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <div class="bg-indigo-600 rounded-3xl p-8 shadow-xl shadow-indigo-100 text-white mb-8">
                    <span class="text-indigo-200 font-black uppercase text-xs tracking-widest">Mon Solde Actuel</span>
                    <div class="text-5xl font-black mt-2"><?= number_format($balance, 2) ?> €</div>
                    
                    <form method="post" action="index.php?page=solde&action=add" class="mt-8 flex flex-col sm:flex-row gap-3">
                        <input type="number" name="amount" placeholder="Montant (€)" min="1" required
                               class="w-full sm:flex-grow px-4 py-4 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:bg-white/20 focus:outline-none font-bold text-lg">
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-white text-indigo-600 rounded-xl font-black uppercase text-sm tracking-widest active:scale-95 transition-transform shadow-lg">
                            Recharger
                        </button>
                    </form>
                </div>

                <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center px-2">
                    <span class="bg-indigo-600 w-1.5 h-6 mr-3 rounded-full"></span>
                    Derniers Achats
                </h3>

                <div class="space-y-4">
                    <?php foreach ($history as $order): ?>
                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-black text-gray-900 leading-tight"><?= htmlspecialchars($order['buvette_name']) ?></h4>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter"><?= date('d/m/Y H:i', strtotime($order['date_heure'])) ?></p>
                                </div>
                                <span class="font-black text-indigo-600">-<?= number_format($order['montant_total'], 2) ?> €</span>
                            </div>
                            <div class="space-y-1">
                                <?php foreach ($order['items'] as $item): ?>
                                    <p class="text-xs text-gray-500 font-medium">
                                        <span class="text-indigo-400 font-black mr-1"><?= $item['quantite'] ?>x</span> 
                                        <?= htmlspecialchars($item['nom']) ?>
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($history)): ?>
                        <div class="bg-gray-50 rounded-3xl p-12 text-center border-2 border-dashed border-gray-200">
                            <p class="text-gray-400 font-bold uppercase tracking-wider text-xs">Aucun historique d'achat</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script>
            if (typeof Vue !== 'undefined') {
                const { createApp } = Vue;
                createApp({
                    data() {
                        return {
                        }
                    },
                    methods: {
                    }
                }).mount('#app-solde');
            }
        </script>
        <?php
    }
}
?>
