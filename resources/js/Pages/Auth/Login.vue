<script setup>
    import { useForm, router } from '@inertiajs/vue3';
    import { message } from 'ant-design-vue'
    import LoginLayout from '@/Layouts/LoginLayout.vue';
    import Header from './Components/Header.vue';

    defineProps({
        canResetPassword: {
            type: Boolean,
            default: true,
        },
        canLogin: {
            type: Boolean,
            default: true,
        },
        canRegister: {
            type: Boolean,
            default: true,
        },
        canRemenber: {
            type: Boolean,
            default: false,
        },

        
    });

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const handleSubmit = () => {
        try {

            form.transform(data => ({
                ...data,
                remember: form.remember ? 'on' : '',
            })).post(route('login'), {

                onFinish: () => {
                    message.success('Datos validados correctamente')
                    form.reset('password')
                },
            });
        } catch (error) {
            // Mostrar mensaje de error
            message.error('Hubo un problema al validar los datos')
        }

    };
    const handleResetPassword = () => {
        const url = route('password.request'); 
        router.visit(url);
    };
    const handleRegister = () => {
        const url = route('register'); 
        router.visit(url);
    };

    const rules = {
        email: [
            {
                required: true,
                message: 'Por favor ingrese el correo electrónico o cédula',
                trigger: 'change',
            },
            
        ],
        password: [
        {
            required: true,
            message: 'Por favor ingrese la contraseña',
            trigger: 'change',
        },
    ],
    
    };


</script>

<template>
    <LoginLayout>

        <template #content>
            <a-row  justify="center"  :wrap="true" >
                <a-col :span="24" class="p-2 text-center" >
                    <Header title="¡Bienvenido!" subTitle="Autenticate para ingresar a tu cuenta" />
                </a-col>
                <a-col class="glass-container p-4 p-sm-4 p-md-4 p-lg-4 p-xl-4 p-xxl-4" :xs="20" :sm="15" :md="15" :lg="10" :xl="8" :xxl="6" >
                    <a-form 
                        v-if="canLogin" 
                        ref="formRef"
                        layout="vertical" 
                        :model="form" 
                        :rules="rules"
                        @finish="handleSubmit"
                        
                    >
                        <a-row justify="center" :gutter="10" :wrap="true" >
                            <a-col :span="24">
                                <a-form-item ref="email" name="email" has-feedback label="Correo electrónico o cédula">
                                    <a-input 
                                    name="username"
                                    v-model:value="form.email" 
                                    placeholder="Escribe aquí tu correo electrónico o cédula" 
                                    />
                                </a-form-item>
                            </a-col>
                            <a-col :span="24">
                                <a-form-item ref="password" name="password" has-feedback label="Contraseña">
                                    <a-input-password 
                                    name="password"
                                    v-model:value="form.password" 
                                    placeholder="Escribe aquí tu contraseña" 
                                    />
                                </a-form-item>
                            </a-col>
                            <a-col :span="24">
                                <a-form-item >
                                    <a-button  type="primary" html-type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" block>
                                        Iniciar Sesión
                                    </a-button>
                                </a-form-item>
                            </a-col>
                            <a-col :span="24" class="text-center">
                               <a-form-item v-if="canRemenber" name="remember" >
                                    <a-checkbox v-model:checked="form.remember">Recordarme</a-checkbox>
                                </a-form-item>
                            </a-col>
                            <a-col :span="24" class="text-center">
                                <a-form-item v-if="canRegister" >
                                    <a-button class="btn-success"  @click="handleRegister" block>
                                        Registrarme como paciente
                                    </a-button>
                                </a-form-item>
                            </a-col>
                            <a-col :span="24" class="text-center">
                                <a-form-item  v-if="canResetPassword">
                                    <a-button  type="link"  @click="handleResetPassword" block>
                                        ¿Olvidaste tu contraseña?
                                    </a-button>
                                </a-form-item>
                            </a-col>
                        </a-row>
                    </a-form>
                    <div v-else class="mb-4 fs-4 text-center text-secondary fw-bold-600 ">
                        <a-typography-title class="text-primary fs-3">
                            No disponible!
                        </a-typography-title>
                        El sistema está en mantenimiento, por favor intente más tarde.
                    </div>
                </a-col>
            </a-row>
           
        </template>
   
    </LoginLayout>
</template>
