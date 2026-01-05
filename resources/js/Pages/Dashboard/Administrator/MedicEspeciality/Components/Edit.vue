    <script>
        const modelTitle = "Usuario";
        export default {
            name: "EditItem",
        }
    </script>
    <script setup>
        // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
        import { h, ref, watch } from 'vue';
        import { EditOutlined } from '@ant-design/icons-vue';
        import { useEdit } from '../Composables/useEdit';
        import { getEditRules } from '../Utils/editRules';
        import Spinner from '@/Components/Spinner.vue';
        import Modal from '@/Components/Modal.vue';

        // 2. Props & Emits (defineProps, defineEmits)
        const props = defineProps({
            item: {
                type: Object,
                required: true,
            },
            modelName: {
                type: String,
                required: true,
            },
        });

        // 3. State (ref, reactive)
        const modalOpen = ref(false);
        const {
            form,
            formRef,
            handleSubmit
        } = useEdit(props.item,modalOpen,props.modelName);

        const rulesForm = getEditRules(form);
        const genderOptions = [
            {
            value: 'm',
            label: 'Masculino',
            },
            {
            value: 'f',
            label: 'Femenino',
            },
        ];

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
                form.first_names = newItem.profile.first_names || '';
                form.last_names = newItem.profile.last_names || '';
                form.dni = newItem.dni || '';
                form.email = newItem.email || '';
                form.birthday = newItem.profile.birthday || '';
                form.gender = newItem.profile.gender || '';
            }
        }, { immediate: true });

        // 7. Lifecycle Hooks (onMounted, etc.)
        // 8. Expose (defineExpose)
    </script>

    <template>
        <a-button 
            type="link" 
            @click="handleModal(true)"
        >
            <EditOutlined />
        </a-button>
        <Modal
            :title="'Editar ' + modelTitle"
            :openModal="modalOpen"      
            @handleCancelForm="handleCancelForm"
        >
            <template #content>
                <Spinner :loading="form.processing" >
                <div class="d-flex align-items-center justify-content-center h-100">
                    <a-form ref="formRef" layout="vertical" :model="form" :rules="rulesForm" @submit.prevent="handleSubmit">
                        <a-row id="tour-identity" justify="center" :gutter="10" :wrap="true">
                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
                            <a-form-item name="dni" ref="dni" has-feedback label="Cédula">
                                <a-input name="dni" :maxlength="8" v-model:value="form.dni" placeholder="Escribe aquí tu cédula" />
                            </a-form-item>
                            </a-col>
                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
                            <a-form-item name="email" ref="email" has-feedback label="Correo electrónico">
                                <a-input name="email" v-model:value="form.email" placeholder="Escribe aquí tu correo electrónico" />
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