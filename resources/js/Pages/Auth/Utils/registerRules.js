export const getRegisterRules = (form) => ({
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
		},
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
		},
	],
	password_confirmation: [
		{
		validator: async (_rule, value) => {
			if (value === '') {
			return Promise.reject('Por favor vuelva a ingresar la contraseña');
			} else if (value.length < 8) {
			return Promise.reject('La contraseña debe tener al menos 8 caracteres');
			} else if (value !== form.password) {
			return Promise.reject('Las contraseñas no coinciden');
			} else {
			return Promise.resolve();
			}
		},
		trigger: 'change',
		},
	],
	});
