import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';


export function useForgotPassword() {
    const forgotPasswordForm = useForm({
        email: '',
    });
    const forgotPasswordRef = ref(null);
    const handleSubmit = async () => {
        if (!forgotPasswordRef.value) {
            console.error('Error: forgotPasswordRef no está vinculado al componente.');
            return;
        }

        try {
            const values = await forgotPasswordRef.value.validate().catch((err) => {
                if (err.outOfDate && err.errorFields.length === 0) {
                return forgotPasswordForm.data();
                }
                throw err;
            });

            forgotPasswordForm.post(route('password.email'), {
                onSuccess: () => {
                    message.success('Verificación exitosa.');
                },
                onError: (errors) => {
                    console.error('Errores del servidor:', errors);
                    const msg = errors.email;
                    message.warning(msg);
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
        forgotPasswordForm,
        forgotPasswordRef,
        handleLogin,
        handleSubmit,
    };
}