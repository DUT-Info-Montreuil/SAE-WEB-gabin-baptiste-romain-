<?php
class vue_gestion {
    public function displayGestion($orga, $products, $history, $transactions, $message = null) {
        ?>
        <div id="app-gestion" class="pb-32">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <header class="mb-6">
                    <a href="index.php?page=orga&id=<?= $orga['id'] ?>" class="text-indigo-600 font-bold text-xs uppercase flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Retour à la buvette
                    </a>
                </header>
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 leading-tight">Gestion : <?= htmlspecialchars($orga['nom']) ?></h1>
                        <p class="text-sm text-gray-500 font-medium"><?= htmlspecialchars($orga['adresse'] ?? 'Pas d\'adresse') ?></p>
                    </div>
                    <div class="bg-green-50 px-6 py-3 rounded-2xl border border-green-100 text-right">
                        <span class="block text-[10px] font-black uppercase text-green-400 tracking-widest">Trésorerie Actuelle</span>
                        <span class="text-2xl font-black text-green-600"><?= number_format($orga['solde'], 2) ?> €</span>
                    </div>
                </div>

                <?php if ($message): ?>
                    <div class="mt-6 bg-indigo-50 text-indigo-600 p-4 rounded-2xl border-2 border-indigo-100 font-bold text-center">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <div class="flex space-x-1 bg-gray-100 p-1.5 rounded-2xl mt-8 mb-8 max-w-3xl mx-auto overflow-x-auto no-scrollbar">
                    <button @click="currentTab = 'products'" :class="currentTab === 'products' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-indigo-600'" class="flex-1 py-3 px-4 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all whitespace-nowrap">Produits</button>
                    <button @click="currentTab = 'sales'" :class="currentTab === 'sales' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-indigo-600'" class="flex-1 py-3 px-4 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all whitespace-nowrap">Ventes</button>
                    <button @click="currentTab = 'stock'" :class="currentTab === 'stock' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-indigo-600'" class="flex-1 py-3 px-4 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all whitespace-nowrap">Stock</button>
                    <button @click="currentTab = 'barmen'" :class="currentTab === 'barmen' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-indigo-600'" class="flex-1 py-3 px-4 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all whitespace-nowrap">Barmen</button>
                </div>

                <div v-show="currentTab === 'products'" class="space-y-6">
                    <div class="flex justify-end mb-4 px-2">
                        <select v-model="filterCategory" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-black uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm">
                            <option value="">Toutes les catégories</option>
                            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-indigo-50 border-2 border-dashed border-indigo-200 rounded-3xl p-6 flex flex-col items-center justify-center text-center group cursor-pointer hover:bg-indigo-100 transition-colors h-full min-h-[250px]"
                             @click="openAddModal">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-indigo-600 shadow-sm mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <span class="font-black text-indigo-600 uppercase text-xs tracking-widest">Nouveau Produit</span>
                        </div>

                        <div v-for="p in filteredProducts" :key="p.id" class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col h-full relative group transition-all hover:shadow-xl hover:-translate-y-1">
                            <div class="bg-gray-50 h-32 rounded-2xl mb-4 flex items-center justify-center text-gray-300 italic text-[10px] uppercase font-black text-center p-2">Image non disponible</div>
                            <span class="inline-block bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase px-2 py-1 rounded-lg mb-2 self-start">{{ p.categorie || 'Divers' }}</span>
                            <h4 class="text-xl font-black text-gray-900 truncate">{{ p.nom }}</h4>
                            <p class="text-indigo-600 font-black text-lg mb-2">{{ parseFloat(p.prix_vente).toFixed(2) }} €</p>
                            <p class="text-gray-400 text-xs mb-6 line-clamp-2 italic h-8">{{ p.description || 'Aucune description' }}</p>
                            
                            <button @click="openEditModal(p)"
                                    class="mt-auto w-full py-3 bg-gray-900 text-white rounded-xl font-black uppercase text-[10px] tracking-[0.2em] hover:bg-indigo-600 transition-colors shadow-lg shadow-gray-100">
                                Modifier
                            </button>
                        </div>
                    </div>
                </div>

                <div v-show="currentTab === 'sales'" class="max-w-4xl mx-auto space-y-6">
                    <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center px-2">
                        <span class="bg-indigo-600 w-1.5 h-6 mr-3 rounded-full"></span>
                        Historique des Ventes (50 dernières)
                    </h3>
                    <div class="space-y-4">
                        <?php foreach ($transactions as $order): ?>
                            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="font-black text-gray-900 leading-tight"><?= htmlspecialchars($order['client_prenom'] . ' ' . $order['client_nom']) ?></h4>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight"><?= htmlspecialchars($order['client_email']) ?></p>
                                        <p class="text-[9px] text-gray-300 font-bold uppercase mt-1"><?= date('d/m/Y H:i', strtotime($order['date_heure'])) ?></p>
                                    </div>
                                    <span class="font-black text-green-600">+<?= number_format($order['montant_total'], 2) ?> €</span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($order['items'] as $item): ?>
                                        <span class="bg-gray-50 text-[10px] font-bold px-2 py-1 rounded-lg border border-gray-100 text-gray-500">
                                            <span class="text-indigo-500"><?= $item['quantite'] ?>x</span> <?= htmlspecialchars($item['nom']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div v-show="currentTab === 'stock'" class="max-w-3xl mx-auto space-y-10">
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                            <span class="bg-indigo-600 w-1.5 h-6 mr-3 rounded-full"></span>
                            État des Stocks (Inventaire)
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-50">
                                        <th class="pb-4 pl-2">Nom du Produit</th>
                                        <th class="pb-4 text-right">Quantité en Stock</th>
                                        <th class="pb-4 text-right pr-2">Alerte</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <?php foreach ($products as $p): ?>
                                        <tr class="text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                                            <td class="py-4 pl-2"><?= htmlspecialchars($p['nom']) ?></td>
                                            <td class="py-4 text-right"><span class="inline-block px-3 py-1 rounded-lg <?= $p['stock'] > 5 ? 'bg-gray-100 text-gray-600' : 'bg-red-50 text-red-600' ?>"><?= $p['stock'] ?> unités</span></td>
                                            <td class="py-4 text-right pr-2 text-xs"><?php if($p['stock'] <= 0): ?><span class="text-red-500 font-black uppercase">Rupture</span><?php elseif($p['stock'] <= 5): ?><span class="text-amber-500 font-black uppercase">Faible</span><?php else: ?><span class="text-green-500 font-black uppercase">OK</span><?php endif; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center"><span class="bg-indigo-600 w-1.5 h-6 mr-3 rounded-full"></span>Approvisionnement</h3>
                        <form method="post" action="index.php?page=gestion&id=<?= $orga['id'] ?>&action=add_stock" class="space-y-5">
                            <div>
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Produit</label>
                                <select name="product_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold text-sm">
                                    <option value="">Sélectionner un article</option>
                                    <?php foreach ($products as $p): ?>
                                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?> (Stock actuel: <?= $p['stock'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Quantité ajoutée</label><input type="number" name="quantity" min="1" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold"></div>
                                <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Prix d'achat total (€)</label><input type="number" name="cost" min="0" step="0.01" required placeholder="Coût retiré du solde" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold"></div>
                            </div>
                            <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Fournisseur</label><input type="text" name="supplier" required placeholder="Nom de l'entreprise" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold"></div>
                            <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-xs tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-transform">Valider l'approvisionnement</button>
                        </form>
                    </div>

                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                            <span class="bg-gray-200 w-1.5 h-6 mr-3 rounded-full"></span>
                            Historique des Approvisionnements
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-50">
                                        <th class="pb-4 pl-2">Fournisseur</th>
                                        <th class="pb-4">Produit</th>
                                        <th class="pb-4 text-right">Quantité</th>
                                        <th class="pb-4 text-right pr-2">Coût</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <?php foreach ($history as $h): ?>
                                        <tr class="text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors">
                                            <td class="py-4 pl-2 text-xs italic text-gray-400"><?= htmlspecialchars($h['supplier']) ?></td>
                                            <td class="py-4"><?= htmlspecialchars($h['product_name']) ?></td>
                                            <td class="py-4 text-right text-indigo-600">+<?= $h['quantity'] ?></td>
                                            <td class="py-4 text-right pr-2 text-red-400">-<?= number_format($h['cost'], 2) ?> €</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div v-show="currentTab === 'barmen'" class="max-w-3xl mx-auto space-y-10">
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center"><span class="bg-indigo-600 w-1.5 h-6 mr-3 rounded-full"></span>Promotion de Barman</h3>
                        <form method="post" action="index.php?page=gestion&id=<?= $orga['id'] ?>&action=add_barman_existing" class="flex flex-col sm:flex-row gap-3"><input type="email" name="email" placeholder="Email de l\'utilisateur" required class="flex-grow px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold"><button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg active:scale-95 transition-transform whitespace-nowrap">Ajouter le rôle</button></form>
                    </div>
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center"><span class="bg-indigo-600 w-1.5 h-6 mr-3 rounded-full"></span>Nouveau compte Barman</h3>
                        <form method="post" action="index.php?page=gestion&id=<?= $orga['id'] ?>&action=create_barman" class="space-y-5">
                            <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Email</label><input type="email" name="email" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold"></div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Nom</label><input type="text" name="nom" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold"></div>
                                <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Prénom</label><input type="text" name="prenom" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold"></div>
                            </div>
                            <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Mot de passe</label><input type="password" name="password" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold"></div>
                            <button type="submit" class="w-full py-4 bg-gray-900 text-white rounded-2xl font-black uppercase text-xs tracking-[0.2em] shadow-xl active:scale-95 transition-transform pt-4">Créer et lier</button>
                        </form>
                    </div>
                </div>
            </div>

            <div v-if="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl relative">
                    <button @click="showModal = false" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    <h3 class="text-2xl font-black text-gray-900 mb-6 uppercase tracking-tighter">{{ isEditMode ? 'Modifier' : 'Nouveau produit' }}</h3>
                    <form :action="isEditMode ? 'index.php?page=gestion&id=<?= $orga['id'] ?>&action=update_product' : 'index.php?page=gestion&id=<?= $orga['id'] ?>&action=add_product'" method="POST" class="space-y-5">
                        <input type="hidden" name="id_produit" :value="form.id">
                        <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Nom</label><input name="nom" v-model="form.nom" type="text" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 font-bold"></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Prix (€)</label><input name="prix" v-model="form.prix_vente" type="number" step="0.01" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl font-bold"></div>
                            <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Catégorie</label>
                                <input name="categorie" v-model="form.categorie" type="text" list="cat-list" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl font-bold">
                                <datalist id="cat-list"><option v-for="cat in categories" :value="cat"></option></datalist>
                            </div>
                        </div>
                        <div v-if="!isEditMode">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Stock Initial</label>
                            <input name="stock" v-model="form.stock" type="number" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl font-bold">
                        </div>
                        <div><label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Description</label><textarea name="description" v-model="form.description" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl font-bold"></textarea></div>
                        <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-xs tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-transform mt-4">{{ isEditMode ? 'Enregistrer' : 'Créer' }}</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof Vue !== 'undefined' && document.getElementById('app-gestion')) {
                    const { createApp } = Vue;
                    createApp({
                        data() {
                            const rawProducts = <?= json_encode($products) ?>;
                            return {
                                currentTab: 'products',
                                showModal: false,
                                isEditMode: false,
                                filterCategory: '',
                                products: rawProducts,
                                form: { id: '', nom: '', prix_vente: '', description: '', stock: 0, categorie: 'Divers' }
                            }
                        },
                        computed: {
                            categories() {
                                return [...new Set(this.products.map(p => p.categorie || 'Divers'))];
                            },
                            filteredProducts() {
                                if (!this.filterCategory) return this.products;
                                return this.products.filter(p => (p.categorie || 'Divers') === this.filterCategory);
                            }
                        },
                        methods: {
                            openEditModal(product) {
                                this.isEditMode = true;
                                this.form = { ...product };
                                this.showModal = true;
                            },
                            openAddModal() {
                                this.isEditMode = false;
                                this.form = { id: '', nom: '', prix_vente: '', description: '', stock: 0, categorie: 'Divers' };
                                this.showModal = true;
                            }
                        }
                    }).mount('#app-gestion');
                }
            });
        </script>
        <?php
    }

    public function displayEditForm($orga, $product) {
        header("Location: index.php?page=gestion&id=" . $orga['id']);
        exit;
    }
}
?>