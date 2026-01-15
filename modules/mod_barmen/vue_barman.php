<?php
class vue_barman {
    public function menu() {}

    public function afficher_interface($products, $searchResults, $searchQuery, $pendingOrders, $msgSuccess = null, $msgError = null) {
        $client = $_SESSION['selected_client'] ?? null;
        $cart = $_SESSION['cart_barman'] ?? [];
        $total = 0;
        foreach($cart as $item) $total += $item['price'] * $item['qty'];
        $buvetteId = $_GET['id'] ?? '';
        ?>
        
        <div id="app-barman" class="pb-12">
            <div class="max-w-7xl mx-auto px-4 py-6">
                <header class="mb-6">
                    <a href="index.php?page=profile" class="text-indigo-600 font-bold text-xs uppercase flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Retour au Profil
                    </a>
                </header>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-8 text-center md:text-left">
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Espace Barman</h1>
                        <p class="text-sm text-gray-500 font-medium italic">Gestion des commandes</p>
                    </div>
                    <?php if($client): ?>
                        <div class="flex items-center gap-4 bg-indigo-50 p-3 pr-6 rounded-2xl border border-indigo-100 mx-auto md:mx-0">
                            <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg text-left">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <div class="text-left">
                                <span class="block text-[10px] font-black uppercase text-indigo-400 leading-none">Client</span>
                                <span class="font-black text-indigo-900 text-sm block truncate max-w-[150px]"><?= htmlspecialchars($client['email']) ?></span>
                                <span class="block text-xs font-bold text-indigo-600"><?= number_format($client['balance'], 2) ?> € dispos</span>
                            </div>
                            <form action="index.php?page=barman&id=<?= $buvetteId ?>&action=reset_client" method="POST">
                                <button type="submit" class="p-2 text-red-400 hover:text-red-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex space-x-1 bg-gray-100 p-1.5 rounded-2xl mb-8 max-w-lg mx-auto overflow-x-auto no-scrollbar">
                    <button @click="currentTab = 'client'" :class="currentTab === 'client' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-indigo-600'" class="flex-1 py-3 px-4 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all whitespace-nowrap">1. Client</button>
                    <button @click="currentTab = 'products'" :class="currentTab === 'products' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-indigo-600'" class="flex-1 py-3 px-4 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all whitespace-nowrap">2. Caisse</button>
                    <button @click="currentTab = 'collect'" :class="currentTab === 'collect' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-indigo-600'" class="flex-1 py-3 px-4 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all whitespace-nowrap">3. Click & Collect</button>
                </div>

                <div v-show="currentTab === 'client'" class="max-w-2xl mx-auto space-y-6">
                    <form action="index.php" method="GET" class="relative">
                        <input type="hidden" name="page" value="barman">
                        <input type="hidden" name="id" value="<?= $buvetteId ?>">
                        <input type="text" name="q" id="search-input" placeholder="Email du client..." value="<?= htmlspecialchars($searchQuery) ?>" class="w-full pl-14 pr-4 py-5 bg-white border-2 border-gray-100 rounded-3xl focus:border-indigo-500 focus:outline-none shadow-xl transition-all font-bold text-lg">
                        <svg class="w-6 h-6 absolute left-5 top-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </form>
                    <div class="grid grid-cols-1 gap-3">
                        <?php foreach($searchResults as $res): ?>
                            <form action="index.php?page=barman&id=<?= $buvetteId ?>&action=select_client" method="POST">
                                <input type="hidden" name="client_id" value="<?= $res['id'] ?>">
                                <button type="submit" class="w-full flex items-center justify-between bg-white p-5 rounded-2xl border border-gray-100 hover:border-indigo-500 transition-all group text-left shadow-sm">
                                    <div><p class="font-black text-gray-900 leading-tight"><?= htmlspecialchars($res['email']) ?></p><p class="text-xs text-gray-400 font-bold"><?= number_format($res['balance'], 2) ?> €</p></div>
                                    <svg class="w-6 h-6 text-indigo-200 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div v-show="currentTab === 'products'" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <?php foreach($products as $p): ?>
                            <div onclick="barmanUpdateCart(<?= $p['id'] ?>, 'add_to_cart', {product_name: '<?= addslashes($p['name']) ?>', product_price: <?= $p['price'] ?>})" 
                                 class="cursor-pointer w-full bg-white p-4 rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl active:scale-95 transition-all text-center flex flex-col items-center h-full">
                                <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 mb-3"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg></div>
                                <span class="text-xs font-black text-gray-900 leading-tight mb-1 line-clamp-2"><?= htmlspecialchars($p['name']) ?></span>
                                <span class="text-sm font-black text-indigo-600"><?= number_format($p['price'], 2) ?> €</span>
                                <span class="mt-2 text-[9px] font-bold uppercase tracking-widest <?= $p['stock'] > 5 ? 'text-gray-400' : 'text-red-500' ?>">Stock: <?= $p['stock'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="lg:col-span-1">
                        <div id="barman-cart-container" class="bg-indigo-900 rounded-3xl p-6 text-white shadow-2xl sticky top-24">
                            <?php $this->render_cart_content($cart, $total, $buvetteId, $client); ?>
                        </div>
                    </div>
                </div>

                <div v-show="currentTab === 'collect'" class="max-w-4xl mx-auto space-y-6">
                    <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center px-2">
                        <span class="bg-indigo-600 w-1.5 h-6 mr-3 rounded-full"></span>
                        Commandes à préparer
                    </h3>
                    <div class="space-y-4">
                        <?php foreach ($pendingOrders as $order): ?>
                            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden group">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="font-black text-gray-900 leading-tight"><?= htmlspecialchars($order['prenom'] . ' ' . $order['nom']) ?></h4>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight"><?= htmlspecialchars($order['email']) ?></p>
                                        <p class="text-[9px] text-indigo-400 font-black uppercase mt-1"><?= date('H:i', strtotime($order['date_heure'])) ?></p>
                                    </div>
                                    <a href="index.php?page=barman&id=<?= $buvetteId ?>&action=prepare_order&order_id=<?= $order['id'] ?>" 
                                       class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-indigo-100 active:scale-95 transition-transform">
                                        Terminer
                                    </a>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($order['items'] as $item): ?>
                                        <span class="bg-gray-50 text-[10px] font-bold px-3 py-1.5 rounded-lg border border-gray-100 text-gray-600">
                                            <span class="text-indigo-600 font-black"><?= $item['quantite'] ?>x</span> <?= htmlspecialchars($item['nom']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($pendingOrders)): ?>
                            <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-gray-100">
                                <p class="text-gray-400 font-bold uppercase tracking-wider text-xs italic">Aucune commande en attente</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
            window.barmanUpdateCart = async function(productId, action, extraData = {}) {
                const formData = new FormData();
                formData.append('product_id', productId);
                for (const key in extraData) formData.append(key, extraData[key]);
                try {
                    const res = await fetch(`index.php?page=barman&id=<?= $buvetteId ?>&action=${action}&ajax=1`, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();
                    if(data.success) {
                        document.getElementById('barman-cart-container').innerHTML = data.html;
                    }
                } catch(e) { console.error(e); }
            }

            if (typeof Vue !== 'undefined') {
                const { createApp } = Vue;
                createApp({ data() { return { currentTab: '<?= $client ? "products" : "client" ?>' } } }).mount('#app-barman');
            }
        </script>
        <?php
    }

    public function render_cart_content($cart, $total, $buvetteId, $client = null) {
        $client = $client ?? ($_SESSION['selected_client'] ?? null);
        ?>
        <h3 class="text-lg font-black uppercase tracking-tighter mb-6 flex items-center text-white">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Panier
        </h3>
        <?php if(empty($cart)): ?>
            <p class="text-indigo-300 text-xs italic py-10 text-center border-2 border-dashed border-indigo-800 rounded-2xl text-white">Vide</p>
        <?php else: ?>
            <div class="space-y-3 mb-8 max-h-[300px] overflow-y-auto no-scrollbar">
                <?php foreach($cart as $item): ?>
                    <div class="flex items-center justify-between bg-indigo-800/50 p-3 rounded-xl border border-indigo-700/50 text-white">
                        <div class="flex-grow pr-2 text-left">
                            <p class="text-xs font-black leading-none truncate max-w-[120px] mb-2 text-white"><?= htmlspecialchars($item['name']) ?></p>
                            <div class="flex items-center gap-2">
                                <button onclick="barmanUpdateCart(<?= $item['id'] ?>, 'update_qty', {op: 'minus'})" class="w-6 h-6 bg-white/10 rounded flex items-center justify-center text-[10px] font-black hover:bg-white/20 text-white border-0">-</button>
                                <span class="text-xs font-black w-4 text-center text-white"><?= $item['qty'] ?></span>
                                <button onclick="barmanUpdateCart(<?= $item['id'] ?>, 'update_qty', {op: 'plus'})" class="w-6 h-6 bg-white/10 rounded flex items-center justify-center text-[10px] font-black hover:bg-white/20 text-white border-0">+</button>
                                <button onclick="barmanUpdateCart(<?= $item['id'] ?>, 'remove_all')" class="ml-2 text-red-400 hover:text-red-300 p-1 transition flex items-center justify-center border-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                        <span class="text-xs font-black whitespace-nowrap text-white"><?= number_format($item['price'] * $item['qty'], 2) ?> €</span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="border-t border-indigo-800 pt-6 mb-8 text-right text-white">
                <span class="block text-[10px] font-black uppercase text-indigo-400 tracking-widest text-white">Total</span>
                <span class="text-3xl font-black text-white"><?= number_format($total, 2) ?> €</span>
            </div>
            <form action="index.php?page=barman&id=<?= $buvetteId ?>&action=validate_purchase" method="POST">
                <button type="submit" <?= !$client ? 'disabled' : '' ?> class="w-full py-4 bg-white text-indigo-900 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl active:scale-95 disabled:opacity-30 border-0">Valider la vente</button>
            </form>
        <?php endif; ?>
        <?php
    }
}
?>
