<?php
class vue_product {
    public function displayProduct($p) {
        if(!$p) { 
            echo "<div class='p-12 text-center'><h2 class='text-2xl font-black text-gray-400'>Produit non trouvé</h2></div>"; 
            return; 
        }
        $cart = $_SESSION['cart'] ?? [];
        $qty = isset($cart[$p['id']]) ? $cart[$p['id']]['quantity'] : 0;
        ?>
        <div class="pb-20 md:pb-8 pt-4" id="product-container-<?= $p['id'] ?>">
            <div class="bg-white px-4 py-6 shadow-sm mb-6 border-b border-gray-100 rounded-3xl mx-4">
                <div class="max-w-7xl mx-auto">
                    <a href="index.php?page=orga&id=<?= $p['id_buvette'] ?>" class="text-indigo-600 font-bold text-xs uppercase flex items-center mb-4">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Retour à la buvette
                    </a>
                </div>
            </div>

            <div class="max-w-4xl mx-auto px-4">
                <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100 relative overflow-hidden">
                    <div class="badge-in-cart absolute top-0 right-0 bg-indigo-600 text-white text-xs font-black px-6 py-2 rounded-bl-3xl shadow-lg <?= $qty > 0 ? '' : 'hidden' ?>">Déjà au panier</div>
                    
                    <h1 class="text-4xl font-black text-gray-900 leading-none mb-2"><?php echo htmlspecialchars($p['name']); ?></h1>
                    <div class="inline-block bg-indigo-50 text-indigo-700 text-2xl font-black px-4 py-2 rounded-2xl mb-8">
                        <?php echo number_format($p['price'], 2); ?> €
                    </div>
                    
                    <h4 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Description</h4>
                    <p class="text-gray-600 leading-relaxed mb-10 text-lg italic">
                        <?php echo nl2br(htmlspecialchars($p['description'] ?? 'Aucune description disponible.')); ?>
                    </p>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between pt-8 border-t border-gray-50 gap-6">
                        <div>
                            <span class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-1">Stock</span>
                            <?php if(($p['stock'] ?? 0) == 0): ?>
                                <span class="text-red-500 font-black uppercase">Rupture de stock</span>
                            <?php else: ?>
                                <span class="text-green-600 font-black text-xl"><?= $p['stock'] ?> unités</span>
                            <?php endif; ?>
                        </div>

                        <?php if(($p['stock'] ?? 0) > 0): ?>
                            <div class="w-full sm:w-64 cart-controls detail-controls" data-product-id="<?= $p['id'] ?>">
                                <?php $this->renderDetailControls($p['id'], $qty); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function displayProductCard($product) {
        $cart = $_SESSION['cart'] ?? [];
        $qty = isset($cart[$product['id']]) ? $cart[$product['id']]['quantity'] : 0;
        ?>
        <div class="flex flex-col h-full">
            <div class="bg-gray-50 h-32 rounded-xl mb-4 flex items-center justify-center text-gray-300 relative overflow-hidden">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <div class="badge-in-cart absolute top-2 right-2 bg-indigo-600 text-white text-[10px] font-black px-2 py-1 rounded-lg shadow-lg <?= $qty > 0 ? '' : 'hidden' ?>">Dans le panier</div>
            </div>
            <h5 class="text-xl font-black text-gray-900 mb-1 truncate"><?= htmlspecialchars($product['name']) ?></h5>
            <p class="text-indigo-600 font-black mb-4"><?= number_format($product['price'], 2) ?> €</p>
            
            <div class="mt-auto space-y-2">
                <a href="index.php?page=product&id=<?= $product['id'] ?>" 
                   class="block text-center py-2 bg-gray-50 text-gray-400 rounded-xl font-bold uppercase text-[9px] tracking-widest hover:text-gray-600 transition">
                    Plus d'infos
                </a>
                
                <div class="cart-controls card-controls" data-product-id="<?= $product['id'] ?>">
                    <?php if(($product['stock'] ?? 0) > 0): ?>
                        <?php $this->renderCardControls($product['id'], $qty); ?>
                    <?php else: ?>
                        <div class="py-3 text-center text-red-400 font-black uppercase text-[10px] tracking-widest h-12 flex items-center justify-center">Épuisé</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function renderCardControls($productId, $qty) {
        ?>
        <div class="h-12 relative">
            <?php if($qty == 0): ?>
                <button onclick="updateCart(<?= $productId ?>, 'add')" class="block w-full py-3 bg-indigo-600 text-white rounded-xl font-black uppercase text-xs tracking-widest shadow-lg shadow-indigo-100 active:scale-95 transition h-full flex items-center justify-center">
                    Ajouter
                </button>
            <?php else: ?>
                <div class="flex items-center justify-between bg-indigo-50 rounded-xl p-1 border border-indigo-100 h-full">
                    <button onclick="updateCart(<?= $productId ?>, 'remove_one')" class="w-10 h-full flex items-center justify-center bg-white rounded-lg text-indigo-600 shadow-sm active:scale-90 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                    </button>
                    <span class="qty-val font-black text-indigo-900 text-sm"><?= $qty ?></span>
                    <button onclick="updateCart(<?= $productId ?>, 'add')" class="w-10 h-full flex items-center justify-center bg-indigo-600 rounded-lg text-white shadow-md active:scale-90 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </button>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function renderDetailControls($productId, $qty) {
        ?>
        <div class="w-full">
            <?php if($qty == 0): ?>
                <button onclick="updateCart(<?= $productId ?>, 'add')" class="block w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-sm tracking-widest shadow-xl shadow-indigo-100 active:scale-95 transition-transform text-center">
                    Ajouter au panier
                </button>
            <?php else: ?>
                <div class="flex items-center justify-between bg-indigo-50 rounded-2xl p-1 border-2 border-indigo-100 w-full h-[60px]">
                    <button onclick="updateCart(<?= $productId ?>, 'remove_one')" class="w-12 h-12 flex items-center justify-center bg-white rounded-xl text-indigo-600 shadow-sm active:scale-90 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
                    </button>
                    <span class="qty-val font-black text-indigo-900 text-xl"><?= $qty ?></span>
                    <button onclick="updateCart(<?= $productId ?>, 'add')" class="w-12 h-12 flex items-center justify-center bg-indigo-600 rounded-xl text-white shadow-md active:scale-90 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </button>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function renderCartControls($productId, $qty) {
        ?>
        <div class="flex items-center bg-indigo-50 rounded-xl p-1 border border-indigo-100 h-10">
            <button onclick="updateCart(<?= $productId ?>, 'remove_one')" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg text-indigo-600 shadow-sm active:scale-90 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4"></path></svg>
            </button>
            <span class="px-3 font-black text-indigo-900 text-sm qty-val"><?= $qty ?></span>
            <button onclick="updateCart(<?= $productId ?>, 'add')" class="w-8 h-8 flex items-center justify-center bg-indigo-600 rounded-lg text-white shadow-md active:scale-90 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </button>
        </div>
        <?php
    }
}
?>