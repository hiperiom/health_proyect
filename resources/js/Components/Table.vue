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
    .table-no-style.ant-table-wrapper,
    .table-no-style.ant-table-wrapper .ant-spin-nested-loading{
        display:flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        
    }
    .ant-spin-container{
        display:flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        
    }
    
    .table-no-style.ant-table-wrapper .ant-table {
        background: #ffffff70 !important;
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        overflow: auto;
        flex: 1 1 0;

    }
    .table-no-style.ant-table-wrapper .ant-table-thead >tr>th, 
    .table-no-style.ant-table-wrapper .ant-table-thead >tr>td {
        background: #ffffff70;
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
    .table-no-style.ant-table-wrapper .ant-table-container table>thead>tr:first-child >*:first-child {
        border-start-start-radius: 1rem;
    }
    .table-no-style.ant-table-wrapper .ant-table-container table>thead>tr:first-child >*:last-child {
        border-start-end-radius: 1rem;
    }
    .ant-table-tbody >tr:last-child>td {
        border-bottom: transparent !important;
    }

    .table-no-style.ant-table-wrapper .ant-table-thead th.ant-table-column-has-sorters:hover {
        background: rgb(250 250 250 / 10%);
    }
    .ant-pagination  .ant-pagination-item-active .ant-pagination-item a {
        color: var(--bs-white);
    }
    .ant-pagination .ant-pagination-prev button, 
    .ant-pagination .ant-pagination-next button {
        color: var(--bs-white);
    }
    .table-no-style.ant-table-wrapper td.ant-table-column-sort {
        background: rgb(250 250 250 / 20%);
    }
    .ant-pagination .ant-pagination-item:not(.ant-pagination-item-active) a {
        color: var(--bs-white);
    }




</style>
