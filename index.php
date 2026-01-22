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
        $hasStaffAccess = false;
        if (isset($_SESSION['user_id'])) {
            require_once 'modules/mod_home/modele_home.php';
            $homeModel = new modele_home();
            $navBalance = $homeModel->getUserBalance($_SESSION['user_id']);
            $hasStaffAccess = $homeModel->hasStaffRole($_SESSION['user_id']);
            
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
                        <?php if ($hasStaffAccess): ?>
                            <a href="index.php?page=staff" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition <?= ($page === 'staff') ? 'bg-indigo-600 text-white shadow-lg' : 'text-gray-500 hover:text-indigo-600 hover:bg-indigo-50' ?>">Staff</a>
                        <?php endif; ?>
                    </nav>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['user_id'])): ?>
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
                case 'admin': require_once 'modules/mod_admin/mod_admin.php'; (new mod_admin())->exec(); break;
                case 'staff': require_once 'modules/mod_staff/mod_staff.php'; (new mod_staff())->exec(); break;
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
                <?php if ($hasStaffAccess): ?>
                <a href="index.php?page=staff" class="-mt-10 flex flex-col items-center justify-center w-14 h-14 rounded-2xl bg-white border border-gray-100 shadow-lg transition active:scale-90 text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span class="text-[7px] font-black uppercase mt-0.5">Staff</span>
                </a>
                <?php endif; ?>
                <a href="index.php?page=profile" class="-mt-10 flex flex-col items-center justify-center w-14 h-14 rounded-2xl bg-white border border-gray-100 shadow-lg transition active:scale-90 text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="text-[7px] font-black uppercase mt-0.5">Profil</span>
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
                    const orgaContainer = document.getElementById('app-orga');
                    const currentBuvetteId = orgaContainer ? orgaContainer.dataset.buvetteId : null;

                    document.querySelectorAll('.cart-badge').forEach(b => {
                        let count = data.totalCount;
                        if (currentBuvetteId && data.buvetteId == currentBuvetteId) {
                            count = data.buvetteCount;
                        }
                        b.innerText = count;
                        b.style.setProperty('display', count > 0 ? 'block' : 'none', 'important');
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