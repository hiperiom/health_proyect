<script setup>
import { defineProps } from 'vue';

// Define las propiedades (props) para controlar la visibilidad
const props = defineProps({
  // Por defecto, muestra el header
  showHeader: {
    type: Boolean,
    default: true, 
  },
  // Por defecto, muestra el content
  showContent: {
    type: Boolean,
    default: true,
  },
  // Por defecto, muestra el footer
  showFooter: {
    type: Boolean,
    default: true,
  },
});
const headerStyle = {
    textAlign: 'center',
    color: 'var(--ant-primary-color)',
    height: 64,
    paddingInline: 50,
    lineHeight: '64px',
    /* backgroundColor: '#7dbcea', */
};

const contentStyle = {
    textAlign: 'center',
    //minHeight: 120,
    //lineHeight: '120px',
    color: 'var(--ant-primary-color)',
    /* backgroundColor: '#108ee9', */
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'center',
    /* alignItems: 'center', */
};

const footerStyle = {
    textAlign: 'center',
    color: 'var(--ant-primary-color)',
    /* backgroundColor: '#7dbcea', */
};
</script>
<template>
    <a-layout class="h-dvh-100-overflow-y-auto">
        <a-layout-header v-if="showHeader" :style="headerStyle">
            <slot name="header" />
        </a-layout-header>
        <a-layout-content class="overflow-y-auto" v-if="showContent" :style="contentStyle">
            <slot name="content" />
        </a-layout-content>
        <a-layout-footer v-if="showFooter" :style="footerStyle">
            <slot name="footer" />
        </a-layout-footer>
    </a-layout>
</template>
<style lang="css" scoped>

</style>
