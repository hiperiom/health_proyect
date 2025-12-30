<script>
    export default {
        name: 'Header'
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h, ref } from 'vue';
    import { Link } from '@inertiajs/vue3';
    // 2. Props & Emits (defineProps, defineEmits)
    defineProps({
        size: {
            type: Number,
            default: 64,
        },
        src: {
            type: String,
            default: 'https://www.antdv.com/assets/logo.1ef800a8.svg',
        },
        className: {
            type: String,
            default: '',
        },
    });
</script>
<template>
    <header  class="d-flex justify-content-center">
        <a-avatar 
            :class="className"
            :size="size"  
            :src="src" 
            alt="logo" 
        />
    </header>
</template>
