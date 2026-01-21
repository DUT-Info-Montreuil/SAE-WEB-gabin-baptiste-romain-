<?php
class vue_auth {
    public function form_login($error = null) {
        ?>
        <div class="min-h-[80vh] flex flex-col justify-center px-4 py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <h2 class="text-center text-4xl font-black text-indigo-600 tracking-tighter uppercase mb-2">Buvettes.</h2>
                <h2 class="text-center text-2xl font-black text-gray-900 leading-tight">Bon retour parmi nous</h2>
                <p class="mt-2 text-center text-sm text-gray-500 font-bold uppercase tracking-widest">Connectez-vous à votre compte</p>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-10 px-8 shadow-2xl rounded-3xl border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50"></div>
                    
                    <?php if ($error): ?>
                        <div class="bg-red-50 text-red-600 p-4 rounded-2xl border-2 border-red-100 mb-6 font-bold text-center text-sm">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form class="space-y-6" action="index.php?page=auth&action=login" method="POST">
                        <div>
                            <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Adresse Email</label>
                            <input name="email" type="email" required 
                                   class="w-full px-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold placeholder-gray-300 transition-all">
                        </div>

                        <div>
                            <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Mot de passe</label>
                            <input name="password" type="password" required 
                                   class="w-full px-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold placeholder-gray-300 transition-all">
                        </div>

                        <div>
                            <button type="submit" 
                                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-black uppercase tracking-widest text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 active:scale-95 transition-all">
                                Se connecter
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 pt-8 border-t border-gray-50 text-center">
                        <p class="text-sm text-gray-500 font-medium">Pas encore de compte ?</p>
                        <a href="index.php?page=auth&action=register_form" class="mt-2 inline-block text-indigo-600 font-black uppercase text-xs tracking-widest hover:underline">Créer un compte</a>
                    </div>
                </div>
                
                <div class="mt-8 text-center">
                    <a href="index.php" class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.2em] hover:text-indigo-600 transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    public function form_register($error = null) {
        ?>
        <div class="min-h-[90vh] flex flex-col justify-center px-4 py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <h2 class="text-center text-2xl font-black text-gray-900 leading-tight">Rejoignez-nous</h2>
                <p class="mt-2 text-center text-sm text-gray-500 font-bold uppercase tracking-widest">Créez votre compte en quelques secondes</p>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-10 px-8 shadow-2xl rounded-3xl border border-gray-100">
                    <?php if ($error): ?>
                        <div class="bg-red-50 text-red-600 p-4 rounded-2xl border-2 border-red-100 mb-6 font-bold text-center text-sm">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form class="space-y-5" action="index.php?page=auth&action=register" method="POST">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Nom</label>
                                <input name="nom" type="text" required 
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
                            </div>
                            <div>
                                <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Prénom</label>
                                <input name="prenom" type="text" required 
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Adresse Email</label>
                            <input name="email" type="email" required 
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
                        </div>

                        <div>
                            <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Mot de passe</label>
                            <input name="password" type="password" required 
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
                        </div>

                        <div>
                            <label class="text-xs font-black uppercase tracking-widest text-gray-400 block mb-2 ml-1">Confirmation</label>
                            <input name="confirm_password" type="password" required 
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:outline-none font-bold">
                        </div>

                        <div class="pt-2">
                            <button type="submit" 
                                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-black uppercase tracking-widest text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none active:scale-95 transition-all">
                                Créer mon compte
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 pt-8 border-t border-gray-50 text-center">
                        <p class="text-sm text-gray-500 font-medium">Déjà un compte ?</p>
                        <a href="index.php?page=auth&action=login_form" class="mt-2 inline-block text-indigo-600 font-black uppercase text-xs tracking-widest hover:underline">Se connecter</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
