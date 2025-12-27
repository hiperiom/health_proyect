<script setup>
    defineProps({
    title: {
        type: String,
    },
    subTitle: {
        type: String,
    },
});
</script>
<template>
    <a-typography-title class="fs-3 text-center text-white">
        {{ title }}
    </a-typography-title>
    <a-typography-title class="fs-6 fw-normal text-white text-center" >
        {{ subTitle }}
    </a-typography-title>
</template>

<style  scoped>

</style>