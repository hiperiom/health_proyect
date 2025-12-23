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
    <a-typography-title class="fs-3 text-center text-primary">
        {{ title }}
    </a-typography-title>
    <a-typography-title class="fs-6 fs-sm-5 fs-md-5 fs-lg-5 text-center" >
        {{ subTitle }}
    </a-typography-title>
</template>

<style  scoped>

</style>