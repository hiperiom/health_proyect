<script setup>
    import { defineProps } from 'vue';
    import AvatarSystem from '@/Pages/Auth/Components/AvatarCompany.vue';
    import Footer from '@/Pages/Auth/Components/Footer.vue';

    const props = defineProps({
       
    });


</script>
<template>
    <div class="h-dvh-100 d-flex flex-column">
        
        <main class="flex-fill overflow-auto d-flex flex-column  justify-content-start" >
            <header class="d-flex justify-content-center pt-3">
                <AvatarSystem />
            </header>
            <slot name="content" />
            <footer class="mt-auto bg-transparent text-center" >
                <Footer />
            </footer>
        </main>
        
    </div>
</template>
<style lang="css" scoped></style>
