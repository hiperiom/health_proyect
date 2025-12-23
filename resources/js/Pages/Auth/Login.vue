<script>
    export default {
        name: "Login",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { Link, router, useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import AuthLayout from '@/Layouts/AuthLayout.vue';
    import Header from '@/Pages/Auth/Components/Header.vue';
    import LoginForm from '@/Components/Auth/LoginForm.vue';
    import LoginTour from './Components/LoginTour.vue';
    import { message } from 'ant-design-vue';

    // 2. Props & Emits (defineProps, defineEmits)
    const loginForm = useForm({
        email: '',
        password: '',
        remember: false,
    });
    const rulesForm = {
        email: [
            {
                validator: async (_rule, value) => {
                    if (value === '' || value === null || value === undefined) {
                        return Promise.reject('Por favor ingrese el correo electrónico o cédula');
                    }
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    const digitsOnly = /^\d{6,8}$/;
                    if (emailRegex.test(value) || digitsOnly.test(value)) {
                        return Promise.resolve();
                    }
                    return Promise.reject('Ingrese un correo válido o una cédula de 6 a 8 dígitos');
                },
                trigger: 'change',
            },
        ],
        password: [
            {
                validator: async (_rule, value) => {
                    if (value === '') {
                        return Promise.reject('Por favor ingrese la contraseña');
                    } else if (value.length < 8) {
                        return Promise.reject('La contraseña debe tener al menos 8 caracteres');
                    } else {
                        return Promise.resolve();
                    }
                },
            }
        ],
    
    };
    // 3. State (ref, reactive)
    const tourCurrentStep = ref(0);
    const tourStep1 = ref(null);
    const tourStep2 = ref(null);
    const tourStep3 = ref(null);
    const loading = ref(false);
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleSubmit = async () => {
        if (!loginForm.isDirty){
           return false;
        }     
            
        try {
            loading.value = true;
            loginForm.transform(data => ({
                ...data,
                remember: loginForm.remember ? 'on' : '',
            })).post(route('login'), {
                onBefore: () => {
                        //para prevenir que se ejecute el login dos veces seguidas
                        new Promise((resolve, reject) => {
                        setTimeout(() => {
                            resolve(true);
                        }, 1000);
                    })
                },
                onError: (errors) => {
                    console.error('Errores recibidos:', errors);
                    message.error('Credenciales o validación fallida.');
                
                },
                onSuccess: () => {
                    message.success('Autenticación exitosa.');
                },
                onFinish: (response) => {
                    loginForm.reset(); // Opcional
                },
            });
        } catch (error) {
            message.error('Hubo un problema al validar los datos')
        }
        finally {
            loading.value = false;
        }
        
    };
    const handleUserRegister = () => {
        const url = route('register'); 
        router.visit(url);
    };
    const handleForgotPassword = () => {
        const url = route('password.request'); 
        router.visit(url);
    };
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
                    <a-card>
                        <Header title="Iniciar Sesión" />

                        <LoginForm 
                            ref="tourStep1"
                            :loginForm="loginForm" 
                            :rulesForm="rulesForm"
                            @handleSubmit="handleSubmit"
                        />
          
                        <a-button 
                            ref="tourStep2"
                            type="link"
                            block
                            class="btn-success"
                            @click="handleUserRegister"
                        >
                            Registro de Paciente
                        </a-button>
                        <a-button 
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
                    <LoginTour 
                        v-model:tourCurrentStep="tourCurrentStep"
                        :tourStep1="tourStep1"
                        :tourStep2="tourStep2"
                        :tourStep3="tourStep3"
                    />
                </a-col>
            </a-row>
        </template>
    </AuthLayout>
    
</template>
<style scoped>
    .btn-success:hover {
        color:var(--bs-white) !important;
        border-color:var(--bs-success) !important;
    }
</style>
