<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h,ref,  reactive, watch, computed } from 'vue';
    import { router, usePage } from '@inertiajs/vue3';
    import { UserOutlined,  MenuUnfoldOutlined, MenuFoldOutlined, DashboardOutlined, SafetyOutlined } from '@ant-design/icons-vue';

    import Navbar from '@/Components/Navbar.vue';
    import AvatarCompany from '@/Components/AvatarCompany.vue';

    // 2. Props & Emits (defineProps, defineEmits)
    // 3. State (ref, reactive)
    const selectedKeys = computed(() => [route().current()]);
    const collapsed = ref(false);
    const state = reactive({
        collapsed: false,
    });
    const page = usePage();
    let optionsList = ref([]);
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    // 6. Watchers
    watch(collapsed, () => {
        optionsList.value = []
        page.props.auth.user.roles.forEach(role => {
            if (role.name === 'Administrador'){
                optionsList.value.push({
                    icon: () => h(UserOutlined),
                    key: 'admin_group',
                    label: h('div',{class:"text-center"}, !collapsed.value ? role.name : h(UserOutlined)) ,
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
                            label: h('div', 'Usuarios'),
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
                        {
                            icon: () => h(SafetyOutlined),
                            key: 'medics.index',
                            label: h('div', 'Médicos'),
                            onClick: () => router.get(route('medics.index')),
                        },

                    ]
                })


            }
            if (role.name === 'Paciente'){
                optionsList.value.push({
                    icon: () => h(UserOutlined),
                    key: 'patient_group',
                    label: h('div', role.name),
                    type: 'group',
                    children: []
                })


            }
        });

    }, { immediate: true });
    // 7. Lifecycle Hooks ( etc.)
    // 8. Expose (defineExpose)


    
    const sidebarItems = computed(() => optionsList.value);
</script>
<template>
    <a-layout class="h-dvh-100">
        <a-layout-sider v-model:collapsed="collapsed" :trigger="null" collapsible class="glass-container rounded-3 my-2 ms-3 bg-transparent">
            <AvatarCompany :size="40"  />
            <a-menu
                :selectedKeys="selectedKeys"
                :items="sidebarItems"
                mode="inline"
                theme="light"
                class="border-0 bg-transparent custom-menu"
            />
        </a-layout-sider>
        <a-layout class="py-2 px-3 gap-2">
            <a-layout-header class="glass-container bg-transparent rounded-3 p-2 d-flex align-items-center">
                <menu-unfold-outlined v-if="collapsed" class="trigger" @click="() => (collapsed = !collapsed)" />
                <menu-fold-outlined v-else class="trigger" @click="() => (collapsed = !collapsed)" />
                <navbar class="ms-auto" />
            </a-layout-header>
            <a-layout-header class="glass-container bg-transparent rounded-3 p-2">
                <slot name="header" />
            </a-layout-header>
            <a-layout-content class="glass-container rounded-3 d-flex p-2 overflow-auto">
                <slot name="content" />
                <slot name="footer" />
            </a-layout-content>
        </a-layout>
    </a-layout>
</template>


<style>
    .ant-menu-item-selected {
        background-color:  rgba(0, 0, 0, 0.06) !important;
    }
</style>
