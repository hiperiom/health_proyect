<script setup>
    import { h, onMounted, ref } from 'vue';
    import Table from '@/Components/Table.vue';
    import { ReloadOutlined,MoreOutlined } from '@ant-design/icons-vue';
    import { useRolesList } from '../Composables/useRolesList';

    const props = defineProps({
        loading: { type: Boolean, default: false },
        fetchRoles: { type: Function, required: true },
        pagination: { type: Object, required: true },
    });

    // Inicializamos la lógica de la lista
    const { 
        dataSource, 
        searchText, 
        loadData, 
        handleTableChange, 
        handleSearch,
        handleRefresh
    } = useRolesList(props.fetchRoles, ref(props.pagination)); 
    // Nota: Usamos ref(props.pagination) para mantener la reactividad bidireccional

    const columns = [
        { title: 'Rol', dataIndex: 'name',  width: '20%' },
        { title: 'Descripción', dataIndex: 'description' },
        { title: 'Acciones', dataIndex: 'actions', width: '100px' },
    ];
    const handleMenuClick = e => {
        console.log('click', e);
    };

    onMounted(() => {
        loadData();
    });
    
</script>

<template>
    <a-card :head-style="{ border: 0 }" :body-style="{ padding: '1rem', overflow: 'auto' }">
        <template #extra>
            <a-space>
                <a-input-search v-model:value="searchText" placeholder="Buscar por..." @search="handleSearch" />
                <a-button :icon="h(ReloadOutlined)" @click="handleRefresh" :loading="loading" />
            </a-space>
        </template>

        <Table :columns="columns" :data-source="dataSource" :loading="loading" :pagination="pagination"
            @handleChange="handleTableChange">
            <template #bodyCell="{ column }">
                <template v-if="column.dataIndex === 'actions'">
                    <a-dropdown>
                        <template #overlay>
                            <a-menu @click="handleMenuClick">
                                <a-menu-item key="1">Editar</a-menu-item>
                                <a-menu-item key="2">Eliminar</a-menu-item>
                            </a-menu>
                        </template>
                        <a-button type="link" class="text-white">
                            <MoreOutlined />
                        </a-button>
                    </a-dropdown>
                 
                </template>
            </template>
        </Table>
    </a-card>
</template>
<style>
    .ant-card-bordered {
        border: none;
    }
    .ant-card {
        background: transparent;
    }
    .table-no-style.ant-table-wrapper .ant-table {
        /* color: var(--bs-white) !important; */
        background: transparent !important;
    }
    .table-no-style.ant-table-wrapper .ant-table-thead >tr>th, 
    .table-no-style.ant-table-wrapper .ant-table-thead >tr>td {
        /* color: var(--bs-white) !important; */
        background: transparent;

    }
    .table-no-style.ant-table-wrapper .ant-table-tbody >tr >td
    {
        border-top: 1px solid rgba(255, 0, 0, 0.1) !important;
        border-bottom: transparent;
    }
    .table-no-style.ant-table-wrapper .ant-table-tbody >tr.ant-table-row:hover>td, 
    .table-no-style.ant-table-wrapper .ant-table-tbody >tr >td.ant-table-cell-row-hover {
        background: rgb(250 250 250 / 10%);
    }
    .ant-table-wrapper .ant-table-thead th.ant-table-column-has-sorters:hover {
        background: rgb(250 250 250 / 10%);
    }
    .ant-pagination  .ant-pagination-item-active .ant-pagination-item a {
        color: var(--bs-white);
    }
    .ant-pagination .ant-pagination-prev button, 
    .ant-pagination .ant-pagination-next button {
        color: var(--bs-white);
    }
    .ant-table-wrapper td.ant-table-column-sort {
        background: rgb(250 250 250 / 20%);
    }
    .ant-pagination .ant-pagination-item:not(.ant-pagination-item-active) a {
        color: var(--bs-white);
    }
</style>