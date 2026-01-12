    <script>
        export default {
            name: "EditItem",
        }
    </script>
    <script setup>
        // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
        import { h, ref, watch } from 'vue';
        import { EditOutlined } from '@ant-design/icons-vue';
        import AvatarUpload from '@/Pages/Auth/Components/AvatarUpload.vue';
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

        // Usar reglas de validación de la configuración
        const rulesForm = props.config.validationRules.edit(form);

    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleModal = () => {
        // Populate form with props.item data
        if (props.item) {
            Object.keys(props.config.formFields).forEach(fieldName => {
                if (fieldName === 'first_names' || fieldName === 'last_names' || fieldName === 'birthday' || fieldName === 'gender') {
                    form[fieldName] = props.item.profile ? props.item.profile[fieldName] || '' : '';
                } else {
                    form[fieldName] = props.item[fieldName] || '';
                }
            });
            // Handle avatar
            if (props.item.avatar) {
                form.avatar = props.item.avatar;
            } else if (props.item.profile_photo_url) {
                form.avatar = props.item.profile_photo_url;
            }
        }
        modalOpen.value = true;
    };
    const handleCancelForm = () => {
       form.reset();
       form.clearErrors();
       modalOpen.value = false;
    };

    // 6. Watchers
    watch(() => props.item, (newItem) => {
        if (newItem && modalOpen.value) {
            // Poblar el formulario dinámicamente con los datos del item si el modal está abierto
            Object.keys(props.config.formFields).forEach(fieldName => {
                if (fieldName === 'first_names' || fieldName === 'last_names' || fieldName === 'birthday' || fieldName === 'gender') {
                    form[fieldName] = newItem.profile ? newItem.profile[fieldName] || '' : '';
                } else {
                    form[fieldName] = newItem[fieldName] || '';
                }
            });
            // Handle avatar
            if (newItem.avatar) {
                form.avatar = newItem.avatar;
            } else if (newItem.profile_photo_url) {
                form.avatar = newItem.profile_photo_url;
            }
        }
    });

    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
  <a-button
    :title="'Editar ' + config.modelTitleSingular"
    :disabled="item.id === config.auth_user_id"
    type="link"
    @click="handleModal"
  >
      <EditOutlined />
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
                <a-col span="24" >
                  <a-form-item :name="'avatar'" :ref="'avatar'" has-feedback :label="'Foto de Perfil'">
                    <AvatarUpload
                      v-model:value="form.avatar"
                      :loading="form.processing"
                    />
                  </a-form-item>
                </a-col>
                <a-col v-for="(fieldConfig, fieldName) in config.formFields" :key="fieldName" :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
                  <a-form-item v-if="fieldConfig.type !== 'avatar'" :name="fieldName" :ref="fieldName" has-feedback :label="fieldConfig.label">
                    <!-- Input text -->
                    <a-input
                      v-if="fieldConfig.type === 'input'"
                      :name="fieldName"
                      :maxlength="fieldConfig.maxlength"
                      v-model:value="form[fieldName]"
                      :placeholder="fieldConfig.placeholder"
                    />
                    <!-- Select -->
                    <a-select
                      v-else-if="fieldConfig.type === 'select'"
                      :name="fieldName"
                      :placeholder="fieldConfig.placeholder"
                      v-model:value="form[fieldName]"
                      :options="fieldConfig.options"
                      style="width: 100%"
                    />
                    <!-- Date picker -->
                    <a-date-picker
                      v-else-if="fieldConfig.type === 'date'"
                      :value-format="fieldConfig.valueFormat"
                      :format="fieldConfig.format"
                      v-model:value="form[fieldName]"
                      style="width: 100%"
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
