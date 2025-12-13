

<script setup>
import { ref } from 'vue';
import { message } from 'ant-design-vue'; 
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const goTo = () => {
  // Usar el servicio de mensajes de Ant Design Vue
  message.success('¡Hiciste clic en el botón!');
 const url = route('example2'); 
  
  // 2. Usar Inertia para visitar la URL (navegación SPA)
  router.visit(url);
}
</script>
<template>
  <div class="p-6">
    <h1>Example 1</h1>
    <a-button type="primary">
      <Link :href="route('example2')">
        Example 2 con Tag a
      </Link>
    </a-button>
    <a-button type="primary" @click="goTo">
      Example 2 con evento click
    </a-button>
    <!-- <Link 
      :href="route('example2')" 
      class="ant-typography-link" 
      :style="{ color: 'var(--ant-primary-color)' }"
    >
      Dashboard (Inertia Link)
    </Link>
    <a-typography-link href="/example2" target="_blank">
      Ant Design Vue (Link)
    </a-typography-link> -->
  </div>
</template>
<style scoped>
    /* Puedes añadir estilos específicos si es necesario */
</style>