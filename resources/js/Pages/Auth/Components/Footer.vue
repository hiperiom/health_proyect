<script setup>
    const year = (new Date()).getFullYear();
    const version = '0.0.1';
    const company = 'Hiperiom412 C.A.';
</script>
<template>
    <a-typography-title class="py-2 fs-6 text-white" >
        {{ company + '©' + year+ ' v' + version }}
    </a-typography-title>
</template>

<style  scoped>

</style>