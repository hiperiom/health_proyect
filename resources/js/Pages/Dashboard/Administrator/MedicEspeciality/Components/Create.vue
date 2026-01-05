    <script>
        const modelTitle = "Especialidad";
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
        import { getCreateRules } from '../Utils/createRules';

        // 2. Props & Emits (defineProps, defineEmits)
        const props = defineProps({
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
        } = useCreate(modalOpen,props.modelName);

        const rulesForm = getCreateRules(form);

        // 4. Computed Properties
        // 5. Methods & Logic (Functions, Handlers)
        const handleModal = () => {
            form.reset();
            modalOpen.value = true;
        };
        const handleCancelForm = () => {
        form.reset();
        //drawerOpen.value = false;
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
        type="primary" 
        @click="handleModal(true)"
    >
        Nueva {{ modelTitle }}
    </a-button>
    <Modal
        :title="'Crear ' + modelTitle"
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
                <a-button type="primary" @click="handleSubmit()">Registrar</a-button>
            </a-space>
        </template>
    </Modal>
    </template>
    <style lang="scss" scoped>
    </style>