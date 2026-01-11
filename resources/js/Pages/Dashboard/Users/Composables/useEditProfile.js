import { ref, watch } from 'vue';
import axios from 'axios';
import { message } from 'ant-design-vue';
import { router, useForm } from '@inertiajs/vue3';
import { capitalizeWords, normalizeText } from '@/helpers/helpers';

export function useEditProfile(user_id, emit, config) {
    // Crear formulario dinámicamente basado en la configuración
    const initialFormData = {
        avatar: null
    };
    Object.keys(config.formFields).forEach(field => {
        initialFormData[field] = '';
    });

    const form = useForm(initialFormData);
    const formRef = ref(null);

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

            await axios.post(route(config.modelNameKebabCase +'.update', user_id), form.data(),{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            router.reload({ 
                only: ['auth'], // Le decimos a Laravel que solo reenvíe el objeto auth
                onSuccess: () => {
                // Los datos en page.props.auth.user ya estarán actualizados aquí
                }
            })
            message.success('¡Registro actualizado con éxito!');

            form.resetAndClearErrors();
            emit('handleOpenModalEditProfile', false);

        } catch (error) {
            console.log(error);
            if (error.response?.status === 422) {
                form.setError(error.response.data.errors);
                message.warning('Revisa los campos del formulario');
            } else {
                const msg = error.response?.data?.message || 'Error inesperado';
                message.error(msg);
            }
        }
    };
    const fetchRecords = async () => {
        form.processing = true;
        try {
            const res = await axios.get(route('auth.profile',user_id));
            const newItem = res.data.data;

            Object.keys(newItem).forEach(fieldName => {
                if (fieldName === 'profile_photo_url') {
                    form['avatar'] = newItem['profile_photo_url'];
                } else {
                    form[fieldName] = newItem[fieldName] || '';
                }
            });
            
        } catch (error) {
            console.error("Error obteniendo registros:", error);
            throw error;
        } finally {
            form.processing = false;
        }
    };
    return {
        form,
        formRef,
        handleSubmit,
        fetchRecords,
    };
}
