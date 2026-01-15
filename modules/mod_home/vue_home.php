<?php
class vue_home {
    public function displayHome($orgas, $search, $userBalance, $topOrgas, $favIds = [], $currentView = 'all') {
        ?>
        <div id="app" class="pb-20 md:pb-0">
            <?php if (!empty($topOrgas) && $currentView === 'all'): ?>
            <section class="mt-4 px-4 overflow-hidden">
                <div @touchstart="touchStart" 
                     @touchend="touchEnd"
                     class="relative rounded-2xl bg-indigo-900 h-64 sm:h-80 shadow-xl group">
                    <div @click="prev" class="absolute left-0 top-0 bottom-0 w-12 z-20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </div>
                    <div @click="next" class="absolute right-0 top-0 bottom-0 w-12 z-20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>

                    <transition-group :name="animName" tag="div" class="relative h-full">
                        <div v-for="(orga, index) in topOrgas" 
                             :key="orga.id"
                             v-show="currentIndex === index"
                             class="absolute inset-0 flex items-center px-6 sm:px-12 text-white">
                            <div class="flex-grow">
                                <span class="bg-indigo-500 text-[10px] font-bold px-2 py-0.5 rounded-full mb-2 inline-block">Top {{ index + 1 }}</span>
                                <h3 class="text-2xl sm:text-4xl font-black leading-tight mb-2">{{ orga.name }}</h3>
                                <p class="text-indigo-200 text-xs sm:text-sm mb-4 truncate">{{ orga.address }}</p>
                                <a :href="'index.php?page=orga&id=' + orga.id" 
                                   class="bg-white text-indigo-900 px-6 py-2 rounded-lg text-sm font-bold inline-block shadow-lg">
                                    Visiter
                                </a>
                            </div>
                        </div>
                    </transition-group>
                </div>
            </section>
            <?php endif; ?>

            <section id="search-section" class="mt-8 px-4 max-w-lg mx-auto">
                <form method="get" action="index.php" class="relative mb-8">
                    <input type="text" name="search" placeholder="Rechercher une buvette..." value="<?= htmlspecialchars($search) ?>"
                           class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none shadow-sm transition-all text-sm font-bold">
                    <svg class="w-5 h-5 absolute left-4 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </form>
            </section>

            <section id="all-orgas" class="mt-8 px-4 max-w-7xl mx-auto pb-8">
                <div class="flex items-center justify-between mb-6 px-2">
                    <h3 class="text-xl font-black text-gray-900 flex items-center">
                        <span class="bg-indigo-600 w-1.5 h-6 mr-3 rounded-full"></span>
                        <?= $currentView === 'favorites' ? 'Mes Buvettes Favorites' : 'Toutes les buvettes' ?>
                    </h3>
                    <?php if($currentView === 'favorites'): ?>
                        <a href="index.php" class="text-xs font-bold text-indigo-600 uppercase">Voir tout</a>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <?php foreach ($orgas as $orga): ?>
                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col h-full relative group">
                            <?php if(isset($_SESSION['user_id'])): 
                                $isFav = in_array($orga['id'], $favIds);
                            ?>
                                <button onclick="toggleFav(event, <?= $orga['id'] ?>)" 
                                   class="fav-btn absolute top-4 right-4 z-10 p-2 rounded-full transition-all <?= $isFav ? 'text-red-500 bg-red-50' : 'text-gray-300 bg-gray-50 hover:text-red-400' ?>">
                                    <svg class="w-5 h-5 heart-svg" fill="<?= $isFav ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                </button>
                            <?php endif; ?>

                            <h4 class="text-lg font-bold text-gray-900 truncate pr-8"><?= htmlspecialchars($orga['name']) ?></h4>
                            <p class="text-gray-500 text-xs mb-4 truncate"><?= htmlspecialchars($orga['address']) ?></p>
                            <a href="index.php?page=orga&id=<?= $orga['id'] ?>" 
                               class="mt-auto block text-center py-3 bg-gray-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition text-sm border border-indigo-50">
                                Voir les produits
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (empty($orgas)): ?>
                    <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-100">
                        <p class="text-xl font-bold text-gray-400"><?= $currentView === 'favorites' ? 'Vous n\'avez pas encore de favoris' : 'Aucune buvette trouvée' ?></p>
                        <a href="index.php" class="text-indigo-600 hover:underline mt-2 inline-block font-bold">Réinitialiser</a>
                    </div>
                <?php endif; ?>
            </section>
        </div>

        <style>
            .slide-next-enter-active, .slide-next-leave-active,
            .slide-prev-enter-active, .slide-prev-leave-active { transition: all 0.5s ease; }
            .slide-next-enter-from { transform: translateX(100%); opacity: 0; }
            .slide-next-leave-to { transform: translateX(-100%); opacity: 0; }
            .slide-prev-enter-from { transform: translateX(-100%); opacity: 0; }
            .slide-prev-leave-to { transform: translateX(100%); opacity: 0; }
        </style>

        <script>
            async function toggleFav(event, orgaId) {
                event.preventDefault();
                const btn = event.currentTarget;
                const svg = btn.querySelector('.heart-svg');
                
                try {
                    const res = await fetch(`index.php?page=home&action=toggle_favorite&id_orga=${orgaId}&ajax=1`);
                    const data = await res.json();
                    if(data.success) {
                        if(btn.classList.contains('text-red-500')) {
                            btn.classList.remove('text-red-500', 'bg-red-50');
                            btn.classList.add('text-gray-300', 'bg-gray-50');
                            svg.setAttribute('fill', 'none');
                        } else {
                            btn.classList.remove('text-gray-300', 'bg-gray-50');
                            btn.classList.add('text-red-500', 'bg-red-50');
                            svg.setAttribute('fill', 'currentColor');
                        }
                    }
                } catch(e) { console.error(e); }
            }

            document.addEventListener('DOMContentLoaded', () => {
                if (typeof Vue !== 'undefined' && document.getElementById('app')) {
                    const { createApp } = Vue;
                    createApp({
                        data() {
                            return {
                                topOrgas: <?= json_encode($topOrgas) ?>,
                                currentIndex: 0,
                                animName: 'slide-next',
                                timer: null,
                                touchStartX: 0
                            }
                        },
                        mounted() {
                            this.startAutoPlay();
                        },
                        methods: {
                            startAutoPlay() { if(this.topOrgas && this.topOrgas.length > 1) this.timer = setInterval(this.next, 5000); },
                            next() {
                                this.animName = 'slide-next';
                                this.currentIndex = (this.currentIndex + 1) % this.topOrgas.length;
                            },
                            prev() {
                                this.animName = 'slide-prev';
                                this.currentIndex = (this.currentIndex - 1 + this.topOrgas.length) % this.topOrgas.length;
                            },
                            touchStart(e) {
                                this.touchStartX = e.touches[0].clientX;
                            },
                            touchEnd(e) {
                                const touchEndX = e.changedTouches[0].clientX;
                                const diff = this.touchStartX - touchEndX;
                                if (Math.abs(diff) > 50) { 
                                    if (diff > 0) this.next();
                                    else this.prev();
                                }
                            }
                        }
                    }).mount('#app');
                }
            });
        </script>
        <?php
    }
}
?>
