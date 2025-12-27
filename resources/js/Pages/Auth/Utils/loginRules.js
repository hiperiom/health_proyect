export const getLoginRules = (form) => ({
    email: [
        {
            validator: async (_rule, value) => {
                if (value === '' || value === null || value === undefined) {
                    return Promise.reject('Por favor ingrese el correo electrónico o cédula');
                }
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const digitsOnly = /^\d{6,8}$/;
                if (emailRegex.test(value) || digitsOnly.test(value)) {
                    return Promise.resolve();
                }
                return Promise.reject('Ingrese un correo válido o una cédula de 6 a 8 dígitos');
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
        }
    ],
});
