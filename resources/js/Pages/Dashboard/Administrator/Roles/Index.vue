<script>
    export default {
        name: "RolesIndex",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h, onMounted, onUnmounted } from 'vue';
    import { ReloadOutlined,} from '@ant-design/icons-vue';
    import { usePage } from '@inertiajs/vue3';
    import { message } from 'ant-design-vue';

    import DashboardLayout from '@/Layouts/DashboardLayout.vue';
    import CreateRole from './Components/Create.vue';
    import Spinner from '@/Components/Spinner.vue';
    import Table from '@/Components/Table.vue';
    import EditRole from './Components/Edit.vue';
    import TourRoles from './Components/Tour.vue';
    import DeleteRole from './Components/Delete.vue';

    import { useIndex } from './Composables/useIndex.js';
    // 2. Props & Emits (defineProps, defineEmits)

    // 3. State (ref, reactive)
    const { 
        loading, 
        pagination, 
        dataSource, 
        searchText, 
        loadData, 
        handleTableChange, 
        handleSearch,
        handleRefresh,
    } = useIndex();

    const columns = [
        { title: 'Rol', dataIndex: 'name',  width: '20%' },
        { title: 'Descripción', dataIndex: 'description' },
        { title: 'Acciones', dataIndex: 'actions', width: '100px' },
    ];
    const page = usePage();

    const can_create = page.props[0]['user.permissions'].includes('create roles')
    const can_read = page.props[0]['user.permissions'].includes('read roles')
    const can_update = page.props[0]['user.permissions'].includes('update roles')
    const can_delete = page.props[0]['user.permissions'].includes('delete roles')

    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    onMounted(() => {
        loadData();
        const channel = window.Echo.channel('roles');
        ['created', 'updated', 'deleted'].forEach(event => {
    
            channel.listen(`.roles.${event}`, () => loadData());
        });
    });
    onUnmounted(() => {
        window.Echo.leaveChannel('roles');
    });
    // 8. Expose (defineExpose)

    


    
</script>

<template>
    <Spinner >
        <DashboardLayout>
            <template #header>
                <a-page-header class="py-0 ps-2 pe-0 " title="Roles" backIcon="false">
                    <template #extra>
                        <a-input-search 
                            v-if="can_create"
                            v-model:value="searchText" 
                            placeholder="Buscar por..." 
                            @search="handleSearch" 
                        />
                        <a-button 
                            v-if="can_create"
                            :icon="h(ReloadOutlined)" 
                            @click="handleRefresh" 
                        />
                        <TourRoles  v-if="can_create" />
                        <CreateRole  v-if="can_create" />
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
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex === 'actions'">
                            <a-flex :align="'center'">
                                <EditRole v-if="can_update" :role="record" />
                                <DeleteRole v-if="can_delete" :role="record" />
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
