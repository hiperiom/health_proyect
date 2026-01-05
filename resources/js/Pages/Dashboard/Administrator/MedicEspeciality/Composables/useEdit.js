import { ref, watch } from 'vue';
import axios from 'axios';
import { message } from 'ant-design-vue';
import { useForm } from '@inertiajs/vue3';
import { capitalizeWords, normalizeText } from '@/helpers/helpers';

export function useEdit(item,modalOpen,modelName) {
    const form = useForm({
        dni: '',
        email: '',
        first_names: '',
        last_names: '',
        gender: null,
        birthday: '',
    });
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

            await axios.put(route(modelName.toLowerCase() +'.update', item.id), form.data());
            message.success('¡Actualizado con éxito!');

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

