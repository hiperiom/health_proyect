<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h, reactive } from 'vue';
    import { router, usePage } from '@inertiajs/vue3';
    import { LockOutlined, ProfileOutlined,LogoutOutlined, UserOutlined } from '@ant-design/icons-vue';
    import { Avatar,Tag } from 'ant-design-vue';
    // 2. Props & Emits (defineProps, defineEmits)

    // 3. State (ref, reactive)
    const state = reactive({
        collapsed: false,
    });
    const page = usePage();
    const {profile_photo_url, name} = page.props.auth.user;
    const rol = page.props['0']['user.roles'][0];
    const horizontalMenuItems = reactive(
        [
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
                                            class:'text-'+rol.color
                                        }, rol.name),
                                        h('b', name),
                                    ]),
                                h(Avatar, {
                                    src: profile_photo_url,
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
                class: 'fade-in pe-0',
                children: [
                    {   
                        key: 'profile',
                        label: 'Perfil',
                        icon: () => h(ProfileOutlined),
                        
                    },
                    {   
                        key: 'segurity',
                        label: 'Seguridad',
                        icon: () => h(LockOutlined),
                        
                    },
                    {   
                        key: 'logout',
                        label: 'Salir',
                        icon: () => h(LogoutOutlined),
                        onClick: () => {
                            router.post(route('logout'));
                        },
                    },
                ],
            
            },

        ]);


    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)

    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
    <a-menu 
        style="height:50px"
        class="d-flex align-items-center border-0"
        mode="horizontal" 
        :items="horizontalMenuItems" 
    
    />
</template>
<style>
 
</style>