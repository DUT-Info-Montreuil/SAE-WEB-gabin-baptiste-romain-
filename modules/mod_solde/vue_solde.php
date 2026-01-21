<?php
class vue_solde {
    public function displayBalance($balances, $history, $message = null, $returnBuvetteId = null) {
        $backLink = $returnBuvetteId ? "index.php?page=orga&id=" . $returnBuvetteId : "index.php";
        ?>
        <div class="max-w-xl mx-auto px-4 py-8 pb-32">
            <header class="mb-8 md:hidden">
                <a href="<?= $backLink ?>" class="text-indigo-600 font-bold text-xs uppercase flex items-center mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Retour
                </a>
                <h1 class="text-3xl font-black text-gray-900">Mes Soldes</h1>
            </header>

            <?php if ($message): ?>
                <div class="bg-indigo-50 text-indigo-600 p-4 rounded-2xl border-2 border-indigo-100 mb-6 font-bold text-center">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100 relative">
                <a href="<?= $backLink ?>" class="inline-flex items-center text-gray-400 hover:text-indigo-600 transition-colors mb-6 group">
                    <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    <span class="text-xs font-black uppercase tracking-widest">Retour</span>
                </a>
                
                <div class="grid gap-4 mb-8">
                    <?php foreach ($balances as $balance): ?>
                        <div class="bg-gray-50 rounded-2xl p-4 flex items-center justify-between border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <span class="font-bold text-gray-700"><?= htmlspecialchars($balance['nom']) ?></span>
                            </div>
                            <span class="text-xl font-black text-indigo-600"><?= number_format($balance['solde'], 2) ?> €</span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr class="border-gray-100 my-8">

                <h3 class="text-lg font-black text-gray-900 mb-6">Recharger mon compte</h3>
                
                <form method="post" action="index.php?page=solde&action=recharge" class="space-y-6">
                    <?php if (count($balances) === 1): ?>
                        <input type="hidden" name="buvette_id" value="<?= $balances[0]['id'] ?>">
                    <?php else: ?>
                        <div>
                            <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Buvette</label>
                            <select name="buvette_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold transition-all text-gray-700">
                                <?php foreach ($balances as $balance): ?>
                                    <option value="<?= $balance['id'] ?>" <?= ($returnBuvetteId && $balance['id'] == $returnBuvetteId) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($balance['nom']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div>
                        <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Montant à ajouter (€)</label>
                        <input type="number" name="amount" id="amount" placeholder="0.00" step="0.10" min="1" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold transition-all">
                    </div>

                    <div id="cb-section" style="display: none;" class="space-y-6 pt-4 border-t border-gray-100">
                        <h4 class="text-sm font-black uppercase tracking-widest text-indigo-600 mb-4">Informations de paiement</h4>

                        <div>
                            <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Numéro de carte</label>
                            <input type="text" inputmode="numeric" pattern="[0-9]*" name="cc_number" placeholder="0000 0000 0000 0000" maxlength="19" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold transition-all">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">CVV</label>
                                <input type="text" inputmode="numeric" pattern="[0-9]*" name="cc_ccv" placeholder="000" maxlength="3" required oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold transition-all">
                            </div>
                            <div>
                                <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Expiration</label>
                                <input type="date" name="cc_date" placeholder="MM/AA"
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold transition-all">
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-sm tracking-widest shadow-lg shadow-indigo-100 active:scale-95 transition-transform">
                            Valider et Payer
                        </button>
                    </div>
                </form>

                <hr class="border-gray-100 my-8">

                <div class="flex justify-center">
                    <button type="button" id="toggle-history" class="py-2 px-6 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Voir l'historique
                    </button>
                </div>

                <div id="history-section" style="display: none;" class="mt-8 animate-fade-in-down">
                    <h3 class="text-lg font-black text-gray-900 mb-4">Historique des commandes</h3>
                    <?php if (empty($history)): ?>
                        <div class="text-center py-8 bg-gray-50 rounded-xl border border-gray-100 border-dashed">
                            <p class="text-gray-400 font-bold">Aucune commande récente</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($history as $order): ?>
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 hover:border-indigo-100 transition-colors">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-indigo-500 shadow-sm border border-gray-100">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900"><?= htmlspecialchars($order['buvette_name']) ?></p>
                                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wide"><?= date('d/m/Y • H:i', strtotime($order['date_heure'])) ?></p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-black text-indigo-600">-<?= number_format($order['montant_total'], 2) ?> €</p>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($order['items'])): ?>
                                        <div class="pl-13 ml-3 border-l-2 border-gray-200 pl-4 py-1">
                                            <ul class="text-sm space-y-1">
                                                <?php foreach ($order['items'] as $item): ?>
                                                    <li class="flex justify-between text-gray-600">
                                                        <span><?= htmlspecialchars($item['nom']) ?></span>
                                                        <span class="font-bold text-gray-400">x<?= htmlspecialchars($item['quantite']) ?></span>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <script>
                // Gestion du formulaire de paiement
                const inputMontant = document.getElementById('amount');
                const cbSection = document.getElementById('cb-section');
                const toggleHistoryBtn = document.getElementById('toggle-history');
                const historySection = document.getElementById('history-section');

                if (toggleHistoryBtn) {
                    toggleHistoryBtn.addEventListener('click', function() {
                        if (historySection.style.display === 'none') {
                            historySection.style.display = 'block';
                            this.innerHTML = `
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                Masquer l'historique
                            `;
                        } else {
                            historySection.style.display = 'none';
                            this.innerHTML = `
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Voir l'historique
                            `;
                        }
                    });
                }

                inputMontant.addEventListener('input', function() {
                    const valeur = parseFloat(this.value);
                    if (valeur > 0) {
                        cbSection.style.display = 'block';
                    } else {
                        cbSection.style.display = 'none';
                    }
                });
            </script>
        </div>
        <?php
    }
}
?>