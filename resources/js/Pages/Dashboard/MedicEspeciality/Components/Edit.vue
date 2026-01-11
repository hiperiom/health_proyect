    <script>
        export default {
            name: "EditItem",
        }
    </script>
    <script setup>
        // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
        import { h, ref, watch } from 'vue';
        import { EditOutlined } from '@ant-design/icons-vue';
        import { useEdit } from '../Composables/useEdit';
        import Spinner from '@/Components/Spinner.vue';
        import Modal from '@/Components/Modal.vue';

        // 2. Props & Emits (defineProps, defineEmits)
        const props = defineProps({
            item: {
                type: Object,
                required: true,
            },
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
        } = useEdit(props.item, modalOpen, props.config);

        const { can_update } = props.config.user_permissions;

        // Usar reglas de validación de la configuración
        const rulesForm = props.config.validationRules.edit(form);

        // 4. Computed Properties
        // 5. Methods & Logic (Functions, Handlers)
        const handleModal = () => {
            modalOpen.value = true;
        };
        const handleCancelForm = () => {
            form.reset();
            modalOpen.value = false;
        };

        // 6. Watchers
        watch(() => props.item, (newItem) => {
            if (newItem) {
                // Poblar el formulario dinámicamente con los datos del item
                Object.keys(props.config.formFields).forEach(fieldName => {
                    form[fieldName] = newItem[fieldName] || '';
                });
            }
        }, { immediate: true });

        // 7. Lifecycle Hooks (onMounted, etc.)
        // 8. Expose (defineExpose)
    </script>

    <template>
        <a-button
            :icon="h(EditOutlined)"
            :type="can_update ? 'link' : 'disabled'"
            @click="can_update ? handleModal(true) : null"
            :title="can_update ? 'Editar ' + config.modelTitleSingular : 'No tienes permisos para editar'"
        >
        </a-button>
        <Modal
            :title="'Editar ' + config.modelTitleSingular"
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
                    <a-button type="primary" @click="handleSubmit()">Actualizar</a-button>
                </a-space>
            </template>
        </Modal>
    </template>
    <style lang="scss" scoped>
    </style>
