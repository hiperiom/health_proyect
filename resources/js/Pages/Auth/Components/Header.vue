<script setup>
    defineProps({
    title: {
        type: String,
        default: "Title",
    },
    subTitle: {
        type: String,
        default: "SubTitle",
    },
});
</script>
<template>
    <a-typography-title class="fs-3">
        {{ title }}
    </a-typography-title>
    <a-typography-title class="fs-6 fs-sm-5 fs-md-5 fs-lg-5 text-secondary" >
        {{ subTitle }}
    </a-typography-title>
</template>

<style  scoped>

</style>