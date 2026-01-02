<script>
    export default {
        name: "DeleteItem",
    }
</script>

<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { message } from 'ant-design-vue';
    import axios from 'axios';
    import {
        DeleteOutlined,
    } from '@ant-design/icons-vue';

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
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleDelete = async () => {
        try {
            await axios.delete(route(modelName.toLowerCase() +'.destroy', props.item.id));
            message.success('Registro eliminado exitosamente.');
        } catch (error) {
            const msg = error.response?.data?.message || 'Error al eliminar el registro.';
            message.error(msg);
        }
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
