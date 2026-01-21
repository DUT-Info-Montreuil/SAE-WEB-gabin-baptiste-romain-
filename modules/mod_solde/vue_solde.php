<?php
class vue_solde {
    public function displayBalance($balance, $history, $message = null) {
        ?>
        <div class="max-w-xl mx-auto px-4 py-8 pb-32">
            <header class="mb-8 md:hidden">
                <a href="index.php" class="text-indigo-600 font-bold text-xs uppercase flex items-center mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Retour
                </a>
                <h1 class="text-3xl font-black text-gray-900">Mon Solde</h1>
            </header>

            <?php if ($message): ?>
                <div class="bg-indigo-50 text-indigo-600 p-4 rounded-2xl border-2 border-indigo-100 mb-6 font-bold text-center">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100 relative">
                
                <div class="flex flex-col items-center mb-8">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-xl font-black text-gray-900">Solde Actuel</h2>
                    <p class="text-4xl font-black text-indigo-600 mt-2"><?= number_format($balance, 2) ?> €</p>
                </div>

                <hr class="border-gray-100 my-8">

                <h3 class="text-lg font-black text-gray-900 mb-6">Recharger mon compte</h3>
                
                <form method="post" action="index.php?page=solde&action=recharge" class="space-y-6">
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
            </div>

            <script>
                // Gestion du formulaire de paiement
                const inputMontant = document.getElementById('amount');
                const cbSection = document.getElementById('cb-section');

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