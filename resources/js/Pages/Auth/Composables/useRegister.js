import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { message } from 'ant-design-vue';
import { capitalizeWords, normalizeText } from '@/helpers/helpers';

export function useRegister() {
	const registerForm = useForm({
		dni: '',
		email: '',
		password: '',
		password_confirmation: '',
		terms: false,
		first_names: '',
		last_names: '',
		gender: null,
		birthday: '',
		avatar: null,
		terms: false,
	});
	const registerFormRef = ref(null);
	
	watch(
		() => registerForm.first_names,
		(newVal) => {
		registerForm.first_names = capitalizeWords(newVal);
		}
	);
	watch(
		() => registerForm.last_names,
		(newVal) => {
		registerForm.last_names = capitalizeWords(newVal);
		}
	);
	watch(
		() => registerForm.email,
		(newVal) => {
		registerForm.email = normalizeText(newVal);
		}
	);

	const handleLogin = () => {
		const url = route('login');
		router.visit(url);
	};
	const handleSubmit = async () => {
		if (!registerFormRef.value) {
			console.error('Error: registerFormRef no está vinculado al componente.');
		return;
		}

		try {
		const values = await registerFormRef.value.validate().catch((err) => {
			if (err.outOfDate && err.errorFields.length === 0) {
			return registerForm.data();
			}
			throw err;
		});

		registerForm.post(route('register'), {
			onSuccess: () => {
				message.success('Registro completado');
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
	

  	return { 
		registerForm,
		registerFormRef,
		handleLogin,
		handleSubmit,
	};
}
