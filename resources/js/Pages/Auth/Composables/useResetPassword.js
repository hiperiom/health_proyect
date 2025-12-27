import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';


export function useResetPassword() {
    

    const resetPasswordForm = useForm({
        token: '',
        email: '',
        password: '',
        password_confirmation: '',
    });
    const resetPasswordRef = ref(null);
    const handleSubmit = async () => {
        if (!resetPasswordRef.value) {
            console.error('Error: resetPasswordRef no está vinculado al componente.');
            return;
        }

        try {
            const values = await resetPasswordRef.value.validate().catch((err) => {
                if (err.outOfDate && err.errorFields.length === 0) {
                return resetPasswordForm.data();
                }
                throw err;
            });

            resetPasswordForm.post(route('password.update'), {
                onSuccess: () => {
                    message.success('Actualización exitosa.');
                },
                onError: (errors) => {
                    console.error('Errores del servidor:', errors);
                    message.error('Error al procesar la Actualización');
                },
            });
        } catch (error) {
            if (error.errorFields && error.errorFields.length > 0) {
                const msg = error.errorFields[0].errors[0];
                message.warning(msg);
            }
        }
    };

    const handleLogin = () => {
        const url = route('login'); 
        router.visit(url);
    };

    return { 
        resetPasswordForm,
        resetPasswordRef,
        handleLogin,
        handleSubmit,
    };
}