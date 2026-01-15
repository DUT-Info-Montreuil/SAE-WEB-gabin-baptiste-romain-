<?php
require_once __DIR__ . '/../mod_product/vue_product.php';

class vue_orga {
    public function displayOrga($orga, $products, $isMember = false, $userRole = null) {
        ?>
        <div id="app-orga" class="pb-20 md:pb-8 pt-4">
            <div class="bg-white px-4 py-6 shadow-sm mb-6 border-b border-gray-100 rounded-3xl mx-4 relative overflow-hidden">
                <div class="max-w-7xl mx-auto relative z-10">
                    <a href="index.php" class="text-indigo-600 font-bold text-xs uppercase flex items-center mb-4">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Retour
                    </a>
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-3xl font-black text-gray-900 leading-tight"><?= htmlspecialchars($orga['name']) ?></h1>
                            <p class="text-gray-500 text-sm mt-1"><?= htmlspecialchars($orga['address']) ?></p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php if (!$isMember): ?>
                                    <a href="index.php?page=orga&id=<?= $orga['id'] ?>&action=join" 
                                       class="px-6 py-2 bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase rounded-xl border-2 border-indigo-100 transition active:scale-95">
                                        Rejoindre
                                    </a>
                                <?php else: ?>
                                    <span class="inline-flex items-center text-[10px] font-black text-green-600 bg-green-50 px-4 py-2 rounded-xl border border-green-100 uppercase">
                                        ✓ Membre
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
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
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <span v-if="product.in_cart" class="badge-in-cart absolute top-2 right-2 bg-indigo-600 text-white text-[9px] font-black px-2 py-1 rounded-lg shadow-lg">Au panier</span>
                        </div>
                        <span class="inline-block bg-indigo-50 text-indigo-600 text-[9px] font-black uppercase px-2 py-1 rounded-lg mb-2 self-start">{{ product.categorie || 'Divers' }}</span>
                        <h5 class="text-xl font-black text-gray-900 mb-1 truncate">{{ product.name }}</h5>
                        <p class="text-indigo-600 font-black mb-4">{{ parseFloat(product.price).toFixed(2) }} €</p>
                        
                        <div class="mt-auto space-y-2">
                            <a :href="'index.php?page=product&id=' + product.id" 
                               class="block text-center py-2 bg-gray-50 text-gray-400 rounded-xl font-bold uppercase text-[9px] tracking-widest hover:text-gray-600 transition">
                                Infos
                            </a>
                            
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
                            qty: cart[p.id] ? cart[p.id].quantity : 0,
                            in_cart: cart[p.id] ? true : false
                        }));
                        return {
                            products: products,
                            filterCat: ''
                        }
                    },
                    computed: {
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