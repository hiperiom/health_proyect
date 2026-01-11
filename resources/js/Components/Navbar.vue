<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h, reactive, ref, watch } from 'vue';
    import { router, usePage } from '@inertiajs/vue3';
    import { LockOutlined, ProfileOutlined,LogoutOutlined, UserOutlined } from '@ant-design/icons-vue';
    import { Avatar,Tag } from 'ant-design-vue';
    import EditProfile from '@/Pages/Dashboard/Users/EditProfile.vue';
    // 2. Props & Emits (defineProps, defineEmits)

    // 3. State (ref, reactive)
    const state = reactive({
        collapsed: false,
    });
    const editProfileModal = ref(false);
    const page = usePage();
    const {
        id:user_id
    } = page.props.auth.user;
    
    const rol = page.props['0']['user.roles'][0];
    let horizontalMenuItems = ref(null);


    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleOpenModalEditProfile = (value) => {
        editProfileModal.value = value;
    };
    // 6. Watchers
    watch(()=>page.props.auth.user,()=>{
        horizontalMenuItems.value = [
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
                                        h('b', page.props.auth.user.name),
                                    ]),
                                h(Avatar, {
                                    src: page.props.auth.user.profile_photo_url,
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
                        onClick: () => {
                            editProfileModal.value = true;
                        },
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

        ];
    },{ immediate: true, deep: true });
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
    <div class="d-flex flex-column">
        <a-menu 
            style="height:50px"
            class="d-flex align-items-center border-0"
            mode="horizontal" 
            :items="horizontalMenuItems" 
        
        />
        <EditProfile 
            :editProfileModal="editProfileModal"
            @handleOpenModalEditProfile="handleOpenModalEditProfile" 
            :user_id="user_id"
        />    
    </div>
    
    
</template>
<style>
 
</style>