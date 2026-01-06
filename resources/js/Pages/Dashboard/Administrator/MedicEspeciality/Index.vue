<script>
    const modelTitle = "Especialidades";
    const modelName = "MedicEspecialityIndex";
    export default {
        name: modelName +"Index",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h, onMounted, onUnmounted, provide } from 'vue';
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

    // CONFIGURACIÓN CENTRALIZADA
    const config = {
        // Información básica del modelo
        modelName: 'medic-especialities',
        modelTitle: 'Especialidades',
        modelTitleSingular: 'Especialidad',

        // Campos del formulario
        formFields: {
            name: {
                label: 'Nombre',
                placeholder: 'Escribe aquí tu especialidad',
                maxlength: 8,
                required: true
            },
            description: {
                label: 'Descripción',
                placeholder: 'Escribe descripción de tu especialidad',
                required: true
            }
        },

        // Columnas de la tabla
        tableColumns: [
            { title: 'Nombre', dataIndex: 'name' },
            { title: 'Descripción', dataIndex: 'description' },
            { title: 'Icono', dataIndex: 'icon' },
            { title: 'Acciones', dataIndex: 'actions', width: '100px' }
        ],

        // Configuración de Echo/WebSocket
        echoChannel: 'medic-especialities',
        echoEvents: ['created', 'updated', 'deleted'],

        // Configuración de paginación
        defaultPageSize: 8,

        // Configuración de búsqueda
        searchPlaceholder: 'Buscar por...',

        // Reglas de validación (pueden ser funciones)
        validationRules: {
            create: (form) => ({
                name: [
                    { required: true, message: 'El nombre es obligatorio' },
                    { max: 8, message: 'El nombre no puede tener más de 8 caracteres' }
                ],
                description: [
                    { required: true, message: 'La descripción es obligatoria' }
                ]
            }),
            edit: (form) => ({
                name: [
                    { required: true, message: 'El nombre es obligatorio' },
                    { max: 8, message: 'El nombre no puede tener más de 8 caracteres' }
                ],
                description: [
                    { required: true, message: 'La descripción es obligatoria' }
                ]
            })
        }
    };

    // Proporcionar configuración a componentes hijos
    provide('medicEspecialityConfig', config);

    const {
        loading,
        pagination,
        dataSource,
        searchText,
        loadData,
        handleTableChange,
        handleSearch,
        handleRefresh,
    } = useIndex(config);

    // Actualizar paginación con el valor de configuración
    pagination.value.pageSize = config.defaultPageSize;

    const page = usePage();

    // Permisos centralizados
    const permissions = {
        create: page.props[0]['user.permissions'].includes('create ' + config.modelName),
        read: page.props[0]['user.permissions'].includes('read ' + config.modelName),
        update: page.props[0]['user.permissions'].includes('update ' + config.modelName),
        delete: page.props[0]['user.permissions'].includes('delete ' + config.modelName)
    };

    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    onMounted(() => {
        loadData();
        const channel = window.Echo.channel(config.echoChannel);
        config.echoEvents.forEach(event => {
            channel.listen(`.${config.echoChannel}.${event}`, () => {
                loadData();
            });
        });
    });
    onUnmounted(() => {
        window.Echo.leaveChannel(config.modelName.toLowerCase());
    });
    // 8. Expose (defineExpose)
</script>

<template>
    <Spinner >
        <DashboardLayout>
            <template #header>
                <a-page-header class="py-0 ps-2 pe-0 " :title="config.modelTitle" backIcon="false">
                    <template #extra>
                        <a-input-search
                            v-if="permissions.create"
                            v-model:value="searchText"
                            :placeholder="config.searchPlaceholder"
                            @search="handleSearch"
                        />
                        <a-button
                            v-if="permissions.create"
                            :icon="h(ReloadOutlined)"
                            @click="handleRefresh"
                        />
                        <TourItem  v-if="permissions.create" />
                        <CreateItem  v-if="permissions.create" :config="config" />
                        <div v-else>Sin permisos para crear</div>

                    </template>
                </a-page-header>
            </template>

            <template #content>
                <Table
                    v-if="permissions.read"
                    :loading="loading"
                    :columns="config.tableColumns"
                    :data-source="dataSource"
                    :pagination="pagination"
                    @handleChange="handleTableChange"
                >
                    <template #bodyCell="{ column, record }">
                        <template v-if="column.dataIndex === 'name'">
                            {{ record.name }}
                        </template>
                        <template v-if="column.dataIndex === 'description'">
                            {{ record.description }}
                        </template>
                        <template v-if="column.dataIndex === 'icon'">
                            {{ record.icon }}
                        </template>

                        <template v-if="column.dataIndex === 'actions'">
                            <a-flex :align="'center'">
                                <EditItem v-if="permissions.update" :item="record" :config="config" />
                                <DeleteItem v-if="permissions.delete" :item="record" :config="config" />
                            </a-flex>
                        </template>
                    </template>
                </Table>
            </template>
        </DashboardLayout>
    </Spinner>
</template>
<style></style>
