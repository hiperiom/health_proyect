<script>
    export default {
        name: "SideBar"
    }
</script>

<script setup>
import { computed, h, reactive } from 'vue'; 
import { router, usePage } from '@inertiajs/vue3';
import { UserOutlined, SafetyOutlined, DashboardOutlined } from '@ant-design/icons-vue';

defineProps({
    title: String,
});

const state = reactive({
    collapsed: false,
});

// 1. Definimos las llaves (keys) usando el nombre de la ruta de Laravel
const sidebarItems = reactive([
    {
        icon: () => h(DashboardOutlined),
        key: 'dashboard', // Nombre de la ruta
        label: h('div', {}, 'Dashboard'),
        onClick: () => router.get(route('dashboard')),
    },
    {
        label: h('div', {}, 'Administrador'),
        type: 'group',
        children: [
            {
                icon: () => h(UserOutlined),
                key: 'admin.users', // Nombre de la ruta
                label: h('div', {}, 'Usuarios'),
                onClick: () => router.get(route('admin.users')),
            },
            {
                icon: () => h(SafetyOutlined),
                key: 'admin.roles', // Nombre de la ruta
                label: h('div', {}, 'Roles'),
                onClick: () => router.get(route('admin.roles')),
            },
        ]
    },
    { label: h('div', {}, 'Paciente'), type: 'group' },
    { label: h('div', {}, 'Doctor'), type: 'group' },
    { label: h('div', {}, 'Personal de Enfermería'), type: 'group' },
    { label: h('div', {}, 'Gerente'), type: 'group' },
    { label: h('div', {}, 'Atención al Paciente'), type: 'group' },
]);

// 2. Sincronización automática con la ruta activa
const selectedKeys = computed(() => {
    // route().current() devuelve el nombre de la ruta activa (ej: 'admin.roles')
    const currentRoute = route().current();
    return currentRoute ? [currentRoute] : [];
});

// Opcional: Si usaras SubMenús (no grupos), aquí los abrirías
const openKeys = computed(() => {
    const current = route().current();
    if (current && current.includes('admin.')) return ['admin_group'];
    return [];
});

</script>

<template>
    <a-menu
        :selectedKeys="selectedKeys"
        :inline-collapsed="state.collapsed"
        :items="sidebarItems"
        mode="inline"
        theme="light"
        class="border-0 bg-transparent custom-menu"
    />    
</template>

<style lang="css" >

.ant-menu-item-selected {
    background-color:  rgba(0, 0, 0, 0.06) !important;
}

</style>