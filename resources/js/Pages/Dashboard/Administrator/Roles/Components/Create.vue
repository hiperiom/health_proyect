<script>
    export default {
        name: "CreateRole",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import Drawer from '@/Components/Drawer.vue';
    // 2. Props & Emits (defineProps, defineEmits)
    defineProps({
        drawerOpen: { type: Boolean, required: true },
    });
    const emit = defineEmits(['handleDrawer','handleSubmit','handleCancelForm']);
    // 3. State (ref, reactive)
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleDrawer = (val) => {
        emit('handleDrawer',val)
    };
    const handleSubmit = () => {
        emit('handleSubmit');
    };
    const handleCancelForm = () => {
        emit('handleCancelForm');
    };
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
  <Drawer
    title="Roles"
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
        <a-button type="primary" @click="handleSubmit()">Registrar</a-button>
      </a-space>
    </template>
  </Drawer>
</template>


<style lang="scss" scoped>

</style>