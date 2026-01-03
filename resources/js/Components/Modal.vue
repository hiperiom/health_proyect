<script>
    export default {
        name: "Modal",
    }
</script>

<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    // 2. Props & Emits (defineProps, defineEmits)
    const props = defineProps({
        openModal: {
            type: Boolean,
            required: false,
        },
        title: {
            type: String,
            required: false,
        },
    });
    // 3. State (ref, reactive)
    const emit = defineEmits(['handleCancelForm']);
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleCancelForm = () => {
        emit('handleCancelForm');
    };
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
    
        
    <a-modal 
        :confirmLoading="true"
        :open="openModal"
        :title="title"
        @cancel="handleCancelForm"
   
    >
        <slot name="content" />
        <template #footer>
            <slot name="footer" />
        </template>
    </a-modal>
   
</template>
<style lang="css" scoped>

</style>