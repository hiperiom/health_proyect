<script>
    export default {
        name: "CreateItem",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h, onUnmounted, ref } from 'vue';
    import { PlusOutlined } from '@ant-design/icons-vue';
    import Drawer from '@/Components/Drawer.vue';
    import { useCreate } from '../Composables/useCreate';
import Spinner from '@/Components/Spinner.vue';
import Modal from '@/Components/Modal.vue';

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
    type="primary"
    @click="handleModal(true)"
  >
      Nuevo {{ config.modelTitleSingular }}
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
                    <!-- Input text -->
                    <a-input
                      v-if="fieldConfig.type === 'input'"
                      :name="fieldName"
                      :maxlength="fieldConfig.maxlength"
                      v-model:value="form[fieldName]"
                      :placeholder="fieldConfig.placeholder"
                    />
                    <!-- Textarea -->
                    <a-textarea
                      v-else-if="fieldConfig.type === 'textarea'"
                      :name="fieldName"
                      v-model:value="form[fieldName]"
                      :placeholder="fieldConfig.placeholder"
                      :rows="4"
                    />
                  </a-form-item>
                </a-col>

                <!-- Campos adicionales para creación (color) -->
                <a-col v-if="config.createOnlyFields && config.createOnlyFields.color" :span="24">
                  <a-form-item name="color" label="Color">
                    <a-select v-model:value="form.color">
                      <a-select-option value="blue">Azul</a-select-option>
                      <a-select-option value="red">Rojo</a-select-option>
                      <a-select-option value="green">Verde</a-select-option>
                    </a-select>
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
