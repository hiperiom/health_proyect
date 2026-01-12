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
        config: {
            type: Object,
            required: true,
        },
    });

    // 3. State (ref, reactive)
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleDelete = async () => {
        try {
            await axios.delete(route(props.config.modelNameKebabCase +'.destroy', props.item));
            message.success(props.config.modelTitleSingular + ' eliminado exitosamente.');
        } catch (error) {
            console.log(error);
            const msg = error.response?.data?.message || 'Error al eliminar el ' + props.config.modelTitleSingular.toLowerCase() + '.';
            message.error(msg);
        }
    };

    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
    <a-popconfirm
        :disabled="item.id === config.auth_user_id"
        placement="bottomRight"
        :title="'¿Quieres eliminar este ' + config.modelTitleSingular.toLowerCase() + '?'"
        ok-text="Si"
        cancel-text="No"
        @confirm="handleDelete"
    >
        <a 
            href="#" 
            :class="{ 'link-disabled': 
            item.id === config.auth_user_id }" 
            :title="'Eliminar ' + config.modelTitleSingular"
        >
            <DeleteOutlined />
        </a>
    </a-popconfirm>
</template>
<style lang="css" scoped>
    .link-disabled {
        color: rgba(0, 0, 0, 0.25) !important; /* Gris estándar de Ant Design para deshabilitados */
        cursor: not-allowed;
        pointer-events: none; /* Evita que el cursor cambie a "mano" */
    }
</style>
