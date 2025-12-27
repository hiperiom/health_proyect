<script>
    export default {
        name: "Drawer",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    // 2. Props & Emits (defineProps, defineEmits)
    defineProps({
        title: { type: String, required: true },
        width: { type: Number, default: 720 },
        drawerOpen: { type: Boolean, required: true },
    });
    const emit = defineEmits(['handleDrawer']);
    // 3. State (ref, reactive)
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleDrawer = (val) => {
        emit('handleDrawer',val)
    };
    
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
  <a-drawer
    :title="title"
    :width="width"
    :open="drawerOpen"
    :body-style="{ paddingBottom: '80px' }"
    :footer-style="{ textAlign: 'right' }"
    @close="handleDrawer(false)"
  >
    <slot name="header" />
    <slot name="content" />
    <template #footer>
      <slot name="footer" />
    </template>
  </a-drawer>
</template>


<style lang="scss" scoped>

</style>