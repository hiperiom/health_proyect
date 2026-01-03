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
                File::put("{$basePath}/Composables/{$file}.js", "export function {$file}() {\n    // Lógica\n}");
            }
            foreach (['createRules', 'editRules'] as $file) {
                File::put("{$basePath}/Utils/{$file}.js", "export const {$file} = {};");
            }
            $this->line("   - Frontend Vue creado.");
        }
    }

    protected function createVueFile($path, $fileName, $moduleName)
    {
        $componentName = "{$fileName}{$moduleName}";
        $template = <<<EOT
<script>
    export default {
        name: "{$componentName}",
    }
</script>
<script setup>
    // 1. Imports (Vue, Inertia, Ant Design, Icons, Components)
    // 2. Props & Emits (defineProps, defineEmits)
    // 3. State (ref, reactive)
    // 4. Computed Properties
    // 5. Methods & Logic (Functions, Handlers)
    // 6. Watchers
    // 7. Lifecycle Hooks (onMounted, etc.)
    // 8. Expose (defineExpose)
</script>

<template>
    <div class="{$componentName}">
        <h1>Componente {$componentName}</h1>
    </div>
</template>

<style scoped>
</style>
EOT;
        File::put("{$path}/{$fileName}.vue", $template);
    }

    protected function registerRoute($modelName, $controllerName)
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
            $content .= "\n{$routeLine}";
            File::put($webPath, $content);
            $this->line("   - Ruta resource '{$routeName}' añadida.");
        }
    }
}