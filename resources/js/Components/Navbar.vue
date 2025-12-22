<script setup>
// 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
import { h, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import { MenuUnfoldOutlined, MenuFoldOutlined, LogoutOutlined, UserOutlined } from '@ant-design/icons-vue';
import { Avatar } from 'ant-design-vue';
// 2. Props & Emits (defineProps, defineEmits)

// 3. State (ref, reactive)
    const state = reactive({
        collapsed: false,
        /* selectedKeys: ['1'], */
        /* openKeys: ['sub1'],
        preOpenKeys: ['sub1'], */
    });
    const horizontalMenuItems = reactive(
        [
            /* {
                key: 'sidebar_trigger',
                icon: () => {
                    return state.collapsed ? h(MenuUnfoldOutlined) : h(MenuFoldOutlined);
                },
            }, */
            {
                key: 'home_menu',
                label:  h(
                            'div', 
                            { 
                                trigger: ['click'], 
                                class: 'd-flex push-right' 
                            }, 
                            [
                                h(
                                    'div',
                                    {
                                        class:'d-flex flex-column align-items-end me-1 justify-content-center',
                                        style:'line-height: 1.2;',
                                    }, 
                                    [
                                        h('i',{
                                            style:'font-size: 0.7rem;',
                                        }, 'Administrador'),
                                        h('b', 'Dr. Ranses J.'),
                                    ]),
                                h(Avatar, {
                                    //src: 'https://www.antdv.com/assets/logo.1ef800a8.svg',
                                    class: 'bg-white',
                                    size: 'default',
                                }, 
                                () => h(Avatar, {
                                        class: 'bg-transparent',
                                        icon: h(UserOutlined, {class:'fs-5 text-primary'}),
                                
                                    })
                                ),
                
                            ] 
                        ),
                class: 'push-right',
                children: [
                {
                    key: 'logout',
                    label: 'Salir',
                    icon: () => h(LogoutOutlined),
                    onClick: () => {
                        router.post(route('logout'));
                    },
                },
                /* {
                    key: 'sub_option2',
                    label: 'Sub Option 2',
                    icon: () => h(DesktopOutlined),
                },
                {
                    key: 'sub_option3',
                    label: 'Sub Option 3',
                    icon: () => h(InboxOutlined),
                }, */
                ],
            
            },

        ]);


// 4. Computed Properties
// 5. Methods & Logic (Functions, Handlers)
const handleNavbarClick = ({ key }) => {
  
  /* if (key === 'sidebar_trigger') {
    toggleCollapsed();
  } */
};
// 6. Watchers
// 7. Lifecycle Hooks (onMounted, etc.)
// 8. Expose (defineExpose)
</script>
<template>
    <a-menu 
        style="height:50px"
        class="bg-primary py-2 text-white d-flex align-items-center"
        mode="horizontal" 
        :items="horizontalMenuItems" 
        @click="handleNavbarClick"
    />
</template>
<style>
    .push-right {
        margin-left: auto !important;
    }
    :where(.css-dev-only-do-not-override-1p3hq3p).ant-menu-light .ant-menu-item:hover:not(.ant-menu-item-selected):not(.ant-menu-submenu-selected), :where(.css-dev-only-do-not-override-1p3hq3p).ant-menu-light .ant-menu-submenu-title:hover:not(.ant-menu-item-selected):not(.ant-menu-submenu-selected) {
        color: var(--white);
    }
    :where(.css-dev-only-do-not-override-1p3hq3p).ant-menu-light .ant-menu-item:hover:not(.ant-menu-item-selected):not(.ant-menu-submenu-selected), :where(.css-dev-only-do-not-override-1p3hq3p).ant-menu-light .ant-menu-submenu-title:hover:not(.ant-menu-item-selected):not(.ant-menu-submenu-selected) {
        color: var(--dark);
    }
</style>