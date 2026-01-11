    <script>
    export default {
      name: "CreateItem",
    }
</script>
    <script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h, onUnmounted, ref,watch } from 'vue';
    import { PlusOutlined } from '@ant-design/icons-vue';

    import Modal from '@/Components/Modal.vue';
    import Spinner from '@/Components/Spinner.vue';
    import AvatarUpload from '@/Pages/Auth/Components/AvatarUpload.vue';

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
    const avatarUploadRef = ref(null);

    const {
      form,
      formRef,
      handleSubmit
    } = useCreate(modalOpen, props.config);

    // Usar reglas de validación de la configuración
    const rulesForm = props.config.validationRules.create(form);

    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleModal = () => {
      form.reset();
      form.clearErrors();
      modalOpen.value = true;
    };
    const handleCancelForm = () => {
      form.reset();
      form.clearErrors();
      modalOpen.value = false;
    };
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    onUnmounted(() => {
      form.reset();
      form.clearErrors();
    });
    // 8. Expose (defineExpose)

const text = `A dog is a type of domesticated animal.Known for its loyalty and faithfulness,it can be found as a welcome guest in many households across the world.`;
const activeKey = ref(['1']);
watch(activeKey, val => {
  console.log(val);
});
</script>
<template>
    <a-button :icon="h(PlusOutlined)" type="primary" @click="handleModal(true)">
        Nuevo {{ config.modelTitleSingular }}
    </a-button>
    <Modal :title="'Crear ' + config.modelTitleSingular" :openModal="modalOpen" @handleCancelForm="handleCancelForm">
        <template #content>
            <Spinner :loading="form.processing">

                <div class="d-flex align-items-center h-100">
                    <a-form ref="formRef" layout="vertical" :model="form" :rules="rulesForm"
                        @submit.prevent="handleSubmit">

                        <a-collapse v-model:activeKey="activeKey" ghost>
                            <a-collapse-panel key="1" class="text-primary" header="Información Personal">
                                <a-row id="tour-identity" justify="center" :gutter="10" :wrap="true">
                                    <a-col span="24">
                                        <a-form-item :name="'avatar'" :ref="'avatar'" has-feedback
                                            :label="'Foto de Perfil'">
                                            <AvatarUpload ref="avatarUploadRef" v-model:value="form.avatar"
                                                :loading="form.processing" />
                                        </a-form-item>
                                    </a-col>
                                    <a-col v-for="(fieldConfig, fieldName) in config.formFields" :key="fieldName"
                                        :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
                                        <a-form-item :name="fieldName" :ref="fieldName" has-feedback
                                            :label="fieldConfig.label">

                                            <a-input v-if="fieldConfig.type === 'input'" :name="fieldName"
                                                :maxlength="fieldConfig.maxlength" v-model:value="form[fieldName]"
                                                :placeholder="fieldConfig.placeholder" />

                                            <a-select v-else-if="fieldConfig.type === 'select'" :name="fieldName"
                                                :placeholder="fieldConfig.placeholder" v-model:value="form[fieldName]"
                                                :options="fieldConfig.options" style="width: 100%" />

                                            <a-date-picker v-else-if="fieldConfig.type === 'date'"
                                                :value-format="fieldConfig.valueFormat" :format="fieldConfig.format"
                                                v-model:value="form[fieldName]" style="width: 100%"
                                                :placeholder="fieldConfig.placeholder" />
                                        </a-form-item>
                                    </a-col>
                                </a-row>
                            </a-collapse-panel>
                            <a-collapse-panel key="2" header="Especialidades">
                                <p>{{ text }}</p>
                            </a-collapse-panel>
                            <a-collapse-panel key="3" header="Estudios"><!-- collapsible="disabled" -->
                                <p>{{ text }}</p>
                            </a-collapse-panel>
                        </a-collapse>
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


<style lang="scss" scoped></style>
