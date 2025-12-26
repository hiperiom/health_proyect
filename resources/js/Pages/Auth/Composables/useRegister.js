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
	
	

	// Watchers para limpieza de datos
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
		// 1. Limpieza de seguridad: Si no hay form, no hacemos nada
		if (!registerFormRef.value) {
			console.error('Error: registerFormRef no está vinculado al componente.');
		return;
		}

		try {
		// 2. Ejecutar validación con un bloque catch interno para el outOfDate
		const values = await registerFormRef.value.validate().catch((err) => {
			// Si el error es solo por outOfDate y no hay campos rojos, devolvemos los valores manualmente
			if (err.outOfDate && err.errorFields.length === 0) {
			return registerForm.data();
			}
			// Si hay errores reales, lanzamos el error para que caiga en el catch principal
			throw err;
		});

		// 3. Envío de datos (Inertia)
		// Usamos el objeto directo para evitar problemas de reactividad
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
