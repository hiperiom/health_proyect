<script>
    const modelTitle = "Médicos";
    const modelTitleSingular = "Médico";
    const modelName = "Medic";
    const modelNameKebabCase = "medics";
    const modelNameRoutes = "medics";
    const fileName = "MedicIndex";
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
    import { Avatar } from 'ant-design-vue';

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
            
            dni: {
                label: 'Cédula',
                placeholder: 'Escribe aquí tu cédula',
                maxlength: 8,
                required: true,
                type: 'input'
            },
            email: {
                label: 'Correo electrónico',
                placeholder: 'Escribe aquí tu correo electrónico',
                required: true,
                type: 'input'
            },
            first_names: {
                label: 'Nombres',
                placeholder: 'Escribe tu primer y segundo nombre',
                maxlength: 50,
                required: true,
                type: 'input'
            },
            last_names: {
                label: 'Apellidos',
                placeholder: 'Escribe tu primer y segundo apellido',
                maxlength: 50,
                required: true,
                type: 'input'
            },
            gender: {
                label: 'Género',
                placeholder: 'Selecciona el sexo',
                required: true,
                type: 'select',
                options: [
                    { value: 'm', label: 'Masculino' },
                    { value: 'f', label: 'Femenino' }
                ]
            },
            birthday: {
                label: 'Fecha de nacimiento',
                placeholder: 'Escribe tu fecha de nacimiento dia/mes/año',
                required: true,
                type: 'date',
                format: 'DD/MM/YYYY',
                valueFormat: 'YYYY-MM-DD'
            }
        },

        // Campos adicionales para creación (password)
        createOnlyFields: {
            password: {
                default: '12345678',
                required: true
            },
            password_confirmation: {
                default: '12345678',
                required: true
            },
            terms: {
                default: false,
                required: false
            }
        },

        // Columnas de la tabla
        tableColumns: [
            { title: 'Avatar', dataIndex: 'avatar', width: '80px' },
            { title: 'Nombres', dataIndex: 'first_names' },
            { title: 'Apellidos', dataIndex: 'last_names' },
            { title: 'Cédula', dataIndex: 'dni' },
            { title: 'Correo', dataIndex: 'email' },
            { title: 'Nacimiento', dataIndex: 'birthday' },
            { title: 'Género', dataIndex: 'gender' },
            { title: 'Acciones', dataIndex: 'actions', width: '100px' }
        ],

        // Configuración de Echo/WebSocket
        echoChannel: modelNameRoutes,
        echoEvents: ['created', 'updated', 'deleted'],

        // Configuración de paginación
        defaultPageSize: 8,

        // Configuración de búsqueda
        searchPlaceholder: 'Buscar por...',

        // Reglas de validación
        validationRules: {
            create: (form) => ({
                dni: [
                    { required: true, message: 'La cédula es obligatoria' },
                    { pattern: /^\d{7,8}$/, message: 'La cédula debe tener 7-8 dígitos' }
                ],
                email: [
                    { required: true, message: 'El correo es obligatorio' },
                    { type: 'email', message: 'Ingresa un correo válido' }
                ],
                first_names: [
                    { required: true, message: 'Los nombres son obligatorios' },
                    { max: 50, message: 'Los nombres no pueden tener más de 50 caracteres' }
                ],
                last_names: [
                    { required: true, message: 'Los apellidos son obligatorios' },
                    { max: 50, message: 'Los apellidos no pueden tener más de 50 caracteres' }
                ],
                gender: [
                    { required: true, message: 'El género es obligatorio' }
                ],
                birthday: [
                    { required: true, message: 'La fecha de nacimiento es obligatoria' }
                ],
                password: [
                    { required: true, message: 'La contraseña es obligatoria' },
                    { min: 8, message: 'La contraseña debe tener al menos 8 caracteres' }
                ],
                password_confirmation: [
                    { required: true, message: 'La confirmación de contraseña es obligatoria' },
                    ({ getFieldValue }) => ({
                        validator(_, value) {
                            if (!value || getFieldValue('password') === value) {
                                return Promise.resolve();
                            }
                            return Promise.reject(new Error('Las contraseñas no coinciden'));
                        },
                    }),
                ]
            }),
            edit: (form) => ({
                dni: [
                    { required: true, message: 'La cédula es obligatoria' },
                    { pattern: /^\d{7,8}$/, message: 'La cédula debe tener 7-8 dígitos' }
                ],
                email: [
                    { required: true, message: 'El correo es obligatorio' },
                    { type: 'email', message: 'Ingresa un correo válido' }
                ],
                first_names: [
                    { required: true, message: 'Los nombres son obligatorios' },
                    { max: 50, message: 'Los nombres no pueden tener más de 50 caracteres' }
                ],
                last_names: [
                    { required: true, message: 'Los apellidos son obligatorios' },
                    { max: 50, message: 'Los apellidos no pueden tener más de 50 caracteres' }
                ],
                gender: [
                    { required: true, message: 'El género es obligatorio' }
                ],
                birthday: [
                    { required: true, message: 'La fecha de nacimiento es obligatoria' }
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
                        <template v-if="column.dataIndex === 'avatar'">

                            <Avatar :src="record.profile_photo_url" size="default" />
                        </template>
                        <template v-if="column.dataIndex === 'first_names'">
                            {{ record.profile.first_names }}
                        </template>
                        <template v-if="column.dataIndex === 'last_names'">
                            {{ record.profile.last_names }}
                        </template>
                        <template v-if="column.dataIndex === 'dni'">
                            {{ record.dni }}
                        </template>
                        <template v-if="column.dataIndex === 'email'">
                            {{ record.email }}
                        </template>
                        <template v-if="column.dataIndex === 'birthday'">
                            
                            {{ $date(record.profile.birthday).format('DD/MM/YYYY') }}
                        </template>
                        <template v-if="column.dataIndex === 'gender'">
                            {{ record.profile.gender.toUpperCase() }}
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
