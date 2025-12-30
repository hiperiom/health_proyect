<script>
    export default {
        name: "GenericTable",
    }
</script>

<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    
    // 2. Props & Emits (defineProps, defineEmits)
    defineProps({
        columns: { type: Array, required: true },
        dataSource: { type: Array, required: true },
        loading: { type: Boolean, default: false },
        pagination: { type: Object,  required: true },
        rowKey: { type: String, default: 'id' }
    });
    
    const emit = defineEmits(['handleChange']);

    // 3. State (ref, reactive)
    
    // 4. Computed Properties
    
    // 5. Methods & Logic (Functions, Handlers)
    const handleChange = (pag, filters, sorter) => {
        emit('handleChange', pag, filters, sorter);
    };
    
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>

<template>
    <a-table
        class="table-no-style"
        :bordered="false"
        :columns="columns"
        :data-source="dataSource"
        :pagination="pagination"
        :loading="loading"
        :row-key="rowKey"
        @change="handleChange"
    >
        <template #bodyCell="{ column, record, index }">
            <slot name="bodyCell" :column="column" :record="record" :index="index">
                <!-- Renderizado por defecto si no hay slot -->
            </slot>
        </template>
    </a-table>
</template>

<style>
/* Elimina el fondo blanco del contenedor principal */



</style>
