
<script setup>
// 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
import { h,ref,computed, onMounted } from 'vue';
import Navbar from '@/Components/Navbar.vue';
import SideBar from '@/Components/SideBar.vue';
import AvatarCompany from '@/Components/AvatarCompany.vue';

// 2. Props & Emits (defineProps, defineEmits)
// 3. State (ref, reactive)
// 4. Computed Properties
// 5. Methods & Logic (Functions, Handlers)
// 6. Watchers
// 7. Lifecycle Hooks (onMounted, etc.)
// 8. Expose (defineExpose)
</script>

<template>
    <div class="h-dvh-100 d-flex flex-column  overflow-auto">
        <div class="d-flex flex-column flex-fill overflow-auto">
            <a-row align="middle" class="mx-3 mt-2 glass-container rounded-3">
                <a-col flex="none">
                    <AvatarCompany :size="40" className="ms-2" />
                </a-col>
                <a-col flex="auto">
                    <div >
                        <navbar />
                    </div>
                </a-col>
            </a-row>
            <a-row span="24" class="h-100 overflow-auto">
                <a-col class="d-flex flex-column h-100 overflow-auto py-3 ps-3 pe-1" :xs="24" :sm="8" :md="8" :lg="5" :xl="4" :xxl="3">
                    <div class="glass-container rounded-3 h-100 flex-fill overflow-auto">
                        <SideBar />       
                    </div>
                </a-col>
                <a-col flex="auto" class="d-flex flex-column h-100 overflow-auto py-3 pe-3 ps-3">
                    <a-row class="mb-3">
                        <a-col class="glass-container rounded-3 px-3 py-2" flex="auto">
                            <slot name="header" />
                        </a-col>
                    </a-row>
                    <a-row span="24" class="h-100">
                        <a-col class="glass-container rounded-3 px-3 py-2" flex="auto">
                            <slot name="content" />
                            <slot name="footer" />
                        </a-col>
                    </a-row>
                </a-col>
            </a-row>
        </div>
    </div>

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