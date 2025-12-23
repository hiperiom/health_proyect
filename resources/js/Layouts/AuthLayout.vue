<script>
export default {
    name: "AuthLayout",
}
</script>
<script setup>
// 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
// 2. Props & Emits (defineProps, defineEmits)
// 3. State (ref, reactive)
// 4. Computed Properties
// 5. Methods & Logic (Functions, Handlers)
// 6. Watchers
// 7. Lifecycle Hooks (onMounted, etc.)
// 8. Expose (defineExpose)
</script>
<template>
    <a-layout class="h-dvh-100 bg-primary text-white">
       
        <a-layout-header class="header">
            <slot name="header" />
        </a-layout-header>
        <a-layout-content class="content">
            <a-flex
                direction="column"
                justify="center"
                align="center"
                class="h-100 overflow-auto"
            >
                <slot name="content" />
            </a-flex>
        </a-layout-content>
        <a-layout-footer class="footer">
            ©2025 Hiperiom412 C.A.<br>Todos los derechos reservados.
        </a-layout-footer>
    </a-layout>
</template>



<style scoped>
    .header {
        background-color: transparent;
        color:inherit
    }
    .content{
        padding:0.5rem;
    }
    .footer {
        background-color: transparent;
        color:inherit;
        text-align: center;
    }
</style>