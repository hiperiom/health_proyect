<script>
    export default {
        name: "Login",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { ref } from 'vue';
    import { message } from 'ant-design-vue';
    import { Link, router, useForm } from '@inertiajs/vue3';
    import AuthLayout from '@/Layouts/AuthLayout.vue';

    import { useLogin } from './Composables/useLogin';
    import { getLoginRules } from './Utils/loginRules';

    import Header from '@/Pages/Auth/Components/Header.vue';
    import LoginFields from './Components/LoginFields.vue';
    import LoginTour from './Components/LoginTour.vue';
    import AvatarCompany from '../../Components/AvatarCompany.vue';

    // 2. Props & Emits (defineProps, defineEmits)
    const { 
		loginForm,
		loginFormRef,
		handleRegister,
        handleForgotPassword,
		handleSubmit,
	} = useLogin();

    const rulesForm = getLoginRules(loginForm);
    // 3. State (ref, reactive)
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)



</script>

<template>
    <AuthLayout>
        <template #content>
            <a-row  justify="center"  :wrap="true" >
                <a-col :span="24" class="p-2 text-center" >
                </a-col>
                <a-col class="p-4 p-sm-4 p-md-4 p-lg-4 p-xl-4 p-xxl-4" :xs="19" :sm="15" :md="15" :lg="15" :xl="15" :xxl="15" >
                    <a-card class="glass-container fade-in">
                        <AvatarCompany />
                        <Header title="Iniciar Sesión" />
                        <a-spin class="text-white"  tip="Espere un momento..." :spinning="loginForm.processing">
                            <a-form 
                                ref="loginFormRef"
                                layout="vertical" 
                                :model="loginForm" 
                                :rules="rulesForm"
                                @submit.prevent="handleSubmit"
                            >
                                <LoginFields :loginForm="loginForm" />
                                <a-button 
                                    id="tour-register-button"
                                    ref="tourStep2"
                                    type="link"
                                    block
                                    class="btn-success"
                                    @click="handleRegister"
                                >
                                    Registro de Paciente
                                </a-button>
                            </a-form>
                        </a-spin> 
                        
                        <a-button 
                            id="tour-forgot-password-button"
                            ref="tourStep3"
                            type="link"
                            block
                            class="mt-2"
                            @click="handleForgotPassword"
                        >
                            ¿Olvidaste tu contraseña?
                        </a-button>
                     
                    </a-card>
                </a-col>
               
            </a-row>
            <a-row>
                <a-col :span="24" class="text-center">
                    <LoginTour />
                </a-col>
            </a-row>
        </template>
    </AuthLayout>
    
</template>
<style scoped>
    
</style>
