import { ref, watch } from 'vue';
import axios from 'axios';
import { message } from 'ant-design-vue';
import { useForm } from '@inertiajs/vue3';
import { capitalizeWords, normalizeText } from '@/helpers/helpers';

export function useCreate(modalOpen, config) {
    // Crear formulario dinámicamente basado en la configuración
    const initialFormData = {
        avatar: null,
    };

    // Agregar campos del formulario principal
    Object.keys(config.formFields).forEach(field => {
        initialFormData[field] = '';
    });

    // Agregar campos adicionales para creación
    if (config.createOnlyFields) {
        Object.keys(config.createOnlyFields).forEach(field => {
            initialFormData[field] = config.createOnlyFields[field].default || '';
        });
    }

    const form = useForm(initialFormData);
    const formRef = ref(null); 
    
    watch(
        () => form.dni,
        async (newDni) => {
            if (newDni && /^\d{7,8}$/.test(newDni)) {
                try {
                    const checkResponse = await axios.get(route('users.check-dni', { dni: newDni }));
                    if (checkResponse.data.exists) {
                        const userResponse = await axios.get(route('users.by-dni', newDni));
                        if (userResponse.data.data) {
                            const userData = userResponse.data.data;
                            form.avatar = userData.profile_photo_url;
                            form.email = userData.email;
                            form.first_names = userData.first_names;
                            form.last_names = userData.last_names;
                            form.gender = userData.gender;
                            form.birthday = userData.birthday;
                        }
                    }
                } catch (error) {
                    console.error('Error checking DNI', error);
                }
            }
        }
    );
    watch(
        () => form.first_names,
        (newVal) => {
        form.first_names = capitalizeWords(newVal);
        }
    );
    watch(
        () => form.last_names,
        (newVal) => {
        form.last_names = capitalizeWords(newVal);
        }
    );
    watch(
        () => form.email,
        (newVal) => {
        form.email = normalizeText(newVal);
        }
    );
    const handleSubmit = async () => {
        if (!formRef.value) {
            console.error('Error: formRef no está vinculado al componente.');
            return;
        }

        try {
            const values = await formRef.value.validate().catch((err) => {
                if (err.outOfDate && err.errorFields.length === 0) {
                    return form.data();
                }
                throw err;
            });
            
            await axios.post(route(config.modelNameKebabCase +'.store'), form.data(),{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            message.success('¡Registro creado con éxito!');
            
            form.reset();
            modalOpen.value = false;
    
        } catch (error) {
            if (error.response?.status === 422) {
                form.setError(error.response.data.errors);
                message.warning('Revisa los campos del formulario');
            } else {
                const msg = error.response?.data?.message || 'Error inesperado';
                message.error(msg);
            }
        }
    };
    return {
       form,
       formRef,
       handleSubmit,
    };
}
