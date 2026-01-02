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
       
        :icon="h(QuestionCircleOutlined)" 
        @click="handleHelp" 
    />
</template>
<style lang="css" scoped>

</style>