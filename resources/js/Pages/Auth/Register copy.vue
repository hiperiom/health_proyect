<script>
	export default {
		name: 'Register',
	};
</script>
<script setup>
	// 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
	import { ref, watch } from 'vue';
	import { router, useForm } from '@inertiajs/vue3';
	import { message } from 'ant-design-vue';
	import AuthLayout from '@/Layouts/AuthLayout.vue';
	import Header from '@/Pages/Auth/Components/Header.vue';
	import RegisterForm from '@/Components/Auth/RegisterForm.vue';
	import RegisterTour from './Components/RegisterTour.vue';
	import AvatarCompany from './Components/AvatarCompany.vue';
	import { capitalizeWords, getBase64, normalizeText } from '@/helpers/helpers';
	import { LoadingOutlined, UserOutlined } from '@ant-design/icons-vue';
	// 2. Props & Emits (defineProps, defineEmits)
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
		profile_photo_path: null,
	});
	const gender = [
		{
		value: 'm',
		label: 'Masculino',
		},
		{
		value: 'f',
		label: 'Femenino',
		},
	];
	const rulesForm = {
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
				}
				
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
				}
				
			],
			password_confirmation: [
				{
					validator: async (_rule, value) => {
						if (value === '') {
							return Promise.reject('Por favor vuelva a ingresar la contraseña');
						} else if (value.length < 8) {
							return Promise.reject('La contraseña debe tener al menos 8 caracteres');
						} else if (value !== registerForm.password) {
							return Promise.reject("Las contraseñas no coinciden");
						} else {
							return Promise.resolve();
						}
					},
					trigger: 'change',
				},
			],
	};
	// 3. State (ref, reactive)
	const fileList = ref([]);
	const formRef = ref(null);
    const previewVisible = ref(false);
    const previewImage = ref('');
    const loadingAvatar = ref(false);
	// 4. Computed Properties
	// 5. Methods & Logic (Functions, Handlers)
	const handleLogin = () => {
		const url = route('login');
		router.visit(url);
	};
	const handleSubmit = async () => {
		// 1. Limpieza de seguridad: Si no hay form, no hacemos nada
		if (!formRef.value) {
			console.error("Error: formRef no está vinculado al componente.");
			return;
		}

		try {
			// 2. Ejecutar validación con un bloque catch interno para el outOfDate
			const values = await formRef.value.validate().catch((err) => {
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
				onSuccess: () => { message.success('Registro completado'); },
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

	const handleBeforeUpload = async (file) => {
		// 1. Generar la previsualización local para que se vea en el componente
		//file.preview = await getBase64(file.originFileObj || file);
		const base64 = await getBase64(file);
		const fileForList = {
			uid: file.uid || Date.now().toString(), // ID único
			name: file.name,
			status: 'done', // ESTO activa el ojito
			url: base64,    // Esto permite la miniatura
			originFileObj: file, // Guardamos el archivo real para el envío a Laravel
		};
		// 2. Actualizar la lista visual y el formulario
		fileList.value = [fileForList];
		registerForm.profile_photo_path = file.originFileObj || file;

		return false; // Evita subida automática
	};

	const handlePreview = async (file) => {
		if (!file.url && !file.preview) {
			alert(1)
			file.preview = await getBase64(file.originFileObj);
		}
		console.log(formRef.value);
		previewImage.value = file.url || file.preview;
		previewVisible.value = true;
		formRef.value.previewTitle = file.name || file.url?.substring(file.url.lastIndexOf('/') + 1) || "Previsualización";
	};

	// Función para cuando el usuario borra la foto (clic en la papelera)
	const handleRemove = () => {
		registerForm.profile_photo_path = null;
		fileList.value = [];
	};

	const handleCancel = () => {
		if (formRef.value) {
			previewVisible.value = false;
		}
	};

	// 6. Watchers
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
	// 7. Lifecycle Hooks (onMounted, etc.)
	// 8. Expose (defineExpose)
</script>

<template>
  <AuthLayout>
    <template #content>
      <a-row
        justify="center"
        :wrap="true"
      >
        <a-col
          :span="24"
          class="p-2 text-center"
        ></a-col>
        <a-col
          class="p-4 p-sm-4 p-md-4 p-lg-4 p-xl-4 p-xxl-4"
          :span="24"
        >
          <a-card class="fade-in">
            <AvatarCompany />

            <Header title="Nuevo Paciente" />

            <RegisterForm 
				ref="formRef"
				:registerForm="registerForm" 
				:rulesForm="rulesForm"
				:loadingAvatar="loadingAvatar"
				:fileList="fileList"
				@handleSubmit="handleSubmit"
				@handleBeforeUpload="handleBeforeUpload"
				@handlePreview="handlePreview"
				@handleCancel="handleCancel"
				@handleRemove="handleRemove"
				:previewVisible="previewVisible"
			/>
            <!-- <RegisterForm 
				ref="formRefChild"
				:registerForm="registerForm" 
				:rulesForm="rulesForm"
				@handleSubmit="handleSubmit"
				:gender="gender"
				v-model:fileList="fileList"
			
			/> -->
            
            <div class="text-center">
              <a-button
                type="link"
                @click="handleLogin"
              >
                Ya estoy registrado
              </a-button>
            </div>
          </a-card>
        </a-col>
      </a-row>
      <a-row>
        <a-col
          :span="24"
          class="text-center"
        >
          <RegisterTour />
        </a-col>
      </a-row>
    </template>
  </AuthLayout>
</template>
<style scoped></style>
