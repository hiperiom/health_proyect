<script>
    const modelTitle = "Usuario";
    export default {
        name: "CreateItem",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h, ref } from 'vue';
    import { PlusOutlined } from '@ant-design/icons-vue';
    import Drawer from '@/Components/Drawer.vue';
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
    const drawerOpen= ref(false);

    const { 
      form, 
      formRef, 
      handleSubmit 
    } = useCreate(drawerOpen,props.modelName);

    const rulesForm = getCreateRules(form);


    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleDrawer = (val) => {
        drawerOpen.value = val;
    };
  
    const handleCancelForm = () => {
       form.reset();
       drawerOpen.value = false;
    };
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
  <a-button 
      
      type="primary" 
      :icon="h(PlusOutlined)" 
      @click="handleDrawer(true)"
  >
      Nuevo {{ modelTitle }}
  </a-button>
  
  <Drawer
    :title="'Crear ' + modelTitle"
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
          <!-- <a-form-item name="name" ref="name" has-feedback label="Nombre del Usuario">
            <a-input name="name" :maxlength="20" v-model:value="form.name"
              placeholder="Escribe aquí..." />
          </a-form-item>
          <a-form-item name="color" ref="color" label="Color">
            <a-select v-model:value="form.color">
              <a-select-option  value="blue">Azul</a-select-option>
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
        <a-button type="primary" @click="handleSubmit()">Registrar</a-button>
      </a-space>
    </template>
  </Drawer>
</template>


<style lang="scss" scoped>

</style>