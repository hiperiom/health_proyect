<script>
    export default {
        name: "RolesIndex",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h, onMounted, ref } from 'vue';
    import {  
        ReloadOutlined,
        EditOutlined 
    } from '@ant-design/icons-vue';
    import { Flex, message } from 'ant-design-vue';
    import { usePage } from '@inertiajs/vue3';

    import DashboardLayout from '@/Layouts/DashboardLayout.vue';
    import CreateRole from './Components/Create.vue';
    import Spinner from '@/Components/Spinner.vue';
    import Table from '@/Components/Table.vue';
    import TourRoles from './Components/Tour.vue';
    import DeleteRole from './Components/Delete.vue';
    import { useRoles } from './Composables/useRoles';
    import { useRolesList } from './Composables/useRolesList';
    // 2. Props & Emits (defineProps, defineEmits)

    // 3. State (ref, reactive)
    const { 
        loading, 
        drawerOpen, 
        pagination, 
        fetchRoles, 
        handleDrawer 
    } = useRoles();

    const page = usePage();
    const can_create = page.props[0]['user.permissions'].includes('create roles')
    const can_read = page.props[0]['user.permissions'].includes('read roles')

    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    const handleSubmit = () => alert("Submitted");

    const handleCancelForm = () => {
        alert("Cancelled");
        handleDrawer(false);
    };
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)

        
    // Inicializamos la lógica de la lista
    const { 
        dataSource, 
        searchText, 
        loadData, 
        handleTableChange, 
        handleSearch,
        handleRefresh
    } = useRolesList(fetchRoles, ref(pagination)); 
    // Nota: Usamos ref(pagination) para mantener la reactividad bidireccional
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
    <Spinner >
        <DashboardLayout>
            <template #header>
                <a-page-header class="py-0 ps-2 pe-0 " title="Roles" backIcon="false">
                    <template #extra>
                        <a-input-search 
                            v-model:value="searchText" 
                            placeholder="Buscar por..." 
                            @search="handleSearch" 
                        />
                        <a-button 
                            :icon="h(ReloadOutlined)" 
                            @click="handleRefresh" 
                        />
                        <TourRoles  v-if="can_create" />
                        <CreateRole 
                            v-if="can_create"
                            :drawerOpen="drawerOpen"
                            @handleDrawer="handleDrawer"
                            @handleSubmit="handleSubmit" 
                            @handleCancelForm="handleCancelForm"
                        />
                        <div v-else>Sin permisos para crear</div>
                        
                        
                    </template>
                </a-page-header>
            </template>

            <template #content>
                <Table 
                    v-if="can_read"
                    :loading="loading"
                    :columns="columns" 
                    :data-source="dataSource" 
                    :pagination="pagination"
                    @handleChange="handleTableChange"
                >
                    <template #bodyCell="{ column }">
                        <template v-if="column.dataIndex === 'actions'">
                            <a-flex :align="'center'">
                                <EditRol />
                                <a-button type="link"><EditOutlined /></a-button>
                                <DeleteRole />
                            </a-flex>
                        </template>
                    </template>
                </Table>
            </template>
        </DashboardLayout>
    </Spinner>
</template>
<style>


</style>