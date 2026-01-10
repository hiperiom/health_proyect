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
    const page = usePage();


    const optionsList = [];
    page.props.auth.user.roles.forEach(role => {
        if (role.name === 'Administrador'){
            optionsList.push({
                icon: () => h(UserOutlined),
                key: 'admin_group',      
                label: h('div', collapsed ? role.name:''),
                type: 'group',
                children: [
                    {
                        icon: () => h(DashboardOutlined),
                        key: 'dashboard',
                        label: h('div', 'Inicio'),
                        onClick: () => router.get(route('dashboard')),
                    },
                    {
                        icon: () => h(UserOutlined),
                        key: 'users.index',
                        label: h('div', 'Usuariosssss1111'),
                        onClick: () => router.get(route('users.index')),
                    },
                    {
                        icon: () => h(SafetyOutlined),
                        key: 'roles.index',
                        label: h('div', 'Roles'),
                        onClick: () => router.get(route('roles.index')),
                    },
                    {
                        icon: () => h(SafetyOutlined),
                        key: 'medic-especialities.index',
                        label: h('div', 'Especialidades'),
                        onClick: () => router.get(route('medic-especialities.index')),
                    },
                ]
            })


        }
        if (role.name === 'Paciente'){
            optionsList.push({
                icon: () => h(UserOutlined),
                key: 'patient_group',      
                label: h('div', role.name),
                type: 'group',
                children: []
            })


        }
    });




    const sidebarItems = reactive(optionsList
        
        /* ...(is_admim && 
        { label: h('div', {}, 'Paciente'), type: 'group' },
        { label: h('div', {}, 'Doctor'), type: 'group' },
        { label: h('div', {}, 'Personal de Enfermería'), type: 'group' },
        { label: h('div', {}, 'Gerente'), type: 'group' },
        { label: h('div', {}, 'Atención al Paciente'), type: 'group' }, */
    );

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
