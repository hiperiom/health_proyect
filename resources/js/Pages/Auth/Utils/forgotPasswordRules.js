export const getForgotPasswordRules = (form) => ({
    email: [
        {
            validator: async (_rule, value) => {
                if (value === '' || value === null || value === undefined) {
                    return Promise.reject('Por favor ingrese un correo electrónico valido');
                }
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailRegex.test(value)) {
                    return Promise.resolve();
                }
                return Promise.reject('Ingrese un correo válido');
            },
            trigger: 'change',
        },
    ],
});
