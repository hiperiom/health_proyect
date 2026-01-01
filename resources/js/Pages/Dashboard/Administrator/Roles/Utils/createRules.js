export const getCreateRules = (form) => ({
	
	name: [
		{
		validator: async (_rule, value) => {
			if (value === '') {
			return Promise.reject('Por favor ingrese el nombre del rol');
			} else if (value.length > 50) {
			return Promise.reject('El nombre debe tener menos de 30 caracteres');
			} else {
			return Promise.resolve();
			}
		},
		trigger: 'change',
		},
	],

});
