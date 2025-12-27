<script>
    export default {
        name: "ForgotPassword",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { ref } from 'vue';
    import AuthLayout from '@/Layouts/AuthLayout.vue';

    import { useForgotPassword } from './Composables/useForgotPassword';

    import Header from '@/Pages/Auth/Components/Header.vue';
    import AvatarCompany from '../../Components/AvatarCompany.vue';
    import { getForgotPasswordRules } from './Utils/forgotPasswordRules';

    // 2. Props & Emits (defineProps, defineEmits)
    const { 
		forgotPasswordForm,
		forgotPasswordRef,
		handleLogin,
		handleSubmit,
	} = useForgotPassword();
    const rulesForm = getForgotPasswordRules(forgotPasswordForm);
    

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
                        <Header title="Recuperación de contraseña" sub-title="Escribe tu correo electrónico para recibir un enlace para crear una nueva contraseña" />
                        <a-spin  tip="Espere un momento..." :spinning="forgotPasswordForm.processing">
                            <a-form 
                                ref="forgotPasswordRef"
                                layout="vertical" 
                                :model="forgotPasswordForm"
                                :rules="rulesForm" 
                                @submit.prevent="handleSubmit"
                            >
                                <a-row id="tour-identity" justify="center" :gutter="10" :wrap="true">
                                    <a-col :xs="24" :sm="24" :md="24" :lg="24" :xl="24" :xxl="24">
                                        <a-form-item name="email" ref="email" has-feedback label="Correo electrónico">
                                            <a-input name="email" :maxlength="50" v-model:value="forgotPasswordForm.email"
                                                placeholder="Escribe aquí tu correo electrónico"  />
                                        </a-form-item>
                                    </a-col>
                                
                                </a-row>
                                <a-button 
                                    type="link"
                                    block
                                    class="btn-success"
                                    @click="handleSubmit"
                                >
                                    Solicitar nueva contraseña
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


<!-- <script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot Password" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
 -->