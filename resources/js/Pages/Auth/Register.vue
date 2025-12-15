
<script setup>
    import { router, useForm, } from '@inertiajs/vue3';
    import { message } from 'ant-design-vue'
    import RegisterLayout from '@/Layouts/RegisterLayout.vue';
    import Header from './Components/Header.vue';
    import { ref } from 'vue';

    const form = useForm({
        dni: '12345678',
        email: 'admin1@test.com',
        password: '12345678',
        password_confirmation: '12345678',
        terms: false,
        dni: '',
        first_names: 'Ranses',
        last_names: 'jimenez',
        gender: 'm',
        birthday: '',
    });
    const gender = [
        {
            value: 'm',
            label: 'Masculino',
        },
        {
            value: 'f',
            label: 'Femenino',
        },
    
    ];
    /**
     * Capitaliza la primera letra de cada palabra y pone el resto en minúscula.
     * @param {Event} event - El objeto de evento del input.
     */
    const capitalizeWords = (event) => {
        // 1. Obtener el valor actual del input
        let value = event.target.value;

        // 2. Aplicar la lógica de capitalización:
        if (value) {
            value = value.toLowerCase().split(' ')
                .map((word) => {
                    // Si la palabra no está vacía, capitaliza la primera letra
                    if (word.length > 0) {
                        return word.charAt(0).toUpperCase() + word.slice(1);
                    }
                    return ''; // Maneja espacios dobles o palabras vacías
                })
                .join(' ');
        }

        // 3. Actualizar directamente el modelo de datos
        form[event.target.name] = value;
    };

    const normalizeText = (event) => {
        // 1. Obtener el valor actual del input
        let value = event.target.value;

        // 2. Aplicar la lógica de normalización
        if (value) {
            // .toLowerCase(): Convierte toda la cadena a minúsculas
            // .replace(/\s/g, ''): Busca todas las ocurrencias de espacios en blanco (\s) 
            //                        de forma global (g) y los reemplaza por una cadena vacía ('').
            value = value.toLowerCase().replace(/\s/g, '');
        }

        // 3. Actualizar directamente el modelo de datos
        form[event.target.name] = value;
    };
    const handleSubmit = () => {
        try {

            form.transform(data => ({
                ...data,
            })).post(route('register'), {
                onError: (errors) => {
                    console.error('Errores recibidos:', errors);
                    message.error('Credenciales o validación fallida.');
                 
                },
                onSuccess: () => {
                    message.success('Registro exitoso.');
                },
                onFinish: (response) => {
                    form.reset(); // Opcional
                },
            });
        } catch (error) {
            message.error('Hubo un problema al validar los datos')
        }

    };

    const handleLogin = () => {
        const url = route('login'); 
        router.visit(url);
    };

    const rules = {
        dni: [
            {
                validator: async (_rule, value) => {
                    if (value === '' || value === null || value === undefined) {
                        return Promise.reject('Por favor ingrese su cédula');
                    }
                    const digitsOnly = /^\d+$/;
                    if (!digitsOnly.test(value)) {
                        return Promise.reject('La cédula debe contener solo números');
                    }
                    if (value.length < 6 || value.length > 8) {
                        return Promise.reject('La cédula debe tener entre 6 y 8 dígitos');
                    }
                    return Promise.resolve();
                },
                trigger: 'change',
            },
        ],
        email: [
            {
                validator: async (_rule, value) => {
                    if (value === '') {
                        return Promise.reject('Por favor ingrese el correo electrónico');
                    }
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        return Promise.reject('Por favor ingrese un correo electrónico válido');
                    }
                    return Promise.resolve();
                },
                trigger: 'change',
            }
            
        ],
        first_names: [
            {
                validator: async (_rule, value) => {
                    if (value === '') {
                        return Promise.reject('Por favor ingrese tu primer y segundo nombre');
                    } else if (value.length > 50) {
                        return Promise.reject('El nombre debe tener menos de 30 caracteres');
                    } else {
                        return Promise.resolve();
                    }
                },
                trigger: 'change',
            },
        ],
        last_names: [
            {
                validator: async (_rule, value) => {
                    if (value === '') {
                        return Promise.reject('Por favor ingrese tu primer y segundo apellido');
                    } else if (value.length > 50) {
                        return Promise.reject('El apellido debe tener menos de 30 caracteres');
                    } else {
                        return Promise.resolve();
                    }
                },
                trigger: 'change',
            },
        ],
        gender: [
            {
                validator: async (_rule, value) => {
                    if (value === null) {
                        return Promise.reject('Por favor selecciona el sexo');
                    } else {
                        return Promise.resolve();
                    }
                },
                trigger: 'change',
            },
        ],
        birthday: [
            {
                validator: async (_rule, value) => {
                    if (value === '') {
                        return Promise.reject('Por favor ingrese tu fecha de nacimiento');
                    } else if (value.length > 50) {
                        return Promise.reject('La fecha de nacimiento debe tener menos de 30 caracteres');
                    } else {
                        return Promise.resolve();
                    }
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
        password_confirmation: [
            {
                validator: async (_rule, value) => {
                    if (value === '') {
                        return Promise.reject('Por favor vuelva a ingresar la contraseña');
                    } else if (value.length < 8) {
                        return Promise.reject('La contraseña debe tener al menos 8 caracteres');
                    } else if (value !== form.password) {
                        return Promise.reject("Las contraseñas no coinciden");
                    } else {
                        return Promise.resolve();
                    }
                },
                trigger: 'change',
            },
        ],
    };


</script>

<template>
    <RegisterLayout >
     
        <template #content>
            {{ form }}
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
                                <a-form-item name="dni" ref="dni" has-feedback label="Cédula">
                                    <a-input 
                                    name="dni"
                                    :maxlength="8"
                                    v-model:value="form.dni" 
                                    placeholder="Escribe aquí tu cédula" 
                                    />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12" >
                                <a-form-item name="email" ref="email" has-feedback label="Correo electrónico">
                                    <a-input 
                                    name="email"
                                    v-model:value="form.email" 
                                    placeholder="Escribe aquí tu correo electrónico" 
                                    @input="normalizeText"
                                    />
                                </a-form-item>       
                            </a-col>
                        </a-row>
                        <a-row justify="center" :gutter="10" :wrap="true" >
                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12" >
                                <a-form-item name="first_names" ref="first_names" has-feedback label="Nombres">
                                    <a-input 
                                    name="first_names"
                                    :maxlength="50"
                                    v-model:value="form.first_names" 
                                    placeholder="Escribe tu primer y segundo nombre" 
                                    @input="capitalizeWords"
                                    />
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12" >
                                <a-form-item name="last_names" ref="last_names" has-feedback label="Apellidos">
                                    <a-input 
                                    name="last_names"
                                    :maxlength="50"
                                    v-model:value="form.last_names" 
                                    placeholder="Escribe tu primer y segundo apellido" 
                                    @input="capitalizeWords"
                                    />
                                </a-form-item>       
                            </a-col>
                        </a-row>
                        <a-row justify="center" :gutter="10" :wrap="true" >
                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12" >
                                <a-form-item name="gender" has-feedback label="Género">
                                    <a-select
                                        
                                        name="gender"
                                        placeholder="Selecciona el sexo"
                                        v-model:value="form.gender"
                                        :options="gender"
                                        style="width: 100%"
                                        
                                    />
                                    
                                </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12" >
                                <a-form-item name="birthday" ref="birthday" has-feedback label="Fecha de nacimiento">
                                    <a-date-picker v-model:value="form.birthday" style="width: 100%" format="DD/MM/YYYY" placeholder="Escribe tu fecha de nacimiento dia/mes/año"  />
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
                                    name="password_confirmation"
                                    v-model:value="form.password_confirmation" 
                                    placeholder="Confirma aquí tu contraseña" 
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