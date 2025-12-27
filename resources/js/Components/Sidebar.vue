<script>
    export default {
        name: "Sidebar"
    }
</script>
<script setup>
// 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
import { computed, h, reactive } from 'vue'; 
import { router, usePage } from '@inertiajs/vue3';
import { UserOutlined, SafetyOutlined,  DashboardOutlined } from '@ant-design/icons-vue';

// 2. Props & Emits (defineProps, defineEmits)
defineProps({
    title: String,
});
// 3. State (ref, reactive)
const state = reactive({
    collapsed: false,
    preOpenKeys: []
});
const sidebarItems = reactive([
    {
        icon: () => h(DashboardOutlined),
        key: 'dashboard',
        label:  h('div',{ },'Dashboard' ),
        onClick: () => {
            router.get(route('dashboard'));
        },
    },
    {
        label:  h('div',{ },'Administrador' ),
        type:'group',
        children:[
            {
                icon: () => h(UserOutlined),
                key: 'users_index',
                label:  h('div',{ },'Usuarios' ),
                onClick: () => {
                    router.get(route('admin.users'));
                },
            },
            {
                icon: () => h(SafetyOutlined),
                key: 'rols_permissions_index',
                label:  h('div',{ },'Roles y Permisos' ),
                onClick: () => {
                    router.get(route('admin.rols_permissions'));
                },
            },
            
        ]
    },
    {
        label:  h('div',{ },'Paciente' ),
        type:'group',
    },
    {
        label:  h('div',{ },'Doctor' ),
        type:'group',
    },
    {
        label:  h('div',{ },'Personal de Enfermería' ),
        type:'group',
    },
    {
        label:  h('div',{ },'Gerente' ),
        type:'group',
    },
    {
        label:  h('div',{ },'Atención al Paciente' ),
        type:'group',
    },
    

   

]);
const page = usePage();


// 4. Computed Properties


const selectedKeys = computed(() => [route().current()]);
console.log(route().current());
// Opcional: Para que los submenús se abran solos si estás dentro de uno
const openKeys = computed(() => {
    const current = route().current();
    if (current && current.includes('admin.users')) return ['users'];
    return [];
});
// 5. Methods & Logic (Functions, Handlers)
// 6. Watchers
// 7. Lifecycle Hooks (onMounted, etc.)
// 8. Expose (defineExpose)


//v-model:selectedKeys="state.selectedKeys"
</script>
<template>
    <div class="d-flex flex-column overflow-auto flex-fill border">
        <a-menu
            
            v-model:selectedKeys="selectedKeys"
            :openKeys="openKeys"
            :inline-collapsed="state.collapsed"
            :items="sidebarItems"
            mode="inline"
            theme="light"
            
            
        />    
    </div>
    
</template>

<style lang="scss" scoped>

</style>