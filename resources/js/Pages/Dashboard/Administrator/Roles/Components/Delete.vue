<script>
    export default {
        name: "DeleteRole",
    }
</script>

<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import {  
        DeleteOutlined,
    } from '@ant-design/icons-vue';
    // 2. Props & Emits (defineProps, defineEmits)
    // 3. State (ref, reactive)
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleDelete = e => {
        console.log(e);
        message.success('Click on Yes');
    };
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
    <a-popconfirm
        placement="bottomRight"
        title="¿Quieres eliminar el registro?"
        ok-text="Si"
        cancel-text="No"
        @confirm="handleDelete"
    >
        <a href="#"><DeleteOutlined /></a>
    </a-popconfirm>
</template>
<style lang="css" scoped>

</style>