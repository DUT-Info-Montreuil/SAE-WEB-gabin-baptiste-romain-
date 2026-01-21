<?php
class vue_buy {
    public function displayCart($cart, $message = null, $success = false, $returnBuvetteId = null) {
        // Calculate global total if needed, or just iterate.
        // Cart structure: [buvetteId => [productId => item]]
        require_once __DIR__ . '/../mod_product/vue_product.php';
        $pv = new vue_product();
        $backLink = $returnBuvetteId ? "index.php?page=orga&id=" . $returnBuvetteId : "index.php";
        ?>
        <div class="max-w-xl mx-auto px-4 py-8 pb-32">
            <header class="mb-8">
                <a href="<?= $backLink ?>" class="text-indigo-600 font-bold text-xs uppercase flex items-center mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Retour
                </a>
                <h1 class="text-3xl font-black text-gray-900">Mon Panier</h1>
            </header>

            <?php if ($message): ?>
                <div class="<?= $success ? 'bg-green-50 text-green-600 border-green-100' : 'bg-red-50 text-red-600 border-red-100' ?> p-4 rounded-2xl border-2 mb-6 font-bold text-center">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div id="cart-content">
                <?php if (empty($cart)): ?>
                    <div class="bg-white rounded-3xl p-12 text-center border-2 border-dashed border-gray-100">
                        <div class="text-gray-200 mb-4 flex justify-center">
                            <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <p class="text-gray-400 font-bold uppercase tracking-wider mb-6">Votre panier est vide</p>
                        <a href="index.php" class="inline-block px-8 py-3 bg-indigo-600 text-white rounded-xl font-black uppercase text-xs tracking-widest shadow-lg shadow-indigo-100">Découvrir les buvettes</a>
                    </div>
                <?php else: ?>
                    <div class="space-y-8">
                        <?php foreach ($cart as $buvetteId => $items): 
                            if (empty($items)) continue;
                            $firstItem = reset($items);
                            $buvetteName = $firstItem['buvette_name'] ?? 'Buvette Inconnue';
                            $totalBuvette = 0;
                            foreach ($items as $item) $totalBuvette += $item['price'] * $item['quantity'];
                        ?>
                            <div class="bg-white rounded-3xl p-6 shadow-lg border border-gray-100">
                                <h2 class="text-xl font-black text-gray-900 mb-4 flex items-center gap-2">
                                    <span class="w-2 h-8 bg-indigo-600 rounded-full block"></span>
                                    <?= htmlspecialchars($buvetteName) ?>
                                </h2>

                                <div class="space-y-4 mb-6">
                                    <?php foreach ($items as $id => $item): ?>
                                        <div class="cart-item flex items-center justify-between border-b border-gray-50 pb-4 last:border-0 last:pb-0" data-product-id="<?= $id ?>">
                                            <div class="flex-grow">
                                                <h4 class="font-bold text-gray-900 leading-tight">
                                                    <?= htmlspecialchars($item['name']) ?>
                                                </h4>
                                                <p class="text-indigo-600 font-bold text-sm"><?= number_format($item['price'], 2) ?> € / unité</p>
                                            </div>
                                            <div class="flex items-center space-x-4">
                                                <div class="cart-controls cart-page-controls" data-product-id="<?= $id ?>">
                                                    <?php $pv->renderCartControls($id, $item['quantity']); ?>
                                                </div>
                                                <span class="font-black text-gray-900 w-20 text-right item-total-price"><?= number_format($item['price'] * $item['quantity'], 2) ?> €</span>
                                                <a href="index.php?page=buy&action=remove&id_product=<?= $id ?>" class="text-red-400 hover:text-red-600 p-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="bg-gray-50 rounded-2xl p-4 flex flex-col md:flex-row items-center justify-between gap-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-500 font-bold uppercase text-xs tracking-widest">Total</span>
                                        <span class="text-2xl font-black text-gray-900"><?= number_format($totalBuvette, 2) ?> €</span>
                                    </div>
                                    
                                    <a href="index.php?page=buy&action=confirm&buvette_id=<?= $buvetteId ?>" class="w-full md:w-auto px-8 py-3 bg-indigo-600 text-white rounded-xl font-black uppercase text-xs tracking-widest shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-colors text-center">
                                        Payer pour cette buvette
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
?>