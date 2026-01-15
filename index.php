<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <title>Buvettes App</title>
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans">
    <div class="min-h-screen flex flex-col">
        <?php
        $page = $_GET['page'] ?? 'home';
        $currentView = $_GET['view'] ?? 'all';

        $navBalance = 0;
        $cartCount = 0;
        if (isset($_SESSION['user_id'])) {
            require_once 'modules/mod_home/modele_home.php';
            $homeModel = new modele_home();
            $navBalance = $homeModel->getUserBalance($_SESSION['user_id']);
            
            if(isset($_SESSION['cart'])) {
                foreach($_SESSION['cart'] as $item) $cartCount += $item['quantity'];
            }
        }
        $footerBalance = $navBalance;
        ?>

        <?php if($page !== 'barman'): ?>
        <header class="hidden md:block bg-white shadow-sm py-4 px-6 sticky top-0 z-40 border-b border-gray-100">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-8">
                    <a href="index.php" class="text-xl font-black text-indigo-600 tracking-tighter uppercase">Buvettes.</a>
                    <nav class="flex items-center space-x-2">
                        <a href="index.php" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition <?= ($page === 'home' && $currentView === 'all') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-500 hover:text-indigo-600 hover:bg-indigo-50' ?>">Accueil</a>
                        <a href="index.php?view=favorites" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition <?= ($currentView === 'favorites') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-500 hover:text-indigo-600 hover:bg-indigo-50' ?>">Favoris</a>
                    </nav>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="index.php?page=buy" class="flex items-center text-gray-500 hover:text-indigo-600 relative p-2 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="cart-badge absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full ring-2 ring-white" style="<?= $cartCount > 0 ? '' : 'display:none;' ?>"><?= $cartCount ?></span>
                        </a>
                        <a href="index.php?page=solde" class="flex items-center bg-indigo-50 px-4 py-2 rounded-xl">
                            <span class="text-xs font-black text-indigo-400 uppercase mr-2">Solde</span>
                            <span class="text-indigo-700 font-black"><?= number_format($navBalance, 2) ?> €</span>
                        </a>
                        <a href="index.php?page=profile" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition shadow-lg">Profil</a>
                    <?php else: ?>
                        <a href="index.php?page=auth&action=login_form" class="px-6 py-2 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg">Connexion</a>
                    <?php endif; ?>
                </div>
            </div>
        </header>
        <?php endif; ?>

        <main class="flex-grow">
            <?php
            switch ($page) {
                case 'auth': require_once 'modules/mod_auth/mod_auth.php'; (new mod_auth())->exec(); break;
                case 'profile': require_once 'modules/mod_profile/mod_profile.php'; (new mod_profile())->exec(); break;
                case 'gestion': require_once 'modules/mod_gestion/mod_gestion.php'; (new mod_gestion())->exec(); break;
                case 'product': require_once 'modules/mod_product/mod_product.php'; (new mod_product())->exec(); break;
                case 'orga': require_once 'modules/mod_orga/mod_orga.php'; (new mod_orga())->exec(); break;
                case 'barman': require_once 'modules/mod_barmen/mod_barman.php'; (new mod_barman())->exec(); break;
                case 'solde': require_once 'modules/mod_solde/mod_solde.php'; (new mod_solde())->exec(); break;
                case 'buy': require_once 'modules/mod_buy/mod_buy.php'; (new mod_buy())->exec(); break;
                case 'home': default: require_once 'modules/mod_home/mod_home.php'; (new mod_home())->exec(); break;
            }
            ?>
        </main>
        
        <?php if($page !== 'barman'): ?>
        <nav class="md:hidden bg-white border-t border-gray-100 fixed bottom-0 w-full z-50 px-2 flex justify-around items-center h-16 shadow-[0_-4px_30px_rgba(0,0,0,0.1)]">
            <a href="index.php" class="-mt-10 flex flex-col items-center justify-center w-14 h-14 rounded-2xl bg-white border border-gray-100 shadow-lg transition active:scale-90 text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-[7px] font-black uppercase mt-0.5">Accueil</span>
            </a>

            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="index.php?page=profile" class="-mt-10 flex flex-col items-center justify-center w-14 h-14 rounded-2xl bg-white border border-gray-100 shadow-lg transition active:scale-90 text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="text-[7px] font-black uppercase mt-0.5">Profil</span>
                </a>
                <a href="index.php?page=solde" class="-mt-10 flex flex-col items-center justify-center bg-indigo-600 w-16 h-16 rounded-2xl text-white shadow-2xl shadow-indigo-300 border-2 border-white active:scale-95 transition-transform">
                    <span class="text-[7px] font-black uppercase opacity-80">Solde</span>
                    <span class="text-xs font-black tracking-tighter"><?= number_format($footerBalance, 0) ?>€</span>
                    <div class="mt-0.5 bg-white/20 p-0.5 rounded-full"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg></div>
                </a>
                <a href="index.php?page=buy" class="-mt-10 flex flex-col items-center justify-center w-14 h-14 rounded-2xl bg-white border border-gray-100 shadow-lg transition active:scale-90 text-gray-400 relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="text-[7px] font-black uppercase mt-0.5">Panier</span>
                    <span class="cart-badge absolute -top-1 -right-1 bg-red-500 text-white text-[8px] font-black px-1.5 py-0.5 rounded-full ring-2 ring-white" style="<?= $cartCount > 0 ? '' : 'display:none;' ?>"><?= $cartCount ?></span>
                </a>
                <a href="index.php?view=favorites" class="-mt-10 flex flex-col items-center justify-center w-14 h-14 rounded-2xl bg-white border border-gray-100 shadow-lg transition active:scale-90 text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    <span class="text-[7px] font-black uppercase mt-0.5">Favoris</span>
                </a>
            <?php else: ?>
                <a href="index.php?page=auth&action=login_form" class="-mt-10 flex flex-col items-center justify-center w-16 h-16 rounded-2xl bg-indigo-600 shadow-xl transition active:scale-90 text-white border-2 border-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    <span class="text-[7px] font-black uppercase mt-0.5">Login</span>
                </a>
            <?php endif; ?>
        </nav>
        <?php endif; ?>
    </div>
    <script>
        async function updateCart(productId, action) {
            try {
                const res = await fetch(`index.php?page=buy&action=${action}&id_product=${productId}&ajax=1`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if(data.success) {
                    document.querySelectorAll('.cart-badge').forEach(b => {
                        b.innerText = data.totalCount;
                        b.style.setProperty('display', data.totalCount > 0 ? 'block' : 'none', 'important');
                    });
                    document.querySelectorAll(`.cart-controls[data-product-id="${productId}"]`).forEach(container => {
                        if(container.classList.contains('card-controls')) container.innerHTML = data.htmlCard;
                        else if(container.classList.contains('detail-controls')) container.innerHTML = data.htmlDetail;
                        else if(container.classList.contains('cart-page-controls')) container.innerHTML = data.htmlCart;
                        const cardContainer = container.closest('.flex-col') || container.closest('.max-w-4xl');
                        const badge = cardContainer ? cardContainer.querySelector('.badge-in-cart') : null;
                        if(badge) badge.style.setProperty('display', data.qty > 0 ? 'block' : 'none', 'important');
                    });
                    const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
                    if(cartItem) {
                        if(data.qty <= 0) {
                            cartItem.remove();
                            if(data.totalCount <= 0) location.reload();
                        } else {
                            const itemPrice = cartItem.querySelector('.item-total-price');
                            if(itemPrice) itemPrice.innerText = data.itemPrice + ' €';
                        }
                    }
                    const totalPrice = document.getElementById('cart-total-price');
                    if(totalPrice) totalPrice.innerText = data.totalPrice;
                }
            } catch (e) { console.error("Erreur AJAX Panier:", e); }
        }
    </script>
</body>
</html>
<?php ob_end_flush(); ?>
