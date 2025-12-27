<script>
    export default {
        name: "ConfirmPassword",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { ref } from 'vue';
    import AuthLayout from '@/Layouts/AuthLayout.vue';

    import { useResetPassword } from './Composables/useResetPassword';

    import Header from '@/Pages/Auth/Components/Header.vue';
    import AvatarCompany from '../../Components/AvatarCompany.vue';
    import { getResetPasswordRules } from './Utils/resetPasswordRules.js';

    // 2. Props & Emits (defineProps, defineEmits)
    const props = defineProps({
        email: String,
        token: String,
    });
    const { 
		resetPasswordForm,
		resetPasswordRef,
		handleLogin,
		handleSubmit,
	} = useResetPassword();
    resetPasswordForm.email = props.email;
    resetPasswordForm.token = props.token;
    const rulesForm = getResetPasswordRules(resetPasswordForm);
    
    const submit = () => {
        form.post(route('password.update'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };
    //const rulesForm = getLoginRules(loginForm);
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
             
                <a-col class="p-4 p-sm-4 p-md-4 p-lg-4 p-xl-4 p-xxl-4" :xs="24" :sm="18" :md="15" :lg="15" :xl="15" :xxl="15" >
                    <a-card class="glass-container fade-in">
                        <AvatarCompany />
                        <Header title="Resetear contraseña" subTitle="Por favor, ingresa y confirma tu nueva contraseña para actualizar el acceso a tu cuenta." />
                        <a-spin  tip="Espere un momento..." :spinning="resetPasswordForm.processing">
                            <a-form 
                                ref="resetPasswordRef"
                                layout="vertical" 
                                :model="resetPasswordForm"
                                :rules="rulesForm" 
                                @submit.prevent="handleSubmit"
                            >
                                <a-row justify="center" :gutter="10" :wrap="true">
                                    <a-col span="24">
                                        <a-form-item name="password" ref="password" hasFeedback label="Contraseña">
                                            <a-input-password name="password" v-model:value="resetPasswordForm.password"
                                                placeholder="Escribe aquí tu contraseña" />
                                        </a-form-item>
                                    </a-col>
                                    <a-col span="24">
                                        <a-form-item name="password_confirmation" ref="password_confirmation"
                                            hasFeedback label="Confirmar Contraseña">
                                            <a-input-password name="password_confirmation"
                                                v-model:value="resetPasswordForm.password_confirmation"
                                                placeholder="Confirma aquí tu contraseña" />
                                        </a-form-item>
                                    </a-col>
                                </a-row>
                                <a-button 
                                    type="link"
                                    block
                                    class="btn-success"
                                    @click="handleSubmit"
                                >
                                    Resetear contraseña
                                </a-button>
                            </a-form>
                        </a-spin> 
                        
                        <a-button 
                            id="tour-forgot-password-button"
                            ref="tourStep3"
                            type="link"
                            block
                            class="mt-2"
                            @click="handleLogin"
                        >
                            Regresar al inicio
                        </a-button>
                     
                    </a-card>
                </a-col>
               
            </a-row>
            
        </template>
    </AuthLayout>
    
</template>
<style scoped>
    
</style>
