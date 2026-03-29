<template>
    <div class="min-h-screen bg-[#f8fafc] font-sans text-slate-900">
        <!-- Header -->
        <header
            class="sticky top-0 z-50 border-b border-slate-200 bg-white/80 backdrop-blur-md"
        >
            <div class="px-6 lg:px-10">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center gap-3">
                        <i
                            class="pi pi-check-square text-2xl text-indigo-600"
                        ></i>
                        <h1
                            class="cursor-pointer text-xl font-extrabold tracking-tight text-slate-900 transition-colors hover:text-indigo-600"
                            @click="$router.push('/')"
                        >
                            Task Manager
                        </h1>
                    </div>

                    <!-- Navegação -->
                    <nav>
                        <router-link
                            to="/"
                            class="group flex items-center rounded-lg px-4 py-2 text-sm font-semibold transition-all"
                            :class="[
                                $route.path === '/'
                                    ? 'bg-indigo-50 text-indigo-700'
                                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900',
                            ]"
                        >
                            <i
                                class="pi pi-th-large mr-2 transition-transform group-hover:scale-110"
                            ></i>
                            Home
                        </router-link>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Conteúdo Principal -->
        <main class="px-6 py-8 lg:px-10">
            <router-view v-slot="{ Component }">
                <transition name="fade" mode="out-in">
                    <component :is="Component" />
                </transition>
            </router-view>
        </main>

        <!-- Footer -->
        <footer class="mt-auto border-t border-slate-200 bg-white">
            <div class="px-6 py-6 lg:px-10">
                <p class="text-center text-sm font-medium text-slate-400">
                    Task Manager - Teste Técnico Full Stack
                </p>
            </div>
        </footer>
    </div>
</template>

<style>
/* Transição de fade entre páginas */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
