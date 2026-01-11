import { ref, watch } from 'vue';
import axios from 'axios';
import { message } from 'ant-design-vue';
import { router, useForm } from '@inertiajs/vue3';
import { capitalizeWords, normalizeText } from '@/helpers/helpers';

export function useEditSegurity(user_id, emit, config) {
    // Crear formulario dinámicamente basado en la configuración
    const initialFormData = {
        current_password: '',
        password: '',
        password_confirmation: '',
    };
    Object.keys(config.formFields).forEach(field => {
        initialFormData[field] = '';
    });

    const form = useForm(initialFormData);
    const formRef = ref(null);

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

            await axios.put(route('user-password.update'), form.data());
            
            message.success('Contraseña actualizada con éxito!');

            form.resetAndClearErrors();
            emit('handleOpenModalEditSegurity', false);

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
