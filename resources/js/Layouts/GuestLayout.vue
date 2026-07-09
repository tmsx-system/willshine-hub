<script setup>
// Guest layout for Landing, Login pages
</script>

<template>
    <div class="min-h-screen bg-gradient-mesh">
        <!-- Decorative blobs -->
        <div class="fixed top-0 right-0 w-96 h-96 bg-pink-200 rounded-full blur-3xl opacity-30 pointer-events-none -translate-y-1/2 translate-x-1/4" />
        <div class="fixed bottom-0 left-0 w-80 h-80 bg-pink-300 rounded-full blur-3xl opacity-20 pointer-events-none translate-y-1/2 -translate-x-1/4" />
        <div class="fixed top-1/2 left-1/3 w-64 h-64 bg-fuchsia-200 rounded-full blur-3xl opacity-15 pointer-events-none" />

        <slot />
    </div>
</template>
