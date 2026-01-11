<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateAdminScaffold extends Command
{
    protected $signature = "make:new-module";
    protected $description = 'Scaffolding integral: Frontend Vue + Backend Completo + Rutas + Estructura de Clases';

    public function handle()
    {
        $config = [];
        $config['modelTitle'] = Str::title($this->ask('Titulo del modelo (Español y plural):'));
        $this->warn($config['modelTitle']);
        $config['modelTitleSingular'] = Str::title($this->ask('Titulo del modelo en singular (Español):', $config['modelTitle']));
        $this->warn($config['modelTitleSingular']);
        $config['modelNameSingular'] = Str::studly( $this->ask('Nombre del modelo (Singular en ingles):') );
        $this->warn($config['modelNameSingular']);
        $config['modelNamePlural'] = $this->ask('Nombre del modelo en (Plural en ingles):',Str::plural($config['modelNameSingular']));
        $this->warn($config['modelNamePlural']);
        $config['modelNameKebabCase'] = $this->ask('Nombre del modelo en kebab-case:',Str::kebab($config['modelNamePlural']));
        $this->warn($config['modelNameKebabCase']);
        $config['modelNameRoutes'] = $this->ask('Nombre de las rutas del modelo:',$config['modelNameKebabCase']);
        $this->warn($config['modelNameRoutes']);
        $config['modelNameController'] = $this->ask('Nombre del controlador del modelo:', Str::studly($config['modelNameSingular']).'Controller');
        $this->warn($config['modelNameController']);
        
        $this->info("🚀 Iniciando scaffolding integral para: {$config['modelNameSingular']}");

        // 1. FRONTEND (VUE)
        $this->generateFrontend($config);

        // 2. BACKEND CORE (Model, Mig, Fact, Seed, Controller)
        $model = $config['modelNameSingular'];
        $controller = "{$model}Controller";

        // Lista de rutas a eliminar
        $files = [
            app_path("Models/{$model}.php"),
            app_path("Events/{$model}"),
            app_path("Services/{$model}"),
            app_path( "Observers/{$model}Observer.php"),
            app_path("Http/Controllers/{$model}Controller.php"),
            app_path("Http/Requests/{$model}"),
            app_path("Http/Resources/{$model}"),
            app_path("Policies/{$model}Policy.php"),
            database_path("factories/{$model}Factory.php"),
            database_path("seeders/{$model}Seeder.php"),
        ];

        foreach ($files as $file) {
            if (File::exists($file)) {
                File::delete($file);
            }
        }
        // Para la migración es más complejo por el timestamp, tendrías que buscarla:
        $migrationPattern = database_path("migrations/*_create_" . Str::snake(Str::plural($model)) . "_table.php");
        foreach (glob($migrationPattern) as $migrationFile) {
            File::delete($migrationFile);
        }

        $this->call('make:model', [
            'name' => $config['modelNameSingular'],
            '--controller' => true,
            '--migration'  => true,
            '--factory'    => true,
            '--seed'       => true,
            '--policy'     => true,
        ]);
        $this->createController($config);

        // 3. ESTRUCTURAS PERSONALIZADAS (Services, Requests, Events, Resources, Observers)
        $this->generateBackendCustomStructures($config['modelNameSingular']);

        // 4. REGISTRO AUTOMÁTICO DE RUTA EN WEB.PHP
        $this->registerRoute($config['modelNameKebabCase'], $config['modelNameController']);

        // 5. REGISTRO AUTOMÁTICO DEL OBSERVER
        $this->registerObserver($config['modelNameSingular']);

        // 6. REGISTRO DE PERMISOS
        $this->registerPermissions($config['modelNameSingular']);

        $this->info("\n✅ ¡Proceso completado con éxito!");
    }
    protected function registerObserver($modelName)
    {
        $appServiceProviderPath = app_path('Providers/AppServiceProvider.php');

        if (!File::exists($appServiceProviderPath)) {
            $this->error("   - No se encontró AppServiceProvider.php");
            return;
        }

        $content = File::get($appServiceProviderPath);

        // Verificar si ya está registrado el observer
        $observerLine = "{$modelName}::observe({$modelName}Observer::class);";
        if (str_contains($content, $observerLine)) {
            $this->line("   - Observer <comment>{$modelName}Observer</comment> ya estaba registrado.");
            return;
        }

        // Verificar si existe el import del modelo
        $modelImport = "use App\Models\\{$modelName};";
        if (!str_contains($content, $modelImport)) {
            // Agregar el import del modelo
            $content = preg_replace(
                '/(use App\\\\Models\\\\[^;]+;)/',
                "$1\n{$modelImport}",
                $content,
                1
            );
        }

        // Verificar si existe el import del observer
        $observerImport = "use App\\Observers\\{$modelName}Observer;";
        if (!str_contains($content, $observerImport)) {
            // Agregar el import del observer
            $content = preg_replace(
                '/(use App\\\\Observers\\\\[^;]+;)/',
                "$1\n{$observerImport}",
                $content,
                1
            );
        }

        // Agregar la línea del observer en el método boot
        $pattern = '/(public function boot\(\): void\s*\{[^}]*)(Schema::defaultStringLength\(191\);)/s';

        if (preg_match($pattern, $content, $matches)) {
            $replacement = $matches[1] . $matches[2] . "\n        {$observerLine}";
            $content = preg_replace($pattern, $replacement, $content);
        } else {
            // Si no encuentra el patrón, buscar el método boot y agregar al final
            $content = preg_replace(
                '/(public function boot\(\): void\s*\{[^}]*)\}/s',
                "$1        {$observerLine}\n    }",
                $content
            );
        }

        File::put($appServiceProviderPath, $content);
        $this->line("   - Observer <info>{$modelName}Observer</info> registrado automáticamente en AppServiceProvider.");
    }
    protected function generateFrontend($config)
    {
        $basePath = resource_path("js/Pages/Dashboard/{$config['modelNameSingular']}");
        if (File::exists($basePath)) {
            File::deleteDirectory($basePath);
        }
        if (!File::exists($basePath)) {
            File::makeDirectory($basePath, 0755, true);
            File::makeDirectory("{$basePath}/Components", 0755, true);
            File::makeDirectory("{$basePath}/Composables", 0755, true);
            File::makeDirectory("{$basePath}/Utils", 0755, true);

            $this->createVueFile(
                $basePath, 
                'Index', 
                $config
            );
            foreach (['Create', 'Edit', 'Delete', 'Tour'] as $comp) {
                $this->createVueFile(
                    "{$basePath}/Components", 
                    $comp, 
                    $config['modelNameSingular']
                );
            }
            foreach (['useCreate', 'useEdit', 'useIndex'] as $file) {
                $template = $this->vueComponentTemplates($file);
                File::put("{$basePath}/Composables/{$file}.js", $template);
            }
            foreach (['createRules', 'editRules'] as $file) {
                $helper = 'get'.ucwords($file);
                File::put("{$basePath}/Utils/{$file}.js", "export const {$helper} = (form) => ({});");
            }
            $this->line("   - Frontend Vue creado.");
        }
    }
    protected function createVueFile($path, $fileName='Index', $config)
    {
        
        $template = $this->vueComponentTemplates(
            $fileName, 
            $config
        );
        File::put("{$path}/{$fileName}.vue", $template);
    }
    protected function vueComponentTemplates($fileName,$config=null){
        switch ($fileName) {
            case 'Index':
                return <<<EOT
                    <script>
                        const modelTitle = "{$config['modelTitle']}";
                        const modelTitleSingular = "{$config['modelTitleSingular']}";
                        const modelName = "{$config['modelNameSingular']}";
                        const modelNameKebabCase = "{$config['modelNameKebabCase']}";
                        const modelNameRoutes = "{$config['modelNameRoutes']}";
                        const fileName = "{$config['modelNameSingular']}Index";
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
                            echoChannel: modelNameRoutes, // modelNameRoutes / modelNameKebabCase
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
                        const {  can_read } = config.user_permissions;
                        
                        // Proporcionar configuración a componentes hijos

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
                                channel.listen("."+ config.echoChannel + "." + event, () => {
                                    loadData();
                                });
                            });
                        });
                        onUnmounted(() => {
                            window.Echo.leaveChannel(config.modelNameKebabCase);
                        });
                        // 8. Expose (defineExpose)
                    </script>

                    <template>
                        <Spinner >
                            <DashboardLayout>
                                <template #header>
                                    <a-page-header class="py-0 ps-2 pe-0 " 
                                        :title="config.modelTitle" 
                                        :backIcon="false"
                                    >
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
                                                :config="config"  
                                            />
                                            <CreateItem  
                                                :config="config" 
                                            />
                                            
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
                                            <template v-if="column.dataIndex === 'icon'">
                                                {{ record.icon }}
                                            </template>

                                            <template v-if="column.dataIndex === 'actions'">
                                                <a-flex :align="'center'">
                                                    <EditItem 
                                                        :item="record" 
                                                        :config="config" 
                                                    />
                                                    <DeleteItem 
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
                    <style lang="scss" scoped></style>
                                    
                EOT;
            break;
            case 'Create':
                return <<<EOT
                    <script>
                        export default {
                            name: "CreateItem",
                        }
                    </script>
                    <script setup>
                        // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
                        import { h, onUnmounted, ref } from 'vue';
                        import { PlusOutlined } from '@ant-design/icons-vue';
                        import { useCreate } from '../Composables/useCreate';
                        import Spinner from '@/Components/Spinner.vue';
                        import Modal from '@/Components/Modal.vue';

                        // 2. Props & Emits (defineProps, defineEmits)
                        const props = defineProps({
                            config: {
                            type: Object,
                            required: true,
                            },
                        });

                        // 3. State (ref, reactive)
                        const modalOpen = ref(false);

                        const {
                            form,
                            formRef,
                            handleSubmit
                        } = useCreate(modalOpen, props.config);

                        // Usar reglas de validación de la configuración
                        const rulesForm = props.config.validationRules.create(form);

                        // 4. Computed Properties
                        // 5. Methods & Logic (Functions, Handlers)
                        const handleModal = () => {
                            form.reset();
                            modalOpen.value = true;
                        };
                        const handleCancelForm = () => {
                            form.reset();
                            modalOpen.value = false;
                        };
                        // 6. Watchers
                        // 7. Lifecycle Hooks (onMounted, etc.)
                        onUnmounted(() => {
                            form.reset();
                        });
                        // 8. Expose (defineExpose)
                    </script>
                    <template>
                        <a-button :icon="h(PlusOutlined)" type="primary" @click="handleModal(true)">
                            Nuevo {{ config.modelTitleSingular }}
                        </a-button>
                        <Modal :title="'Crear ' + config.modelTitleSingular" :openModal="modalOpen" @handleCancelForm="handleCancelForm">
                            <template #content>
                            <Spinner :loading="form.processing">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                <a-form ref="formRef" layout="vertical" :model="form" :rules="rulesForm" @submit.prevent="handleSubmit">
                                    <a-row id="tour-identity" justify="center" :gutter="10" :wrap="true">
                                    <a-col v-for="(fieldConfig, fieldName) in config.formFields" :key="fieldName" :span="24">
                                        <a-form-item :name="fieldName" :ref="fieldName" has-feedback :label="fieldConfig.label">
                                        <!-- Input text -->
                                        <a-input v-if="fieldConfig.type === 'input'" :name="fieldName" :maxlength="fieldConfig.maxlength"
                                            v-model:value="form[fieldName]" :placeholder="fieldConfig.placeholder" />
                                        <!-- Textarea -->
                                        <a-textarea v-else-if="fieldConfig.type === 'textarea'" :name="fieldName"
                                            v-model:value="form[fieldName]" :placeholder="fieldConfig.placeholder" :rows="4" />
                                        </a-form-item>
                                    </a-col>

                                    <!-- Campos adicionales para creación (color) -->
                                    <a-col v-if="config.createOnlyFields && config.createOnlyFields.color" :span="24">
                                        <a-form-item name="color" label="Color">
                                        <a-select v-model:value="form.color">
                                            <a-select-option value="blue">Azul</a-select-option>
                                            <a-select-option value="red">Rojo</a-select-option>
                                            <a-select-option value="green">Verde</a-select-option>
                                        </a-select>
                                        </a-form-item>
                                    </a-col>
                                    </a-row>
                                </a-form>
                                </div>
                            </Spinner>
                            </template>
                            <template #footer>
                            <a-space>
                                <a-button @click="handleCancelForm()">Cancelar</a-button>
                                <a-button type="primary" @click="handleSubmit()">Registrar</a-button>
                            </a-space>
                            </template>
                        </Modal>
                    </template>
                    <style lang="scss" scoped></style>

                EOT;
            break;
            case 'Edit':
                return <<<EOT
                    <script>
                        export default {
                            name: "EditItem",
                        }
                    </script>
                    <script setup>
                        // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
                        import { h, ref, watch } from 'vue';
                        import { EditOutlined } from '@ant-design/icons-vue';
                        import Modal from '@/Components/Modal.vue';
                        import Spinner from '@/Components/Spinner.vue';
                        import { useEdit } from '../Composables/useEdit';

                        // 2. Props & Emits (defineProps, defineEmits)
                        const props = defineProps({
                            item: {
                            type: Object,
                            required: true,
                            },
                            config: {
                            type: Object,
                            required: true,
                            },
                        });

                        // 3. State (ref, reactive)
                        const modalOpen = ref(false);

                        const {
                            form,
                            formRef,
                            handleSubmit: originalHandleSubmit
                        } = useEdit(props.item, modalOpen, props.config);

                        // Usar reglas de validación de la configuración
                        const rulesForm = props.config.validationRules.edit(form);


                        // 4. Computed Properties
                        // 5. Methods & Logic (Functions, Handlers)
                        const handleDrawer = (val) => {
                            drawerOpen.value = val;
                        };

                        const handleSubmit = async () => {
                            await originalHandleSubmit();
                            modalOpen.value = false; // Close modal after successful submit
                        };

                        const handleCancelForm = () => {
                            // Reset to current item values
                            Object.keys(props.config.formFields).forEach(fieldName => {
                            form[fieldName] = props.item[fieldName] || '';
                            });
                            modalOpen.value = false;
                        };

                        // 6. Watchers
                        watch(() => props.item, (newItem) => {
                            if (newItem) {
                            // Poblar el formulario dinámicamente con los datos del item
                            Object.keys(props.config.formFields).forEach(fieldName => {
                                form[fieldName] = newItem[fieldName] || '';
                            });
                            }
                        }, { immediate: true });

                        // 7. Lifecycle Hooks (onMounted, etc.)
                        // 8. Expose (defineExpose)
                    </script>
                    <template>
                        <a-button type="link" @click="handleModal(true)">
                            <EditOutlined />
                        </a-button>

                        <Modal :title="'Editar ' + config.modelTitleSingular" :openModal="modalOpen" @handleCancelForm="handleCancelForm">
                            <template #content>
                            <Spinner :loading="form.processing">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                <a-form ref="formRef" layout="vertical" :model="form" :rules="rulesForm" @submit.prevent="handleSubmit">
                                    <a-row id="tour-identity" justify="center" :gutter="10" :wrap="true">
                                    <a-col v-for="(fieldConfig, fieldName) in config.formFields" :key="fieldName" :span="24">
                                        <a-form-item :name="fieldName" :ref="fieldName" has-feedback :label="fieldConfig.label">
                                        <!-- Input text -->
                                        <a-input v-if="fieldConfig.type === 'input'" :name="fieldName" :maxlength="fieldConfig.maxlength"
                                            v-model:value="form[fieldName]" :placeholder="fieldConfig.placeholder" />
                                        <!-- Textarea -->
                                        <a-textarea v-else-if="fieldConfig.type === 'textarea'" :name="fieldName"
                                            v-model:value="form[fieldName]" :placeholder="fieldConfig.placeholder" :rows="4" />
                                        </a-form-item>
                                    </a-col>
                                    </a-row>
                                </a-form>
                                </div>
                            </Spinner>
                            </template>
                            <template #footer>
                            <a-space>
                                <a-button @click="handleCancelForm()">Cancelar</a-button>
                                <a-button type="primary" @click="handleSubmit()">Actualizar</a-button>
                            </a-space>
                            </template>
                        </Modal>
                    </template>
                    <style lang="scss" scoped></style>
                EOT;
            break;
            case 'Delete':
                return <<<EOT
                <script>
                    export default {
                        name: "DeleteItem",
                    }
                </script>

                <script setup>
                    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
                    import { message } from 'ant-design-vue';
                    import axios from 'axios';
                    import {
                        DeleteOutlined,
                    } from '@ant-design/icons-vue';

                    // 2. Props & Emits (defineProps, defineEmits)
                    const props = defineProps({
                        item: {
                            type: Object,
                            required: true,
                        },
                        config: {
                            type: Object,
                            required: true,
                        },
                    });

                    // 3. State (ref, reactive)
                    // 4. Computed Properties
                    // 5. Methods & Logic (Functions, Handlers)
                    const handleDelete = async () => {
                        try {
                            await axios.delete(route(props.config.modelNameKebabCase +'.destroy', props.item));
                            message.success(props.config.modelTitleSingular + ' eliminado exitosamente.');
                        } catch (error) {
                            console.log(error);
                            const msg = error.response?.data?.message || 'Error al eliminar el ' + props.config.modelTitleSingular.toLowerCase() + '.';
                            message.error(msg);
                        }
                    };

                    // 6. Watchers
                    // 7. Lifecycle Hooks (onMounted, etc.)
                    // 8. Expose (defineExpose)
                </script>
                <template>
                    <a-popconfirm
                        placement="bottomRight"
                        :title="'¿Quieres eliminar este ' + config.modelTitleSingular.toLowerCase() + '?'"
                        ok-text="Si"
                        cancel-text="No"
                        @confirm="handleDelete"
                    >
                        <a href="#" :title="'Eliminar ' + config.modelTitleSingular"><DeleteOutlined /></a>
                    </a-popconfirm>
                </template>
                <style lang="css" scoped>

                </style>


                EOT;
            break;
            case 'Tour':
                return <<<EOT
                <script>
                    export default {
                        name: "TourItem",
                    }
                </script>

                <script setup>
                    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
                    import { h } from 'vue';
                    import {  QuestionCircleOutlined,} from '@ant-design/icons-vue';
                    // 2. Props & Emits (defineProps, defineEmits)
                    // 3. State (ref, reactive)
                    // 4. Computed Properties
                    // 5. Methods & Logic (Functions, Handlers)
                    const handleHelp = () => alert("Help");

                    // 6. Watchers
                    // 7. Lifecycle Hooks (onMounted, etc.)
                    // 8. Expose (defineExpose)
                </script>
                <template>
                    <a-button 
                    
                        :icon="h(QuestionCircleOutlined)" 
                        @click="handleHelp" 
                    />
                </template>
                <style lang="css" scoped>

                </style>

                EOT;
            break;
            case 'useCreate':
                return <<<EOT
                    import { ref, watch } from 'vue';
                    import axios from 'axios';
                    import { message } from 'ant-design-vue';
                    import { useForm } from '@inertiajs/vue3';
                    import { capitalizeWords } from '@/helpers/helpers';

                    export function useCreate(modalOpen, config) {
                        // Crear formulario dinámicamente basado en la configuración
                        const initialFormData = {};

                        // Agregar campos del formulario principal
                        Object.keys(config.formFields).forEach(field => {
                            initialFormData[field] = '';
                        });

                        // Agregar campos adicionales para creación
                        if (config.createOnlyFields) {
                            Object.keys(config.createOnlyFields).forEach(field => {
                                initialFormData[field] = config.createOnlyFields[field].default || '';
                            });
                        }

                        const form = useForm(initialFormData);
                        const formRef = ref(null); 
                        
                        watch(
                            () => form.name,
                            (newVal) => {
                                form.name = capitalizeWords(newVal);
                            }
                        );
                        const handleSubmit = async () => {
                            if (!formRef.value) {
                                console.error('Error: formRef no está vinculado al componente.');
                            return;
                            }

                            try {
                                const values = await formRef.value.validate().catch((err) => {
                                    if (err.outOfDate && err.errorFields.length === 0) {
                                        return form.data();
                                    }
                                    throw err;
                                });
                                
                                await axios.post(route(config.modelNameKebabCase +'.store'), form.data());
                                message.success('¡Creado con éxito!');

                                form.reset();
                                modalOpen.value = false;
                        
                            } catch (error) {
                                if (error.response?.status === 422) {
                                    form.setError(error.response.data.errors);
                                    message.warning('Revisa los campos del formulario');
                                } else {
                                    const msg = error.response?.data?.message || 'Error inesperado';
                                    message.error(msg);
                                }
                            }
                        };
                        return {
                        form,
                        formRef,
                        handleSubmit,
                        };
                    }
                EOT;
            break;
            case 'useEdit':
                return <<<EOT
                    import { ref, watch } from 'vue';
                    import axios from 'axios';
                    import { message } from 'ant-design-vue';
                    import { useForm } from '@inertiajs/vue3';
                    import { capitalizeWords } from '@/helpers/helpers';

                    export function useEdit(item, modalOpen, config) {
                        // Crear formulario dinámicamente basado en la configuración
                        const initialFormData = {};
                        Object.keys(config.formFields).forEach(field => {
                            initialFormData[field] = item[field] || '';
                        });

                        const form = useForm(initialFormData);
                        const formRef = ref(null);

                        watch(
                            () => form.name,
                            (newVal) => {
                                form.name = capitalizeWords(newVal);
                            }
                        );

                        const handleSubmit = async () => {
                            if (!formRef.value) {
                                console.error('Error: formRef no está vinculado al componente.');
                                return;
                            }

                            try {
                                const values = await formRef.value.validate().catch((err) => {
                                    if (err.outOfDate && err.errorFields.length === 0) {
                                        return form.data();
                                    }
                                    throw err;
                                });

                                await axios.put(route(config.modelNameKebabCase +'.update', item.id), form.data());
                                message.success('¡Actualizado con éxito!');

                                form.reset();
                                modalOpen.value = false;
                            } catch (error) {
                                if (error.response?.status === 422) {
                                    form.setError(error.response.data.errors);
                                    message.warning('Revisa los campos del formulario');
                                } else {
                                    const msg = error.response?.data?.message || 'Error inesperado';
                                    message.error(msg);
                                }
                            }
                        };

                        return {
                            form,
                            formRef,
                            handleSubmit,
                        };
                    }

                EOT;
            break;
            case 'useIndex':
                return <<<EOT
                    import { ref } from 'vue';
                    import axios from 'axios';

                    export function useIndex(config) {
                        const loading = ref(false);
                        const drawerOpen = ref(false);
                        const dataSource = ref([]);
                        const searchText = ref('');
                        const pagination = ref({
                            total: 0,
                            page: 1,
                            pageSize: config.defaultPageSize || 8
                        });

                        
                        const handleTableChange = (pag, filters, sorter) => {
                            loadData({
                                results: pag.pageSize || pagination.value.pageSize,
                                page: pag.current,
                                sortField: sorter.field,
                                sortOrder: sorter.order,
                                ...filters,
                            });
                        };

                        const handleSearch = () => {
                            loadData({ page: 1 }); 
                        };
                        const handleRefresh = () => {
                            searchText.value='';
                            loadData({ current: 1 });
                        };
                        const loadData = async (params = {}) => {
                            if (params.current) pagination.value.current = params.current;
                            if (params.pageSize) pagination.value.pageSize = params.pageSize;
                            
                            const fetchParams = {
                                ...params,
                                searchText: params.searchText !== undefined 
                                    ? params.searchText 
                                    : searchText.value
                            };

                            const res = await fetchRoles(fetchParams);
                            if (res && res.data) {
                                dataSource.value = res.data.data || res.data;
                                const totalCount =  res.data.total 
                                                    || (Array.isArray(res.data) ? res.data.length : 0);
                                pagination.value.total = parseInt(totalCount);
                            }
                        };
                        const fetchRoles = async (params = {}) => {
                            loading.value = true;
                            try {
                                const page = params.page || pagination.value.page;
                                const pageSize = params.results || pagination.value.pageSize;
                                const searchText = params.searchText || '';

                                const res = await axios.get(route(config.modelNameKebabCase +'.data'), {
                                    params: {
                                        page: page,      
                                        pageSize: pageSize,    
                                        searchText: searchText,
                                    },
                                });
                                
                                // Sincronizamos el estado de la paginación
                                pagination.value.page = page;
                                pagination.value.pageSize = pageSize;
                                
                                return res;
                            } catch (error) {
                                console.error("Error obteniendo roles:", error);
                                throw error;
                            } finally {
                                loading.value = false;
                            }
                        };

                        return {
                            loading,
                            drawerOpen,
                            pagination,
                            dataSource,
                            searchText,
                            fetchRoles,
                            loadData,
                            handleRefresh,
                            handleTableChange,
                            handleSearch
                        };
                    }

                EOT;
            break;
        }
    }
    protected function createController($config)
    {
        $path = app_path("Http/Controllers/{$config['modelNameController']}.php");
        $content = $this->getTemplate('controller', $config['modelNameController'], $config['modelNameSingular']);
        File::put($path, $content);
        $this->line("   - Controlador {$config['modelNameController']} creado con boilerplate.");
    }
    protected function registerPermissions($modelName)
    {
        // Convertimos el modelo a kebab-case plural (ej: Product -> products)
        $permissionPart = Str::kebab(Str::plural($modelName));
        $actions = ['create', 'read', 'update', 'delete'];

        // Verificamos que el modelo de Spatie existe
        if (!class_exists(\Spatie\Permission\Models\Permission::class) || 
            !class_exists(\Spatie\Permission\Models\Role::class)) {
            $this->warn("   - No se pudieron asignar permisos: Modelos de Spatie no encontrados.");
            return;
        }

        // Buscamos el Rol del Administrador (ID 1)
        $adminRole = \Spatie\Permission\Models\Role::find(1);

        if (!$adminRole) {
            $this->error("   - No se encontró el Rol con ID 1. No se pudieron asignar los permisos.");
            return;
        }

        foreach ($actions as $action) {
            $permissionName = "{$action} {$permissionPart}";

            // 1. Crear el permiso si no existe
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web' // O el guard que utilices
            ]);

            // 2. Asociar al rol de administrador (role_has_permissions)
            if (!$adminRole->hasPermissionTo($permissionName)) {
                $adminRole->givePermissionTo($permission);
                $this->line("   - Permiso <info>{$permissionName}</info> creado y asignado al Administrador.");
            } else {
                $this->line("   - El permiso <comment>{$permissionName}</comment> ya estaba asignado.");
            }
        }
    }

    protected function generateBackendCustomStructures($model)
    {
        // --- RESOURCES ---
        $resourcePath = app_path("Http/Resources/{$model}");
        $this->createFolderAndFiles(
            $resourcePath, 
            ["StoreResource.php", "UpdateResource.php"], 
            'resource', 
            $model
        );

        // --- REQUESTS ---
        $requestPath = app_path("Http/Requests/{$model}");
        $this->createFolderAndFiles($requestPath, [
            "StoreRequest.php", "UpdateRequest.php"
        ], 'request', $model);

        // --- SERVICES ---
        $servicePath = app_path("Services/{$model}");
        $this->createFolderAndFiles(app_path("Services/{$model}"), ["StoreService.php"], 'store_service', $model);
        $this->createFolderAndFiles(app_path("Services/{$model}"), ["UpdateService.php"], 'update_service', $model);

        // --- EVENTS ---
        $eventPath = app_path("Events/{$model}");
        $this->createFolderAndFiles(
            $eventPath, 
            [ "CreatedEvent.php", "UpdatedEvent.php", "DeletedEvent.php"
        ], 'event', $model);

        // --- OBSERVER ---
        $this->createFolderAndFiles(
            app_path("Observers"), 
            ["{$model}Observer.php"], 
            'observer', $model
        );
        
        $this->line("   - Backend especializado (Resources, Requests, Services) creado.");
    }

    protected function createFolderAndFiles($path, $files, $type, $model)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        foreach ($files as $file) {
            $className = str_replace('.php', '', $file);
            $content = $this->getTemplate(
                $type, 
                $className, 
                $model
            );
            File::put("{$path}/{$file}", $content);
        }
    }

    protected function getTemplate($type, $className, $model)
    {
        $mymodel = strtolower($model);
        
        return match ($type) {
            'resource' => "<?php\n\nnamespace App\Http\Resources\\{$model};\n\nuse Illuminate\Http\Request;\nuse Illuminate\Http\Resources\Json\JsonResource;\n\nclass {$className} extends JsonResource\n{\n    /**\n     * Transform the resource into an array.\n     *\n     * @return array<string, mixed>\n     */\n    public function toArray(Request \$request): array\n    {\n        return [];\n    }\n}",
            
            'request' => "<?php\n\nnamespace App\Http\Requests\\{$model};\n\nuse Illuminate\Foundation\Http\FormRequest;\n\nclass {$className} extends FormRequest\n{\n    public function authorize(): bool\n    {\n        return true;\n    }\n\n    public function rules(): array\n    {\n        return [];\n    }\n}",
            
            'service' => "<?php\n\nnamespace App\Services\\{$model};\n\nclass {$className}\n{\n    public function execute(array \$data)\n    {\n        // Lógica de negocio\n    }\n}",
            
            'store_service' => "<?php\n\nnamespace App\Services\\{$model};\n\nuse App\Models\\{$model};\n\nclass StoreService\n{\n    /**\n     * Create a new {$model}\n     *\n     * @param array \$data\n     * @return {$model}\n     */\n    public function execute(array \$data): {$model}\n    {\n        return {$model}::create(\$data);\n    }\n}",

            'update_service' => "<?php\n\nnamespace App\Services\\{$model};\n\nuse App\Models\\{$model};\n\nclass UpdateService\n{\n    /**\n     * Update the specified {$model}\n     *\n     * @param {$model} \$modelInstance\n     * @param array \$data\n     * @return {$model}\n     */\n    public function execute({$model} \$modelInstance, array \$data): {$model}\n    {\n        \$modelInstance->update(\$data);\n        return \$modelInstance->fresh();\n    }\n}",

            'event' => "<?php\n\nnamespace App\Events\\{$model};\n\nuse Illuminate\Broadcasting\Channel;\nuse Illuminate\Broadcasting\InteractsWithSockets;\nuse Illuminate\Contracts\Broadcasting\ShouldBroadcast;\nuse Illuminate\Foundation\Events\Dispatchable;\nuse Illuminate\Queue\SerializesModels;\n\nclass {$className} implements ShouldBroadcast\n{\n\tuse Dispatchable, InteractsWithSockets, SerializesModels;\n\n\tpublic \$" . strtolower($model) . ";\n\n\tpublic function __construct(\$" . strtolower($model) . ")\n\t{\n\t\t\$this->" . strtolower($model) . " = \$" . strtolower($model) . ";\n\t}\n\n\tpublic function broadcastOn(): array\n\t{\n\t\treturn [\n\t\t\tnew Channel('" . Str::kebab(Str::plural($model)) . "'),\n\t\t];\n\t}\n\n\tpublic function broadcastAs(): string\n\t{\n\t\treturn '" . Str::kebab(Str::plural($model)) . "." . strtolower(str_replace('Event', '', $className))."';\n\t}\n}",

            'controller' => "<?php\n\nnamespace App\Http\Controllers;\n\nuse Illuminate\Http\Request;\nuse App\Models\\{$model};\nuse Symfony\Component\HttpFoundation\JsonResponse;\nuse App\Http\Requests\\{$model}\StoreRequest;\nuse App\Http\Requests\\{$model}\UpdateRequest;\nuse App\Http\Resources\\{$model}\StoreResource;\nuse App\Http\Resources\\{$model}\UpdateResource;\nuse App\Services\\{$model}\StoreService;\nuse App\Services\\{$model}\UpdateService;\n\nclass {$model}Controller extends Controller\n{\n\tpublic function data(Request \$request): JsonResponse\n\t{\n\t\t\$data = {$model}::query()\n\t\t\t->when(\$request->searchText, function(\$query, \$search) {\n\t\t\t\t\$query->where('name', 'like', \"%\$search%\");\n\t\t\t})\n\t\t\t->orderBy('created_at', 'desc')\n\t\t\t->paginate(\$request->pageSize ?? 7);\n\n\t\treturn response()->json(\$data);\n\t}\n\n\tpublic function index()\n\t{\n\t\treturn inertia('Dashboard/{$model}/Index');\n\t}\n\n\tpublic function create()\n\t{\n\t\t//\n\t}\n\n\tpublic function store(StoreRequest \$request, StoreService \$storeService): StoreResource|JsonResponse\n\t{\n\t\t\$result = \$storeService->execute(\$request->validated());\n\t\treturn new StoreResource(\$result);\n\t}\n\n\tpublic function show(string \$id)\n\t{\n\t\t//\n\t}\n\n\tpublic function edit(string \$id)\n\t{\n\t\t//\n\t}\n\n\tpublic function update(UpdateRequest \$request, UpdateService \$updateService, string \$id): UpdateResource\n\t{\n\t\t\$" . strtolower($model) . " = {$model}::findOrFail(\$id);\n\t\t\$result = \$updateService->execute(\$" . strtolower($model) . ", \$request->validated());\n\t\treturn new UpdateResource(\$result);\n\t}\n\n\tpublic function destroy(string \$id): JsonResponse\n\t{\n\t\t\$" . strtolower($model) . " = {$model}::findOrFail(\$id);\n\t\t\$" . strtolower($model) . "->delete();\n\t\treturn response()->json(['message' => 'Registro eliminado exitosamente.']);\n\t}\n}",

            'observer' => "<?php\n\nnamespace App\Observers;\n\nuse App\Models\\{$model};\nuse App\Events\\{$model}\\CreatedEvent;\nuse App\Events\\{$model}\\UpdatedEvent;\nuse App\Events\\{$model}\\DeletedEvent;\n\nclass {$className}\n{\n    public function created({$model} \${$mymodel}): void\n    {\n        event(new CreatedEvent(\${$mymodel}));\n    }\n\n    public function updated({$model} \${$mymodel}): void\n    {\n        event(new UpdatedEvent(\${$mymodel}));\n    }\n\n    public function deleted({$model} \${$mymodel}): void\n    {\n        event(new DeletedEvent(\${$mymodel}));\n    }\n\n    public function restored({$model} \${$mymodel}): void\n    {\n        //\n    }\n\n    public function forceDeleted({$model} \${$mymodel}): void\n    {\n        //\n    }\n}",            
            
            default => "<?php\n\nnamespace App\\{$type}\\{$model};\n\nclass {$className} {}"
        };
    }

    
    

    
    protected function registerRoute($modelName, $controllerName)
    {
        $webPath = base_path('routes/web.php');
        $routeName = Str::kebab(Str::plural($modelName));
        $controllerImport = "use App\Http\Controllers\\{$controllerName};";
        
        // Definimos el bloque de código a insertar con la identación adecuada
        $newRoutes = "\n    Route::get('/{$routeName}/data', [{$controllerName}::class, 'data'])->name('{$routeName}.data');" .
                    "\n    Route::resource('{$routeName}', {$controllerName}::class);\n";
        
        $content = File::get($webPath);

        // 1. Evitar duplicados
        if (!str_contains($content, "resource('{$routeName}'")) {
            
            // 2. Insertar Import del Controlador (al principio con los demás 'use')
            if (!str_contains($content, $controllerImport)) {
                $content = preg_replace('/(?<=^use\s).+?;/m', "$0\n{$controllerImport}", $content, 1);
            }

            // 3. Inserción lógica dentro del grupo
            // Buscamos la última ocurrencia de }); que cierra el grupo de middleware
            // Usamos una expresión regular que busque el cierre del grupo auth:sanctum
            $pattern = '/(auth:sanctum.+?group\(function\s*\(\)\s*\{)(.+?)(\s*\}\);)/s';

            if (preg_match($pattern, $content)) {
                // Insertamos las nuevas rutas justo antes del cierre '});' del grupo
                $content = preg_replace($pattern, "$1$2{$newRoutes}$3", $content);
            } else {
                // Si por alguna razón no detecta el grupo, lo añade al final para no perder el código
                $content .= "\n\n{$newRoutes}";
            }

            File::put($webPath, $content);
            $this->line("   - Rutas para '{$routeName}' insertadas dentro del grupo de seguridad.");
        }
    }
}
