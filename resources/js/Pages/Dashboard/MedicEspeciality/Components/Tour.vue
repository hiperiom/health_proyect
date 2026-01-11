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
    const { can_read } = props.config.user_permissions;
    // 3. State (ref, reactive)
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleHelp = () => alert("Help");

    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
    <a-button 
        :disabled="!can_read"
        :title="can_read ? 'Ayuda' : 'Sin permisos para consultar'"
        :icon="h(QuestionCircleOutlined)" 
        @click="handleHelp" 
    />
</template>
<style lang="css" scoped>
</style>
