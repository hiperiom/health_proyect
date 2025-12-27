export const getResetPasswordRules = (form) => ({
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
