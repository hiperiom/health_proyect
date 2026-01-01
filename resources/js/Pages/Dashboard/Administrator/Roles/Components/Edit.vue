<script>
    export default {
        name: "EditRole",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h, ref } from 'vue';
    import { EditOutlined } from '@ant-design/icons-vue';
    import Drawer from '@/Components/Drawer.vue';
    // 2. Props & Emits (defineProps, defineEmits)
    // 3. State (ref, reactive)
    const drawerOpen= ref(false);

    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleDrawer = (val) => {
       drawerOpen.value = val;
    };
    const handleSubmit = () => {
    };
    const handleCancelForm = () => {
    };
    // 6. Watchers
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
    title="Editar Rol"
    :drawerOpen="drawerOpen"
    @handleDrawer="handleDrawer"
  >
    <template #header>
      hola
    </template>
    <template #content>
      hola 2
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