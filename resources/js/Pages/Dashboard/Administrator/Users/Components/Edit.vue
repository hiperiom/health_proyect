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
    import Drawer from '@/Components/Drawer.vue';
    import { useEdit } from '../Composables/useEdit';
    import { getEditRules } from '../Utils/editRules';

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
    const drawerOpen = ref(false);

    const {
        form,
        formRef,
        handleSubmit: originalHandleSubmit
    } = useEdit(props.item,drawerOpen,props.modelName);

    const rulesForm = getEditRules(form);


    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleDrawer = (val) => {
        drawerOpen.value = val;
    };

    const handleSubmit = async () => {
        await originalHandleSubmit();
        drawerOpen.value = false; // Close drawer after successful submit
    };

    const handleCancelForm = () => {
        // Reset to current role values
        //form.name = props.role.name || '';
        //form.color = props.role.color || 'blue';
        //form.guard_name = props.role.guard_name || 'web';
        //drawerOpen.value = false;
    };

    // 6. Watchers
    watch(() => props.item, (newItem) => {
        if (newItem) {
            //form.name = newRole.name || '';
            //form.color = newRole.color || 'blue';
            //form.guard_name = newRole.guard_name || 'web';
        }
    }, { immediate: true });

    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
    <a-button 
        type="link" 
        @click="handleDrawer(true)"
    >
        <EditOutlined />
    </a-button>
  
  <Drawer
    :title="'Editar ' + modelTitle"
    :drawerOpen="drawerOpen"
    @handleDrawer="handleDrawer"
  >
    <template #header>

    </template>
    <template #content>
      <div class="d-flex align-items-center justify-content-center h-100">
        <a-form
          ref="formRef"
          layout="vertical"
          :model="form"
          :rules="rulesForm"
          @submit.prevent="handleSubmit"
        >
          <!-- <a-form-item name="name" ref="name" has-feedback label="Nombre del Rol">
            <a-input name="name" :maxlength="20" v-model:value="form.name"
              placeholder="Escribe aquí..." />
          </a-form-item>
          <a-form-item name="color" ref="color" label="Color">
            <a-select v-model:value="form.color">
              <a-select-option value="blue">Azul</a-select-option>
              <a-select-option value="red">Rojo</a-select-option>
              <a-select-option value="green">Verde</a-select-option>
            </a-select>
          </a-form-item> -->
        </a-form>
      </div>
    </template>
    <template #footer>
      <a-space>
        <a-button @click="handleCancelForm()">Cancelar</a-button>
        <a-button type="primary" @click="handleSubmit()">Actualizar</a-button>
      </a-space>
    </template>
  </Drawer>
</template>


<style lang="scss" scoped>

</style>
