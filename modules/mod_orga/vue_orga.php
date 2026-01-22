<?php
require_once __DIR__ . '/../mod_product/vue_product.php';

class vue_orga {
    public function displayOrga($orga, $products, $isMember = false, $userRole = null, $balance = 0, $cartCount = 0, $isPending = false, $memberInfo = null) {
        $price = number_format($orga['prix_adhesion'] ?? 10, 2);
        ?>
        <div id="app-orga" class="pb-20 md:pb-8 pt-4" data-buvette-id="<?= $orga['id'] ?>">
            <div class="bg-white px-4 py-6 shadow-sm mb-6 border-b border-gray-100 rounded-3xl mx-4 relative overflow-hidden">
                <?php if (!empty($orga['photo'])): ?>
                    <div class="absolute inset-0 z-0">
                        <img src="<?= htmlspecialchars($orga['photo']) ?>" class="w-full h-full object-cover opacity-10">
                        <div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>
                    </div>
                <?php endif; ?>
                <div class="max-w-7xl mx-auto relative z-10">
                    <a href="index.php" class="text-indigo-600 font-bold text-xs uppercase flex items-center mb-4">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Retour
                    </a>
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-3xl font-black text-gray-900 leading-tight"><?= htmlspecialchars($orga['name']) ?></h1>
                            <p class="text-gray-500 text-sm mt-1"><?= htmlspecialchars($orga['address']) ?></p>
                            
                            <?php if(!empty($orga['email']) || !empty($orga['telephone'])): ?>
                                <div class="mt-3 flex flex-col sm:flex-row sm:items-center gap-3 text-xs text-gray-400 font-bold">
                                    <?php if(!empty($orga['email'])): ?>
                                        <a href="mailto:<?= htmlspecialchars($orga['email']) ?>" class="flex items-center hover:text-indigo-600 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            <?= htmlspecialchars($orga['email']) ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if(!empty($orga['telephone'])): ?>
                                        <a href="tel:<?= htmlspecialchars($orga['telephone']) ?>" class="flex items-center hover:text-indigo-600 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            <?= htmlspecialchars($orga['telephone']) ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="flex flex-wrap gap-3 items-center">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php if ($isMember): ?>
                                    <a href="index.php?page=solde&buvette_id=<?= $orga['id'] ?>" class="flex items-center bg-indigo-50 px-4 py-2 rounded-xl border border-indigo-100 transition hover:bg-indigo-100">
                                        <span class="text-xs font-black text-indigo-400 uppercase mr-2">Solde</span>
                                        <span class="text-indigo-700 font-black"><?= number_format($balance, 2) ?> €</span>
                                    </a>

                                    <a href="index.php?page=buy&buvette_id=<?= $orga['id'] ?>" class="flex items-center bg-white px-4 py-2 rounded-xl border border-gray-200 shadow-sm transition hover:bg-gray-50 relative group text-gray-600 hover:text-indigo-600">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        <span class="text-xs font-black uppercase">Panier</span>
                                        <span class="cart-badge absolute -top-2 -right-2 bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full ring-2 ring-white shadow-sm" style="<?= $cartCount > 0 ? '' : 'display:none;' ?>"><?= $cartCount ?></span>
                                    </a>
                                <?php endif; ?>

                                <?php if (!$isMember): ?>
                                    <?php if ($isPending): ?>
                                        <div class="flex items-center gap-2">
                                            <span class="px-4 py-2 bg-yellow-50 text-yellow-600 text-[10px] font-black uppercase rounded-xl border border-yellow-100 cursor-default">
                                                En attente
                                            </span>
                                            <a href="index.php?page=orga&id=<?= $orga['id'] ?>&action=cancel_join" 
                                               class="px-4 py-2 bg-red-50 text-red-500 text-[10px] font-black uppercase rounded-xl border border-red-100 hover:bg-red-100 transition">
                                                Annuler
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <button @click="showJoinModal = true" 
                                           class="px-6 py-2 bg-indigo-600 text-white text-[10px] font-black uppercase rounded-xl shadow-lg shadow-indigo-200 transition active:scale-95 hover:bg-indigo-700">
                                            Rejoindre
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center text-[10px] font-black text-green-600 bg-green-50 px-4 py-2 rounded-xl border border-green-100 uppercase">
                                            ✓ Membre
                                        </span>
                                        <?php if ($memberInfo && $memberInfo['est_demissionnaire']): ?>
                                            <span class="px-4 py-2 bg-gray-100 text-gray-500 text-[10px] font-black uppercase rounded-xl border border-gray-200 cursor-default">
                                                Fin le <?= date('d/m/Y', strtotime($memberInfo['date_adhesion'] . ' +1 year')) ?>
                                            </span>
                                        <?php else: ?>
                                            <a href="index.php?page=orga&id=<?= $orga['id'] ?>&action=leave" 
                                               onclick="return confirm('Voulez-vous vraiment résilier votre adhésion ? Elle prendra fin 1 an après votre date d\'inscription.');"
                                               class="px-4 py-2 bg-gray-50 text-gray-400 text-[10px] font-black uppercase rounded-xl border border-gray-100 hover:bg-gray-100 hover:text-gray-600 transition">
                                                Quitter
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Join Modal -->
            <div v-if="showJoinModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative">
                    <button @click="showJoinModal = false" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    
                    <h3 class="text-xl font-black text-gray-900 mb-2 uppercase tracking-tight">Devenir Adhérent</h3>
                    <p class="text-gray-500 text-xs mb-6">Veuillez compléter vos informations pour envoyer votre demande au gestionnaire.</p>
                    
                    <form action="index.php?page=orga&id=<?= $orga['id'] ?>&action=submit_join" method="POST" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Nom</label><input type="text" name="nom" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 font-bold text-sm"></div>
                            <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Prénom</label><input type="text" name="prenom" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 font-bold text-sm"></div>
                        </div>
                        <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Adresse</label><input type="text" name="adresse" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 font-bold text-sm"></div>
                        
                        <div class="flex items-center bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <input type="checkbox" name="majeur" id="majeur" required class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300">
                            <label for="majeur" class="ml-3 text-xs font-bold text-gray-700">Je certifie être majeur</label>
                        </div>

                        <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl shadow-indigo-100 active:scale-95 transition-transform mt-2">
                            Confirmer (<?= $price ?> € / an)
                        </button>
                    </form>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                    <h3 class="text-xl font-black text-gray-900 flex items-center">
                        <span class="bg-indigo-600 w-1.5 h-6 mr-3 rounded-full"></span>
                        Produits
                    </h3>
                    
                    <div class="flex flex-wrap gap-2 overflow-x-auto no-scrollbar pb-2">
                        <button @click="filterCat = ''" 
                                :class="filterCat === '' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-gray-500 border border-gray-100 hover:border-indigo-200'"
                                class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                            Tout
                        </button>
                        <button v-for="cat in categories" :key="cat"
                                @click="filterCat = cat"
                                :class="filterCat === cat ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-gray-500 border border-gray-100 hover:border-indigo-200'"
                                class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                            {{ cat }}
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="product in filteredProducts" :key="product.id" class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col transition-all hover:shadow-xl hover:-translate-y-1">
                        <div class="bg-gray-50 h-32 rounded-2xl mb-4 flex items-center justify-center text-gray-300 relative overflow-hidden">
                            <img v-if="product.photo" :src="product.photo" class="w-full h-full object-cover absolute inset-0">
                            <svg v-else class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <span v-if="product.in_cart" class="badge-in-cart absolute top-2 right-2 bg-indigo-600 text-white text-[9px] font-black px-2 py-1 rounded-lg shadow-lg z-10">Au panier</span>
                        </div>
                        <span class="inline-block bg-indigo-50 text-indigo-600 text-[9px] font-black uppercase px-2 py-1 rounded-lg mb-2 self-start">{{ product.categorie || 'Divers' }}</span>
                        <h5 class="text-xl font-black text-gray-900 mb-1 truncate">{{ product.name }}</h5>
                        <p class="text-indigo-600 font-black mb-4">{{ parseFloat(product.price).toFixed(2) }} €</p>
                        
                        <div class="mt-auto space-y-2">
                            <a :href="'index.php?page=product&id=' + product.id" 
                               class="block text-center py-2 bg-gray-50 text-gray-400 rounded-xl font-bold uppercase text-[9px] tracking-widest hover:text-gray-600 transition">
                                Infos
                            </a>
                            
                            <div v-if="isMember">
                                <div v-if="product.stock > 0" class="cart-controls card-controls" :data-product-id="product.id">
                                    <div v-if="!product.qty" class="h-12">
                                        <button @click="updateCart(product.id, 'add')" class="btn-add block w-full py-3 bg-indigo-600 text-white rounded-xl font-black uppercase text-xs tracking-widest shadow-lg shadow-indigo-100 active:scale-95 transition h-full flex items-center justify-center">
                                            Ajouter
                                        </button>
                                    </div>
                                    <div v-else class="h-12 flex items-center justify-between bg-indigo-50 rounded-xl p-1 border border-indigo-100">
                                        <button @click="updateCart(product.id, 'remove_one')" class="w-10 h-full flex items-center justify-center bg-white rounded-lg text-indigo-600 shadow-sm active:scale-90 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                                        </button>
                                        <span class="qty-val font-black text-indigo-900 text-sm">{{ product.qty }}</span>
                                        <button @click="updateCart(product.id, 'add')" class="w-10 h-full flex items-center justify-center bg-indigo-600 rounded-lg text-white shadow-md active:scale-90 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                <div v-else class="py-3 text-center text-red-400 font-black uppercase text-[10px] tracking-widest h-12 flex items-center justify-center italic">Épuisé</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="filteredProducts.length === 0" class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-gray-200 mt-6">
                    <p class="text-gray-400 font-bold uppercase tracking-wider">Aucun produit dans cette catégorie</p>
                </div>
            </div>
        </div>

        <script>
            if (typeof Vue !== 'undefined' && document.getElementById('app-orga')) {
                const { createApp } = Vue;
                createApp({
                                            data() {
                                                const cart = <?= json_encode($_SESSION['cart'] ?? []) ?>;
                                                const products = <?= json_encode($products) ?>.map(p => ({
                                                    ...p,
                                                    qty: cart[p.id_buvette] && cart[p.id_buvette][p.id] ? cart[p.id_buvette][p.id].quantity : 0,
                                                    in_cart: cart[p.id_buvette] && cart[p.id_buvette][p.id] ? true : false
                                                }));
                                                return {
                                                    products: products,
                                                    filterCat: '',
                                                    showJoinModal: false,
                                                    isMember: <?= json_encode($isMember) ?>
                                                }
                                            },                    computed: {
                        categories() {
                            return [...new Set(this.products.map(p => p.categorie || 'Divers'))];
                        },
                        filteredProducts() {
                            if (!this.filterCat) return this.products;
                            return this.products.filter(p => (p.categorie || 'Divers') === this.filterCat);
                        }
                    },
                    methods: {
                        async updateCart(id, action) {
                            // Appel de la fonction globale updateCart définie dans index.php
                            await window.updateCart(id, action);
                            // Rafraîchir les données locales depuis la session (simulation pour la réactivité UI)
                            const p = this.products.find(x => x.id == id);
                            if (action === 'add') p.qty++;
                            else if (action === 'remove_one') p.qty--;
                            p.in_cart = p.qty > 0;
                        }
                    }
                }).mount('#app-orga');
            }
        </script>
        <?php
    }
}
?>