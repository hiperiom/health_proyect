
<script setup>
// 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
import { h,ref,computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Sidebar from '@/Components/Sidebar.vue';

// 2. Props & Emits (defineProps, defineEmits)

// 3. State (ref, reactive)

// 4. Computed Properties
// 5. Methods & Logic (Functions, Handlers)

/**/
// 6. Watchers
// 7. Lifecycle Hooks (onMounted, etc.)
// 8. Expose (defineExpose)


</script>

<template>
    <a-row class="h-dvh-100">
        <a-col class="d-flex flex-column h-100" :xs="24" :sm="8" :md="8" :lg="5" :xl="4" :xxl="3">
            <nav class="bg-primary" style="height:50px"></nav>
            <Sidebar />
        </a-col>
        <a-col class="d-flex flex-column h-100" flex="auto">
            <navbar />
            <slot name="header" />
            <slot name="content" />
            <slot name="footer" />
        </a-col>
    </a-row>
</template>
<style scoped>
  

    /* En pantallas xs y sm (<768px) el sidebar ocupa todo el ancho y queda fixed.
       Ajustamos el contenido para no quedar solapado. */
    @media (max-width: 767.98px) {
        .sidebar-fixed {
            position: fixed;
        }

        /* Si el navbar del sidebar cambia de altura, ajusta este valor */
        .sidebar-fixed + .main-fixed {
            margin-top: 64px;
        }
    }
</style>