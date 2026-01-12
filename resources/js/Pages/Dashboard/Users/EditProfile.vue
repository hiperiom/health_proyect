    <script>
        const modelTitle = "Usuarios";
        const modelTitleSingular = "Usuario";
        const modelName = "Users";
        const modelNameKebabCase = "users";
        const modelNameRoutes = "users";
        export default {
            name: "EditProfile",
        }
    </script>
    <script setup>
        // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
        import { watch } from 'vue';
        import { usePage } from '@inertiajs/vue3';
        import Modal from '@/Components/Modal.vue';
        import Spinner from '@/Components/Spinner.vue';
        import { useEditProfile } from './Composables/useEditProfile.js';
        import AvatarUpload from '@/Pages/Auth/Components/AvatarUpload.vue';
        
        // 2. Props & Emits (defineProps, defineEmits)
        const props = defineProps({
            editProfileModal: {
                type: Boolean,
                required: true,
            },
            user_id: {
                type: Number,
                required: true,
            },
        });
        const emits = defineEmits(['handleOpenModalEditProfile']);
        const page = usePage();

        const config = {
            // Información básica del modelo
            modelName,
            modelNameKebabCase,
            modelTitle,
            modelTitleSingular,
            modelNameRoutes,

            // Campos del formulario
            formFields: {
                
                dni: {
                    label: 'Cédula',
                    placeholder: 'Escribe aquí tu cédula',
                    maxlength: 8,
                    required: true,
                    type: 'input'
                },
                email: {
                    label: 'Correo electrónico',
                    placeholder: 'Escribe aquí tu correo electrónico',
                    required: true,
                    type: 'input'
                },
                first_names: {
                    label: 'Nombres',
                    placeholder: 'Escribe tu primer y segundo nombre',
                    maxlength: 50,
                    required: true,
                    type: 'input'
                },
                last_names: {
                    label: 'Apellidos',
                    placeholder: 'Escribe tu primer y segundo apellido',
                    maxlength: 50,
                    required: true,
                    type: 'input'
                },
                gender: {
                    label: 'Género',
                    placeholder: 'Selecciona el sexo',
                    required: true,
                    type: 'select',
                    options: [
                        { value: 'm', label: 'Masculino' },
                        { value: 'f', label: 'Femenino' }
                    ]
                },
                birthday: {
                    label: 'Fecha de nacimiento',
                    placeholder: 'Escribe tu fecha de nacimiento dia/mes/año',
                    required: true,
                    type: 'date',
                    format: 'DD/MM/YYYY',
                    valueFormat: 'YYYY-MM-DD'
                }
            },
            // Reglas de validación
            validationRules: {
               edit: (form) => ({
                    dni: [
                        { required: true, message: 'La cédula es obligatoria' },
                        { pattern: /^\d{6,8}$/, message: 'La cédula debe tener 7-8 dígitos' }
                    ],
                    email: [
                        { required: true, message: 'El correo es obligatorio' },
                        { type: 'email', message: 'Ingresa un correo válido' }
                    ],
                    first_names: [
                        { required: true, message: 'Los nombres son obligatorios' },
                        { max: 50, message: 'Los nombres no pueden tener más de 50 caracteres' }
                    ],
                    last_names: [
                        { required: true, message: 'Los apellidos son obligatorios' },
                        { max: 50, message: 'Los apellidos no pueden tener más de 50 caracteres' }
                    ],
                    gender: [
                        { required: true, message: 'El género es obligatorio' }
                    ],
                    birthday: [
                        { required: true, message: 'La fecha de nacimiento es obligatoria' }
                    ],
                    avatar: [
                        {
                            validator: async (_rule, value) => {
                                
                                const file = value;
                                if (!file) {
                                    return Promise.resolve();
                                }
                                const isJPG = file.type === 'image/jpeg';
                                const isPNG = file.type === 'image/png';
                                if (!isJPG && !isPNG) {
                                    return Promise.reject('El formato de la foto debe ser JPG o PNG');
                                }
                                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2); 
                                console.log(`La foto pesa: ${sizeInMB} MB`);
                                
                                if (file.size > 2 * 1024 * 1024) {
                                    return Promise.reject('El tamaño de la foto debe ser menor a 2 MB');
                                }
                                return Promise.resolve();
                            },
                        },
                    ],
                    
                })
            },
            
        };
        // 3. State (ref, reactive)
        const {
            form,
            formRef,
            handleSubmit: originalHandleSubmit,
            fetchRecords
        } = useEditProfile(props.user_id, emits, config);

        const rulesForm = config.validationRules.edit(form);


        // 4. Computed Properties
        // 5. Methods & Logic (Functions, Handlers)

        const handleCancelForm = () => {
            emits('handleOpenModalEditProfile', false);
        };
        const handleSubmit = async () => {
            await originalHandleSubmit();
        };
        // 6. Watchers
        watch(() => props.editProfileModal, (newVal) => {
            if (newVal) {
                fetchRecords();
            }
        });
        // 7. Lifecycle Hooks (onMounted, etc.)
      
           
        // 8. Expose (defineExpose)
    </script>
    <template>
        <Modal 
            :title="'Información personal '" 
            :openModal="editProfileModal" 
            @handleCancelForm="handleCancelForm"
        >
            <template #content>
                <Spinner :loading="form.processing">
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <a-form ref="formRef" layout="vertical" :model="form" :rules="rulesForm"
                            @submit.prevent="handleSubmit">
                            <a-row id="tour-identity" justify="center" :gutter="10" :wrap="true">
                                <a-col span="24">
                                    <a-form-item :name="'avatar'" :ref="'avatar'" has-feedback >
                                        <AvatarUpload v-model:value="form.avatar" :loading="form.processing" />
                                    </a-form-item>
                                </a-col>
                                <a-col v-for="(fieldConfig, fieldName) in config.formFields" :key="fieldName" :xs="24"
                                    :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
                                    <a-form-item v-if="fieldConfig.type !== 'avatar'" :name="fieldName" :ref="fieldName"
                                        has-feedback :label="fieldConfig.label">
                                        <!-- Input text -->
                                        <a-input v-if="fieldConfig.type === 'input'" :name="fieldName"
                                            :maxlength="fieldConfig.maxlength" v-model:value="form[fieldName]"
                                            :placeholder="fieldConfig.placeholder" />
                                        <!-- Select -->
                                        <a-select v-else-if="fieldConfig.type === 'select'" :name="fieldName"
                                            :placeholder="fieldConfig.placeholder" v-model:value="form[fieldName]"
                                            :options="fieldConfig.options" style="width: 100%" />
                                        <!-- Date picker -->
                                        <a-date-picker v-else-if="fieldConfig.type === 'date'"
                                            :value-format="fieldConfig.valueFormat" :format="fieldConfig.format"
                                            v-model:value="form[fieldName]" style="width: 100%"
                                            :placeholder="fieldConfig.placeholder" />
                                    </a-form-item>
                                </a-col>
                            </a-row>
                        </a-form>
                    </div>
                </Spinner>
            </template>
            <template #footer>
                <a-space>
                    <a-button @click="handleCancelForm()">Cancelar</a-button>
                    <a-button type="primary" @click="handleSubmit()">Actualizar</a-button>
                </a-space>
            </template>
        </Modal>
    </template>
    <style lang="scss" scoped></style>