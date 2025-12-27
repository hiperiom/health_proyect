<script>
    export default {
        name: "ListRole",
    }
</script>

<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    // 2. Props & Emits (defineProps, defineEmits)
    // 3. State (ref, reactive)
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
    import { computed } from 'vue';
import { usePagination } from 'vue-request';
import axios from 'axios';

const columns = [
    {
        title: 'Nombre',
        dataIndex: 'name', // JSONPlaceholder devuelve un string directo
        sorter: true,
    },
    {
        title: 'Email', // Cambiado de 'Description' para que coincida con la API de prueba
        dataIndex: 'email',
    },
    {
        title: 'Ciudad',
        dataIndex: ['address', 'city'], // Acceso a objetos anidados
    },
    {
        title: 'Acciones',
        dataIndex: 'actions',
        width: '20%',
    },
];

// Función para obtener los datos
const queryData = async (params) => {
    const res = await axios.get('https://jsonplaceholder.typicode.com/users', {
        params,
    });
    // Importante: JSONPlaceholder devuelve un array directo, no un objeto con .results
    return res.data; 
};

const {
    data: dataSource,
    run,
    loading,
    current,
    pageSize,
} = usePagination(queryData, {
    pagination: {
        currentKey: 'page',
        pageSizeKey: 'results',
    },
});

const pagination = computed(() => ({
    total: 10, // JSONPlaceholder solo tiene 10 usuarios
    current: current.value,
    pageSize: pageSize.value,
}));

const handleTableChange = (pag, filters, sorter) => {
    run({
        results: pag.pageSize,
        page: pag?.current,
        sortField: sorter.field,
        sortOrder: sorter.order,
        ...filters,
    });
};
</script>
<template>
<a-table
        :columns="columns"
        :row-key="record => record.id"
        :data-source="dataSource"
        :pagination="pagination"
        :loading="loading"
        @change="handleTableChange"
    >
        <template #bodyCell="{ column, record }">
            <template v-if="column.dataIndex === 'actions'">
                <a-button type="link">Editar</a-button>
            </template>
        </template>
    </a-table>
</template>
<style lang="css" scoped>

</style>