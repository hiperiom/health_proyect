<script>
    export default {
        name: "TourItem",
    }
</script>

<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h } from 'vue';
    import {  QuestionCircleOutlined,} from '@ant-design/icons-vue';

    // 2. Props & Emits (defineProps, defineEmits)
    const props = defineProps({
        config: {
            type: Object,
            required: true,
        },
    });

    // 3. State (ref, reactive)
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleHelp = () => alert("Ayuda para " + props.config.modelTitle);

    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
    <a-button
        :disabled="!config.user_permissions.can_create"
        :title="config.user_permissions.can_create ? 'Ayuda para ' + config.modelTitle : 'Sin permisos para ver ayuda'"
        :icon="h(QuestionCircleOutlined)"
        @click="handleHelp"
    />
</template>
<style lang="css" scoped>

</style>
