<script>
    export default {
        name: "Spinner",
    }
</script>

<script setup>

    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    // 2. Props & Emits (defineProps, defineEmits)
    defineProps({
        message: {
            type: String,
            default: 'Espere un momento...',
        },
        loading: {
            type: Boolean,
            default: false,
        },
        size: {
            type: String,
            default: 'large',
        }
    });
    //const emit = defineEmits(['handleSubmit']);
    // 3. State (ref, reactive)
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>
<template>
    <a-spin  
        wrapperClassName="ant-spin-custom"
        :tip="message" 
        :spinning="loading"
        :size="size"
    >
        <slot />
    </a-spin>
</template>
<style lang="css" >

    .ant-spin-custom >div>.ant-spin {
        position: unset;
    }
</style>