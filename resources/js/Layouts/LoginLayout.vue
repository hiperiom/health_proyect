<script setup>

import Footer from '@/Pages/Auth/Components/Footer.vue';
import AvatarSystem from '@/Components/AvatarCompany.vue';
</script>
<template>
    <div class="h-dvh-100 bg-primary d-flex flex-column">
        <main class="flex-fill overflow-auto d-flex flex-column  justify-content-center">
            <AvatarSystem />

            <slot name="content" />
        </main>
        <footer class="bg-transparent text-center" >
            <Footer />
        
        </footer>
    </div>
</template>
<style lang="css" scoped></style>
