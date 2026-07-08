<template>
    <div class="min-h-screen bg-gray-50">
        <header class="border-b bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
                <div>
                    <p class="text-sm font-semibold text-green-700">
                        Willshine Hub
                    </p>
                    <h1 class="text-lg font-bold text-gray-900">
                        Buyer Portal
                    </h1>
                </div>

                <nav class="flex gap-4 text-sm text-gray-600">
                    <a href="/buyer" class="hover:text-green-700">Dashboard</a>
                    <a href="/buyer/products" class="hover:text-green-700">Produk</a>
                    <a href="/buyer/orders" class="hover:text-green-700">Pesanan</a>
                    <a href="/buyer/rewards" class="hover:text-green-700">Reward</a>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6">
            <slot />
        </main>
    </div>
</template>