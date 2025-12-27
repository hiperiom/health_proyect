<script>
    export default {
        name: "Error",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { computed } from 'vue';
    import { Link, router } from '@inertiajs/vue3';
    import AuthLayout from '@/Layouts/AuthLayout.vue';
    import AvatarCompany from '@/Components/AvatarCompany.vue';

    // 2. Props & Emits (defineProps, defineEmits)
    const props = defineProps({ status: Number });


    // 3. State (ref, reactive)
    // 4. Computed Properties
    const title = computed(() => {
    return {
        404: '404: Página no encontrada',
        500: '500: Error del servidor',
        403: '403: Acceso prohibido',
        401: '401: No autorizado',
    }[props.status] || 'Error Inesperado';
    });

    const description = computed(() => {
    return {
        404: 'Lo sentimos, la página que buscas no existe.',
        500: 'Vaya, algo salió mal en nuestros servidores.',
        403: 'No tienes permiso para acceder a esta página.',
    }[props.status] || 'Ha ocurrido un error en la aplicación.';
    });
    // 5. Methods & Logic (Functions, Handlers)
    const handleLogin = () => {
        const url = route('login'); 
        router.visit(url);
    };
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)



</script>

<template>
    <AuthLayout>
        <template #content>
            <a-result :status="props.status" :title=" title " :sub-title="description">
                <template #extra>
                    <a-button @click="handleLogin" type="primary">Volver al inicio</a-button>
                </template>
            </a-result>
        </template>
    </AuthLayout>
    
</template>
<style>
    .ant-result .ant-result-subtitle,
    .ant-result .ant-result-title {
        color: var(--bs-white);
    }
</style>
