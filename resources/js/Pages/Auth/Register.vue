
<script setup>
    import { router, useForm } from '@inertiajs/vue3';
    import { message } from 'ant-design-vue'
    import RegisterLayout from '@/Layouts/RegisterLayout.vue';
    import Header from './Components/Header.vue';

    /* const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const onSubmit = () => {
        form.transform(data => ({
            ...data,
            remember: form.remember ? 'on' : '',
        })).post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    }; */
    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: false,
    });

   /*  const handleSubmit = () => {
        form.post(route('register'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    }; */
    const handleSubmit = () => {
        try {

            form.transform(data => ({
                ...data,
                //remember: form.remember ? 'on' : '',
            })).post(route('register'), {

                onFinish: () => {
                    message.success('Datos registrados correctamente')
                    form.reset(
                        'name', 
                        'email',
                        'password', 
                        'password_confirmation'
                    )
                },
            });
        } catch (error) {
            // Mostrar mensaje de error
            message.error('Hubo un problema al registrar los datos')
        }

    };
    const handleLogin = () => {
        const url = route('login'); 
        router.visit(url);
    };
    const validatePass = async (_rule, value) => {
        if (value === '') {
          return Promise.reject('Por favor ingrese la contraseña');
        } else {
          if (form.password !== '') {
            formRef.value.validateFields('password_confirmation');
          }
          return Promise.resolve();
        }
      };
      const validatePass2 = async (_rule, value) => {
        if (value === '') {
          return Promise.reject('Por favor vuelva a ingresar la contraseña');
        } else if (value !== form.password) {
          return Promise.reject("Las contraseñas no coinciden");
        } else {
          return Promise.resolve();
        }
      };

    const rules = {
        name: [
            {
                required: true,
                message: 'Por favor ingrese su cédula',
                trigger: 'change',
            },
            
        ],
        email: [
            {
                required: true,
                message: 'Por favor ingrese el correo electrónico',
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
        password_confirmation: [
            {
                required: true,
                message: 'Por favor vuelva a ingresar la contraseña',
                trigger: 'change',
            },
            {
                validator: validatePass2,
                trigger: 'change',
            },
        ],
    };
</script>

<template>
    <RegisterLayout >
     
        <template #content>
            <a-row  justify="center"  ::wrap="true" >
                <a-col :span="24" class="p-2 text-center" >
                    <Header title="Registro de Paciente" subTitle="Completa la siguiente información para crear tu cuenta" />
                </a-col>
                <a-col class="glass-container p-4 p-sm-4 p-md-4 p-lg-4 p-xl-4 p-xxl-4" :xs="20" :sm="20" :md="20" :lg="15" :xl="12" :xxl="10" >
                
                    <a-form 
                        ref="formRef"
                        layout="vertical" 
                        :model="form" 
                        :rules="rules"
                        @finish="handleSubmit"
                    >
                            
                    
                        <a-row justify="center" :gutter="10" :wrap="true" >
                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12" >
                                <a-form-item name="name" ref="name" has-feedback label="Cédula">
                                    <a-input 
                                    name="name"
                                    v-model:value="form.name" 
                                    placeholder="Escribe aquí tu cédula" 
                                    />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12" >
                                <a-form-item name="email" ref="email" has-feedback label="Correo electrónico">
                                    <a-input 
                                    name="username"
                                    v-model:value="form.email" 
                                    placeholder="Escribe aquí tu correo electrónico o cédula" 
                                    />
                                </a-form-item>       
                            </a-col>
                        </a-row>
                        <a-row justify="center" :gutter="10" :wrap="true" >
                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12" >
                                <a-form-item name="password" ref="password" hasFeedback label="Contraseña">
                                    <a-input-password 
                                    name="password"
                                    v-model:value="form.password" 
                                    placeholder="Escribe aquí tu contraseña" 
                                    />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12" >
                                <a-form-item name="password_confirmation" ref="password_confirmation" hasFeedback label="Confirmar Contraseña">
                                    <a-input-password 
                                    name="password"
                                    v-model:value="form.password_confirmation" 
                                    placeholder="Escribe aquí tu contraseña" 
                                />
                                </a-form-item>
                            </a-col>
                        </a-row>
                        <a-row justify="center" :gutter="10" :wrap="true" >
                            <a-col :span="24" class="text-center" >
                                 <a-form-item v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
                                    <a-checkbox v-model:checked="form.terms">Acepto los términos y condiciones</a-checkbox>
                                </a-form-item>
                            </a-col>
                            
                        </a-row>
                        <a-row justify="center" :gutter="10" :wrap="true" >
                            <a-col :xs="24" :sm="12" :md="10" :lg="10" :xl="8" class="text-center" >
                                <a-row justify="center"  :wrap="true" >
                                    <a-col :span="24"  class="text-center" >
                                        <a-form-item class="mb-2">
                                            <a-button  
                                                type="primary" 
                                                html-type="submit" 
                                                :class="{ 'opacity-25': form.processing }" 
                                                :disabled="form.processing" 
                                                block
                                            >
                                                Registrar
                                            </a-button>
                                        </a-form-item>
                                    </a-col>
                                    <a-col :span="24"  class="text-center" >
                                        <a-form-item>
                                      
                                        <a-button  type="link"  @click="handleLogin" block>
                                             Ya estoy registrado
                                        </a-button>
                                        </a-form-item>
                                    </a-col>
                                </a-row>
                            </a-col>
                        </a-row>
                    </a-form>
                </a-col>
            </a-row>
        </template>
    </RegisterLayout>

</template>

<!-- <script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';


</script>

<template>
    <Head title="Register" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                        <div class="ms-2">
                            I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacy Policy</a>
                        </div>
                    </div>
                    <InputError class="mt-2" :message="form.errors.terms" />
                </InputLabel>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
 -->