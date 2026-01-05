<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateAdminScaffold extends Command
{
    protected $signature = 'make:new-module {name : El nombre del módulo/modelo}';
    protected $description = 'Scaffolding integral: Frontend Vue + Backend Completo + Rutas + Estructura de Clases';

    public function handle()
    {
        $name = $this->argument('name');
        $moduleName = Str::studly($name);
        $modelName = Str::singular($moduleName);
        $controllerName = "{$modelName}Controller";

        $this->info("🚀 Iniciando scaffolding integral para: {$moduleName}");

        // 1. FRONTEND (VUE)
        $this->generateFrontend($moduleName);

        // 2. BACKEND CORE (Model, Mig, Fact, Seed, Controller)
        $this->call('make:model', [
            'name' => $modelName,
            '--controller' => true,
            '--migration'  => true,
            '--factory'    => true,
            '--seed'       => true,
            '--policy'     => true,
        ]);
        $this->createController($modelName, $controllerName);

        // 3. ESTRUCTURAS PERSONALIZADAS (Services, Requests, Events, Resources, Observers)
        $this->generateBackendCustomStructures($modelName);

        // 4. REGISTRO AUTOMÁTICO DE RUTA EN WEB.PHP
        $this->registerRoute($modelName, $controllerName);

        // 5. AVISO DE OBSERVER
        $this->warn("\n⚠️  Recuerda registrar el Observer en AppServiceProvider.php:");
        $this->line("{$modelName}::observe({$modelName}Observer::class);");
        
        // 6. REGISTRO DE PERMISOS
        $this->registerPermissions($modelName);

        $this->info("\n✅ ¡Proceso completado con éxito!");
    }
    protected function createController($model, $controllerName)
    {
        $path = app_path("Http/Controllers/{$controllerName}.php");
        $content = $this->getTemplate('controller', $controllerName, $model);
        File::put($path, $content);
        $this->line("   - Controlador {$controllerName} creado con boilerplate.");
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
            ["StoreResource.php", "UpdatedResource.php"], 
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

            'event' => "<?php\n\nnamespace App\Events\\{$model};\n\nuse App\Models\\{$model};\nuse Illuminate\Broadcasting\Channel;\nuse Illuminate\Broadcasting\InteractsWithSockets;\nuse Illuminate\Contracts\Broadcasting\ShouldBroadcast;\nuse Illuminate\Foundation\Events\Dispatchable;\nuse Illuminate\Queue\SerializesModels;\n\nclass {$className} implements ShouldBroadcast\n{\n    use Dispatchable, InteractsWithSockets, SerializesModels;\n\n    public function __construct(public {$model} \$model) {}\n\n    public function broadcastOn(): array\n    {\n        return [new Channel('" . Str::kebab(Str::plural($model)) . "')];\n    }\n\n    public function broadcastAs(): string\n    {\n        return '" . Str::kebab(Str::plural($model)) . ".' . strtolower(str_replace('{$model}', '', '{$className}'));\n    }\n}",
            
            'controller' => "<?php\n\nnamespace App\Http\Controllers;\n\nuse App\Models\\{$model};\nuse Illuminate\Http\Request;\nuse Symfony\Component\HttpFoundation\JsonResponse;\nuse App\Http\Requests\\{$model}\StoreRequest;\nuse App\Http\Requests\\{$model}\UpdateRequest;\nuse App\Http\Resources\\{$model}\\StoreResource;\nuse App\Http\Resources\\{$model}\\UpdatedResource;\nuse App\Services\\{$model}\StoreService;\nuse App\Services\\{$model}\UpdateService;\n\nclass {$className} extends Controller\n{\n    public function data(Request \$request): JsonResponse\n    {\n        \$items = {$model}::query()->when(\$request->searchText, function(\$query, \$search) {\n            \$query->where('name', 'like', \"%{\$search}%\");\n        })->orderBy('created_at', 'desc')->paginate(\$request->pageSize ?? 7);\n        return response()->json(\$items);\n    }\n\n    public function index()\n    {\n        return inertia('Dashboard/Administrator/{$model}/Index');\n    }\n\n    public function store(StoreRequest \$request, StoreService \$service): StoreResource\n    {\n        \$result = \$service->execute(\$request->validated());\n        return new StoreResource(\$result);\n    }\n\n    public function update(UpdateRequest \$request, UpdateService \$service, \$id): UpdatedResource\n    {\n        \$modelInstance = {$model}::findOrFail(\$id);\n        \$result = \$service->execute(\$modelInstance, \$request->validated());\n        return new UpdatedResource(\$result);\n    }\n\n    public function destroy(\$id): JsonResponse\n    {\n        \$modelInstance = {$model}::findOrFail(\$id);\n        \$service->execute(\$modelInstance);\n        return response()->json(['message' => 'Eliminado exitosamente']);\n    }\n}",
            
            'observer' => "<?php\n\nnamespace App\Observers;\n\nuse App\Models\\{$model};\nuse App\Events\\{$model}\\CreatedEvent;\nuse App\Events\\{$model}\\UpdatedEvent;\nuse App\Events\\{$model}\\DeletedEvent;\n\nclass {$className}\n{\n    public function created({$model} \${$mymodel}): void\n    {\n        event(new CreatedEvent(\${$mymodel}));\n    }\n\n    public function updated({$model} \${$mymodel}): void\n    {\n        event(new UpdatedEvent(\${$mymodel}));\n    }\n\n    public function deleted({$model} \${$mymodel}): void\n    {\n        event(new DeletedEvent(\${$mymodel}));\n    }\n\n    public function restored({$model} \${$mymodel}): void\n    {\n        //\n    }\n\n    public function forceDeleted({$model} \${$mymodel}): void\n    {\n        //\n    }\n}",            
            
            default => "<?php\n\nnamespace App\\{$type}\\{$model};\n\nclass {$className} {}"
        };
    }

    protected function vueComponentTemplates($component,$moduleName=null){
        switch ($component) {
            case 'Index':
                return <<<EOT
                <script>
                    const modelTitle = "{$moduleName}";
                    const modelName = "{$moduleName}Index";
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
                        { title: 'Nombres', dataIndex: 'first_names'},
                        { title: 'Apellidos', dataIndex: 'last_names' },
                        { title: 'Cédula', dataIndex: 'dni' },
                        { title: 'Correo', dataIndex: 'email' },
                        { title: 'Nacimiento', dataIndex: 'birthday' },
                        { title: 'Género', dataIndex: 'gender' },

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
                            channel.listen("." + modelName.toLowerCase() + "." + event, () => {
                                loadData();
                            });
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
                                        <template v-if="column.dataIndex === 'first_names'">
                                            {{ record.profile.first_names }}
                                        </template>
                                        <template v-if="column.dataIndex === 'last_names'">
                                            {{ record.profile.last_names }}
                                        </template>
                                        <template v-if="column.dataIndex === 'birthday'">
                                            {{ record.profile.birthday }}
                                        </template>
                                        <template v-if="column.dataIndex === 'gender'">
                                            {{ record.profile.gender.toUpperCase() }}
                                        </template>
                                    
                                        
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
                <style></style>
                EOT;
            break;
            case 'Create':
                return <<<EOT
                    <script>
                        const modelTitle = "{$moduleName}";
                        export default {
                            name: "CreateItem",
                        }
                    </script>
                    <script setup>
                        // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
                        import { h, onUnmounted, ref } from 'vue';
                        import { PlusOutlined } from '@ant-design/icons-vue';
                        
                        import Modal from '@/Components/Modal.vue';
                        import Spinner from '@/Components/Spinner.vue';

                        import { useCreate } from '../Composables/useCreate';
                        import { getCreateRules } from '../Utils/createRules';

                        // 2. Props & Emits (defineProps, defineEmits)
                        const props = defineProps({
                            modelName: {
                                type: String,
                                required: true,
                            },
                        });
                        // 3. State (ref, reactive)
                        const modalOpen = ref(false);

                        const { 
                        form, 
                        formRef, 
                        handleSubmit 
                        } = useCreate(modalOpen,props.modelName);

                        const rulesForm = getCreateRules(form);

                        // 4. Computed Properties
                        // 5. Methods & Logic (Functions, Handlers)
                        const handleModal = () => {
                            form.reset();
                            modalOpen.value = true;
                        };
                        const handleCancelForm = () => {
                        form.reset();
                        //drawerOpen.value = false;
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
                    <a-button 
                        :icon="h(PlusOutlined)" 
                        type="primary" 
                        @click="handleModal(true)"
                    >
                        Nuevo {{ modelTitle }}
                    </a-button>
                    <Modal
                        :title="'Crear ' + modelTitle"
                        :openModal="modalOpen"      
                        @handleCancelForm="handleCancelForm"
                    >
                        <template #content>
                            <Spinner :loading="form.processing" >
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <a-form ref="formRef" layout="vertical" :model="form" :rules="rulesForm" @submit.prevent="handleSubmit">
                                    <a-row id="tour-identity" justify="center" :gutter="10" :wrap="true">
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
                                        <a-form-item name="dni" ref="dni" has-feedback label="Cédula">
                                            <a-input name="dni" :maxlength="8" v-model:value="form.dni" placeholder="Escribe aquí tu cédula" />
                                        </a-form-item>
                                        </a-col>
                                        <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
                                        <a-form-item name="email" ref="email" has-feedback label="Correo electrónico">
                                            <a-input name="email" v-model:value="form.email" placeholder="Escribe aquí tu correo electrónico" />
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
                    <style lang="scss" scoped>
                    </style>
                EOT;
            break;
            case 'Edit':
                return <<<EOT
                    <script>
                        const modelTitle = "Usuario";
                        export default {
                            name: "EditItem",
                        }
                    </script>
                    <script setup>
                        // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
                        import { h, ref, watch } from 'vue';
                        import { EditOutlined } from '@ant-design/icons-vue';
                        import { useEdit } from '../Composables/useEdit';
                        import { getEditRules } from '../Utils/editRules';
                        import Spinner from '@/Components/Spinner.vue';
                        import Modal from '@/Components/Modal.vue';

                        // 2. Props & Emits (defineProps, defineEmits)
                        const props = defineProps({
                            item: {
                                type: Object,
                                required: true,
                            },
                            modelName: {
                                type: String,
                                required: true,
                            },
                        });

                        // 3. State (ref, reactive)
                        const modalOpen = ref(false);
                        const {
                            form,
                            formRef,
                            handleSubmit
                        } = useEdit(props.item,modalOpen,props.modelName);

                        const rulesForm = getEditRules(form);
                        const genderOptions = [
                            {
                            value: 'm',
                            label: 'Masculino',
                            },
                            {
                            value: 'f',
                            label: 'Femenino',
                            },
                        ];

                        // 4. Computed Properties
                        // 5. Methods & Logic (Functions, Handlers)
                        const handleModal = () => {
                            modalOpen.value = true;
                        };
                        const handleCancelForm = () => {
                        form.reset();
                        modalOpen.value = false;
                        };

                        // 6. Watchers
                        watch(() => props.item, (newItem) => {
                            if (newItem) {
                                form.first_names = newItem.profile.first_names || '';
                                form.last_names = newItem.profile.last_names || '';
                                form.dni = newItem.dni || '';
                                form.email = newItem.email || '';
                                form.birthday = newItem.profile.birthday || '';
                                form.gender = newItem.profile.gender || '';
                            }
                        }, { immediate: true });

                        // 7. Lifecycle Hooks (onMounted, etc.)
                        // 8. Expose (defineExpose)
                    </script>

                    <template>
                        <a-button 
                            type="link" 
                            @click="handleModal(true)"
                        >
                            <EditOutlined />
                        </a-button>
                        <Modal
                            :title="'Editar ' + modelTitle"
                            :openModal="modalOpen"      
                            @handleCancelForm="handleCancelForm"
                        >
                            <template #content>
                                <Spinner :loading="form.processing" >
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <a-form ref="formRef" layout="vertical" :model="form" :rules="rulesForm" @submit.prevent="handleSubmit">
                                        <a-row id="tour-identity" justify="center" :gutter="10" :wrap="true">
                                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
                                            <a-form-item name="dni" ref="dni" has-feedback label="Cédula">
                                                <a-input name="dni" :maxlength="8" v-model:value="form.dni" placeholder="Escribe aquí tu cédula" />
                                            </a-form-item>
                                            </a-col>
                                            <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12" :xxl="12">
                                            <a-form-item name="email" ref="email" has-feedback label="Correo electrónico">
                                                <a-input name="email" v-model:value="form.email" placeholder="Escribe aquí tu correo electrónico" />
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
                    <style lang="scss" scoped>
                    </style>
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
                            modelName: {
                                type: String,
                                required: true,
                            },
                        });

                        // 3. State (ref, reactive)
                        // 4. Computed Properties
                        // 5. Methods & Logic (Functions, Handlers)
                        const handleDelete = async () => {
                            try {
                                await axios.delete(route(props.modelName.toLowerCase() +'.destroy', props.item));
                                message.success('Registro eliminado exitosamente.');
                            } catch (error) {
                                console.log(error);
                                const msg = error.response?.data?.message || 'Error al eliminar el registro.';
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
                            title="¿Quieres eliminar el registro?"
                            ok-text="Si"
                            cancel-text="No"
                            @confirm="handleDelete"
                        >
                            <a href="#"><DeleteOutlined /></a>
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
                import { capitalizeWords, normalizeText } from '@/helpers/helpers';

                export function useCreate(modalOpen, modelName) {
                    const form = useForm({
                        dni: '',
                        email: '',
                        password: '12345678',
                        password_confirmation: '12345678',
                        first_names: '',
                        last_names: '',
                        gender: null,
                        birthday: '',
                    });
                    const formRef = ref(null); 
                    
                    watch(
                        () => form.first_names,
                        (newVal) => {
                        form.first_names = capitalizeWords(newVal);
                        }
                    );
                    watch(
                        () => form.last_names,
                        (newVal) => {
                        form.last_names = capitalizeWords(newVal);
                        }
                    );
                    watch(
                        () => form.email,
                        (newVal) => {
                        form.email = normalizeText(newVal);
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
                            
                            await axios.post(route(modelName.toLowerCase() +'.store'), form.data());
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
                import { capitalizeWords, normalizeText } from '@/helpers/helpers';

                export function useEdit(item,modalOpen,modelName) {
                    const form = useForm({
                        dni: '',
                        email: '',
                        first_names: '',
                        last_names: '',
                        gender: null,
                        birthday: '',
                    });
                    const formRef = ref(null);

                    watch(
                        () => form.first_names,
                        (newVal) => {
                        form.first_names = capitalizeWords(newVal);
                        }
                    );
                    watch(
                        () => form.last_names,
                        (newVal) => {
                        form.last_names = capitalizeWords(newVal);
                        }
                    );
                    watch(
                        () => form.email,
                        (newVal) => {
                        form.email = normalizeText(newVal);
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

                            await axios.put(route(modelName.toLowerCase() +'.update', item.id), form.data());
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

                export function useIndex(modelName) {
                    const loading = ref(false);
                    const dataSource = ref([]);
                    const searchText = ref('');
                    const pagination = ref({
                        total: 0,
                        page: 1,
                        pageSize: 8
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

                        const res = await fetchRecords(fetchParams);
                        if (res && res.data) {
                            dataSource.value = res.data.data || res.data;
                            const totalCount =  res.data.total 
                                                || (Array.isArray(res.data) ? res.data.length : 0);
                            pagination.value.total = parseInt(totalCount);
                        }
                    };
                    
                    const fetchRecords = async (params = {}) => {
                        loading.value = true;
                        try {
                            const page = params.page || pagination.value.page;
                            const pageSize = params.results || pagination.value.pageSize;
                            const searchText = params.searchText || '';

                            const res = await axios.get(route(modelName.toLowerCase() +'.data'), {
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
                            console.error("Error obteniendo registros:", error);
                            throw error;
                        } finally {
                            loading.value = false;
                        }
                    };

                    return {
                        loading,
                        pagination,
                        dataSource,
                        searchText,
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
    protected function generateFrontend($moduleName)
    {
        $basePath = resource_path("js/Pages/Dashboard/Administrator/{$moduleName}");
        if (!File::exists($basePath)) {
            File::makeDirectory($basePath, 0755, true);
            File::makeDirectory("{$basePath}/Components", 0755, true);
            File::makeDirectory("{$basePath}/Composables", 0755, true);
            File::makeDirectory("{$basePath}/Utils", 0755, true);

            $this->createVueFile($basePath, 'Index', $moduleName);
            foreach (['Create', 'Edit', 'Delete', 'Tour'] as $comp) {
                $this->createVueFile("{$basePath}/Components", $comp, $moduleName);
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

    protected function createVueFile($path, $fileName='Index', $moduleName)
    {
        $componentName = "{$fileName}{$moduleName}";
        $template = $this->vueComponentTemplates($fileName, $moduleName);
        File::put("{$path}/{$fileName}.vue", $template);
    }

    /* protected function registerRoute($modelName, $controllerName)
    {
        $webPath = base_path('routes/web.php');
        $routeName = Str::kebab(Str::plural($modelName));
        $controllerImport = "use App\Http\Controllers\\{$controllerName};";
        $routeLine = "Route::resource('{$routeName}', {$controllerName}::class);";
        $content = File::get($webPath);
        if (!str_contains($content, $routeLine)) {
            if (!str_contains($content, $controllerImport)) {
                $content = preg_replace('/^<\?php/', "<?php\n\n{$controllerImport}", $content);
            }
            
            $content .= "\nRoute::get('/{$routeName}/data', [{$controllerName}::class, 'data'])->name('{$routeName}/data');". "\n{$routeLine}";
            File::put($webPath, $content);
            $this->line("   - Ruta resource '{$routeName}' añadida.");
        }
    } */
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