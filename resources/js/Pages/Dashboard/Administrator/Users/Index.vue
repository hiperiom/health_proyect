<script>
    const modelTitle = "Usuarios";
    const modelName = "Users";
    export default {
        name: modelName +"Index",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h, onMounted, onUnmounted } from 'vue';
    import { ReloadOutlined,} from '@ant-design/icons-vue';
    import { usePage } from '@inertiajs/vue3';

    import DashboardLayout from '@/Layouts/DashboardLayout.vue';
    import Spinner from '@/Components/Spinner.vue';
    import Table from '@/Components/Table.vue';

    import TourItem from './Components/Tour.vue';
    import CreateItem from './Components/Create.vue';
    import EditItem from './Components/Edit.vue';
    import DeleteItem from './Components/Delete.vue';

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
    } = useIndex(modelName);

    const columns = [
        //{ title: 'Usuario', dataIndex: 'name',  width: '20%' },
        //{ title: 'Cédula', dataIndex: 'dni' },
        //{ title: 'Correo', dataIndex: 'email' },
        { title: 'Acciones', dataIndex: 'actions', width: '100px' },
    ];
    const page = usePage();

    const can_create = page.props[0]['user.permissions'].includes('create ' + modelName.toLowerCase())
    const can_read = page.props[0]['user.permissions'].includes('read ' + modelName.toLowerCase())
    const can_update = page.props[0]['user.permissions'].includes('update ' + modelName.toLowerCase())
    const can_delete = page.props[0]['user.permissions'].includes('delete ' + modelName.toLowerCase())

    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    onMounted(() => {
        loadData();
        const channel = window.Echo.channel( modelName.toLowerCase() );
        ['created', 'updated', 'deleted'].forEach(event => {
            channel.listen(`.${ modelName.toLowerCase() }.${event}`, () => loadData());
        });
    });
    onUnmounted(() => {
        window.Echo.leaveChannel( modelName.toLowerCase() );
    });
    // 8. Expose (defineExpose)

    


    
</script>

<template>
    <Spinner >
        <DashboardLayout>
            <template #header>
                <a-page-header class="py-0 ps-2 pe-0 " :title="modelTitle" backIcon="false">
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
                        <TourItem  v-if="can_create" />
                        <CreateItem  v-if="can_create" :modelName="modelName" />
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
                                <EditItem v-if="can_update" :item="record" :modelName="modelName" />
                                <DeleteItem v-if="can_delete" :item="record" :modelName="modelName" />
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
