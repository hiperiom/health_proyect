    <script>
        export default {
            name: "CreateItem",
        }
    </script>
    <script setup>
        // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
        import { h, onUnmounted, ref } from 'vue';
        import { PlusOutlined } from '@ant-design/icons-vue';

        import Modal from '@/Components/Modal.vue';
        import Spinner from '@/Components/Spinner.vue';

        import { useCreate } from '../Composables/useCreate';

        // 2. Props & Emits (defineProps, defineEmits)
        const props = defineProps({
            config: {
                type: Object,
                required: true,
            },
        });

        // 3. State (ref, reactive)
        const modalOpen = ref(false);

        const {
            form,
            formRef,
            handleSubmit
        } = useCreate(modalOpen, props.config);

        const { can_create } = props.config.user_permissions;

        // Usar reglas de validación de la configuración
        const rulesForm = props.config.validationRules.create(form);

        // 4. Computed Properties
        // 5. Methods & Logic (Functions, Handlers)
        const handleModal = () => {
            form.reset();
            modalOpen.value = true;
        };
        const handleCancelForm = () => {
            form.reset();
            modalOpen.value = false;
        };
        // 6. Watchers
        // 7. Lifecycle Hooks (onMounted, etc.)
        onUnmounted(() => {
            form.reset();
        });
        // 8. Expose (defineExpose)
    </script>
    <template>
    <a-button
        :icon="h(PlusOutlined)"
        :type="can_create ? 'primary' : 'disabled'"
        @click="can_create ? handleModal(true) : null"
        :title="can_create ? 'Crear ' + config.modelTitleSingular : 'No tienes permisos para crear'"
    >
        Nueva {{ config.modelTitleSingular }}
    </a-button>

    <Modal
        :title="'Crear ' + config.modelTitleSingular"
        :openModal="modalOpen"
        @handleCancelForm="handleCancelForm"
    >
        <template #content>
            <Spinner :loading="form.processing" >
            <div class="d-flex align-items-center justify-content-center h-100">
                <a-form ref="formRef" layout="vertical" :model="form" :rules="rulesForm" @submit.prevent="handleSubmit">
                    <a-row id="tour-identity" justify="center" :gutter="10" :wrap="true">
                        <a-col v-for="(fieldConfig, fieldName) in config.formFields" :key="fieldName" :span="24">
                        <a-form-item :name="fieldName" :ref="fieldName" has-feedback :label="fieldConfig.label">
                            <a-input
                                :name="fieldName"
                                :maxlength="fieldConfig.maxlength"
                                v-model:value="form[fieldName]"
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
                <a-button type="primary" @click="handleSubmit()">Registrar</a-button>
            </a-space>
        </template>
    </Modal>
    </template>
    <style lang="scss" scoped>
    </style>
