<script>
    const modelTitle = "Roles";
    const modelTitleSingular = "Rol";
    const modelName = "Roles";
    const modelNameKebabCase = "roles";
    const modelNameRoutes = "roles";
    const fileName = modelName + "Index";
    export default {
        name: fileName,
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    import { h, onMounted, onUnmounted, provide } from 'vue';
    import { usePage } from '@inertiajs/vue3';
    import { ReloadOutlined,} from '@ant-design/icons-vue';

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
    const page = usePage();

    const config = {
        // Información básica del modelo
        modelName,
        modelNameKebabCase,
        modelTitle,
        modelTitleSingular,
        modelNameRoutes,

        // Campos del formulario
        formFields: {
            name: {
                label: 'Nombre del Rol',
                placeholder: 'Escribe el nombre del rol',
                maxlength: 50,
                required: true,
                type: 'input'
            },
            description: {
                label: 'Descripción',
                placeholder: 'Escribe la descripción del rol',
                required: false,
                type: 'textarea'
            }
        },

        // Campos adicionales para creación
        createOnlyFields: {
            color: {
                default: 'blue',
                required: false
            },
            guard_name: {
                default: 'web',
                required: true
            }
        },

        // Columnas de la tabla
        tableColumns: [
            { title: 'Rol', dataIndex: 'name', width: '20%' },
            { title: 'Descripción', dataIndex: 'description' },
            { title: 'Acciones', dataIndex: 'actions', width: '100px' }
        ],

        // Configuración de Echo/WebSocket
        echoChannel: modelNameRoutes,
        echoEvents: ['created', 'updated', 'deleted'],

        // Configuración de paginación
        defaultPageSize: 8,

        // Configuración de búsqueda
        searchPlaceholder: 'Buscar roles...',

        // Reglas de validación
        validationRules: {
            create: (form) => ({
                name: [
                    { required: true, message: 'El nombre del rol es obligatorio' },
                    { max: 50, message: 'El nombre no puede tener más de 50 caracteres' }
                ],
                description: [
                    { required: false }
                ]
            }),
            edit: (form) => ({
                name: [
                    { required: true, message: 'El nombre del rol es obligatorio' },
                    { max: 50, message: 'El nombre no puede tener más de 50 caracteres' }
                ],
                description: [
                    { required: false }
                ]
            })
        },
        // Permisos centralizados
        user_permissions: {
            can_create: page.props[0]['user.permissions'].includes('create ' + modelNameRoutes),
            can_read: page.props[0]['user.permissions'].includes('read ' + modelNameRoutes),
            can_update: page.props[0]['user.permissions'].includes('update ' + modelNameRoutes),
            can_delete: page.props[0]['user.permissions'].includes('delete ' + modelNameRoutes)
        },
        permissionsNames: page.props[0]['user.permissions']
    };

    console.log(config);

    const { can_read } = config.user_permissions;

    // Proporcionar configuración a componentes hijos
    provide('rolesConfig', config);

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
                <a-page-header class="py-0 ps-2 pe-0 " :title="config.modelTitle" :backIcon="false">
                    <template #extra>
                        <a-input-search
                            :disabled="!can_read"
                            :title="can_read ? 'Buscar en listado de ' + config.modelTitle : 'Sin permisos para consultar'"
                            v-model:value="searchText"
                            :placeholder="config.searchPlaceholder"
                            @search="handleSearch"
                        />
                        <a-button
                            :disabled="!can_read"
                            :title="can_read ? 'Refrescar listado de ' + config.modelTitle : 'Sin permisos para refrescar'"
                            :icon="h(ReloadOutlined)"
                            @click="handleRefresh"
                        />
                        <TourItem
                            v-if="config.user_permissions.can_create"
                            :config="config"
                        />
                        <CreateItem
                            v-if="config.user_permissions.can_create"
                            :config="config"
                        />
                        <div v-else>Sin permisos para crear</div>

                    </template>
                </a-page-header>
            </template>

            <template #content>
                <Table
                    v-if="can_read"
                    :locale="{ emptyText: 'No hay registros para mostrar' }"
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

                        <template v-if="column.dataIndex === 'actions'">
                            <a-flex :align="'center'">
                                <EditItem
                                    v-if="config.user_permissions.can_update"
                                    :item="record"
                                    :config="config"
                                />
                                <DeleteItem
                                    v-if="config.user_permissions.can_delete"
                                    :item="record"
                                    :config="config"
                                />
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
