    <script>
        const modelTitle = "Usuarios";
        const modelTitleSingular = "Usuario";
        const modelName = "Users";
        const modelNameKebabCase = "users";
        const modelNameRoutes = "users";
        export default {
            name: "EditSegurity",
        }
    </script>
    <script setup>
        // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
        import { usePage } from '@inertiajs/vue3';
        import Modal from '@/Components/Modal.vue';
        import Spinner from '@/Components/Spinner.vue';
        import { useEditSegurity } from './Composables/useEditSegurity.js';
        
        // 2. Props & Emits (defineProps, defineEmits)
        const props = defineProps({
            editSegurityModal: {
                type: Boolean,
                required: true,
            },
            user_id: {
                type: Number,
                required: true,
            },
        });
        const emits = defineEmits(['handleOpenModalEditSegurity']);
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
                
                current_password: {
                    label: 'Contraseña Actual',
                    placeholder: 'Escribe aquí tu contraseña actual',
                    maxlength: 8,
                    required: true,
                    type: 'password'
                },
                
                password: {
                    label: 'Nueva Contraseña',
                    placeholder: 'Escribe aquí tu nueva contraseña',
                    maxlength: 8,
                    required: true,
                    type: 'password'
                },
                password_confirmation: {                    
                    label: 'Confirmar Contraseña',
                    placeholder: 'Confirma tu contraseña',
                    maxlength: 8,
                    required: true,
                    type: 'password'
                },
               
            },
            // Reglas de validación
            validationRules: {
               edit: (form) => ({
                    current_password: [
                        { required: true, message: 'La contraseña actual es obligatoria' },
                        { min: 8, message: 'La contraseña actual debe tener al menos 8 caracteres' }
                    ],
                    password: [
                        { required: true, message: 'La contraseña es obligatoria' },
                        { min: 8, message: 'La contraseña debe tener al menos 8 caracteres' }
                    ],
                    password_confirmation: [
                        { required: true, message: 'La confirmación de contraseña es obligatoria' },
                        ({ getFieldValue }) => ({
                            validator(_, value) {
                                if (!value || getFieldValue('password') === value) {
                                    return Promise.resolve();
                                }
                                return Promise.reject(new Error('Las contraseñas no coinciden'));
                            },
                        }),
                    ]
                }),
                   
            },
            
        };
        // 3. State (ref, reactive)
        const {
            form,
            formRef,
            handleSubmit: originalHandleSubmit,
            fetchRecords
        } = useEditSegurity(props.user_id, emits, config);

        const rulesForm = config.validationRules.edit(form);


        // 4. Computed Properties
        // 5. Methods & Logic (Functions, Handlers)

        const handleCancelForm = () => {
            emits('handleOpenModalEditSegurity', false);
        };
        const handleSubmit = async () => {
            await originalHandleSubmit();
        };
        // 6. Watchers
        
        // 7. Lifecycle Hooks (onMounted, etc.)
      
           
        // 8. Expose (defineExpose)
    </script>
    <template>
        <Modal 
            :title="'Seguridad '" 
            :openModal="editSegurityModal" 
            @handleCancelForm="handleCancelForm"
        >
            <template #content>
                <Spinner :loading="form.processing">
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <a-form ref="formRef" layout="vertical" :model="form" :rules="rulesForm"
                            @submit.prevent="handleSubmit">
                            <a-row id="tour-identity" justify="center" :gutter="10" :wrap="true">
                             
                                <a-col v-for="(fieldConfig, fieldName) in config.formFields" :key="fieldName" :span="24">
                                    <a-form-item v-if="fieldConfig.type !== 'avatar'" :name="fieldName" :ref="fieldName"
                                        has-feedback :label="fieldConfig.label">
                                         
                                        <a-input-password 
                                            v-if="fieldConfig.type === 'password'" 
                                            :name="fieldName"
                                            :maxlength="fieldConfig.maxlength" v-model:value="form[fieldName]"
                                            :placeholder="fieldConfig.placeholder"
                                        />
                                       
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