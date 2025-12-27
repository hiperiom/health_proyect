import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';


export function useLogin() {
	const loginForm = useForm({
        email: '',
        password: '',
        remember: false,
    });
    const loginFormRef = ref(null);
    const handleSubmit = async () => {
		if (!loginFormRef.value) {
			console.error('Error: loginFormRef no está vinculado al componente.');
		    return;
		}

		try {
            const values = await loginFormRef.value.validate().catch((err) => {
                if (err.outOfDate && err.errorFields.length === 0) {
                return loginForm.data();
                }
                throw err;
            });

            loginForm.post(route('login'), {
                onSuccess: () => {
                    message.success('Autenticación exitosa.');
                },
                onError: (errors) => {
                    console.error('Errores del servidor:', errors);
                    message.error('Error al procesar el registro');
                },
            });
		} catch (error) {
            if (error.errorFields && error.errorFields.length > 0) {
                const msg = error.errorFields[0].errors[0];
                message.warning(msg);
            }
		}
	};
	const handleRegister = () => {
        const url = route('register'); 
        router.visit(url);
    };
    const handleForgotPassword = () => {
        const url = route('password.request'); 
        router.visit(url);
    };

  	return { 
		loginForm,
		loginFormRef,
        handleForgotPassword,
		handleRegister,
		handleSubmit,
	};
}