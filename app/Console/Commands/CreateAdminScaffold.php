<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class CreateAdminScaffold extends Command
{
    protected $signature = 'make:new-module {name : El nombre del módulo/página/modelo}';
    protected $description = 'Scaffolding integral: Frontend Vue + Backend Completo (Model, Service, Request, Events, Resources)';

    public function handle()
    {
        $name = $this->argument('name');
        $moduleName = Str::studly($name); // Ejemplo: UserProfile
        $modelName = Str::singular($moduleName); // Ejemplo: UserProfile

        $this->info("🚀 Iniciando scaffolding integral para: {$moduleName}");

        // --- 1. FRONTEND (VUE) ---
        $this->generateFrontend($moduleName);

        // --- 2. BACKEND CORE (Model, Mig, Fact, Seed, Controller, Request) ---
        // Usamos --all para la base, luego personalizamos las carpetas de Request
        $this->call('make:model', ['name' => $modelName, '--all' => true]);

        // --- 3. ESTRUCTURAS PERSONALIZADAS (Services, Requests, Events, Resources, Observers) ---
        $this->generateBackendCustomStructures($modelName);

        // --- 4. REGISTRO EN PROVIDER ---
        $this->registerObserver($modelName);

        $this->info("✅ ¡Proceso completado! No olvides definir la ruta Route::resource en web.php o api.php");
    }

    protected function generateFrontend($moduleName)
    {
        $basePath = resource_path("js/Pages/Dashboard/Administrator/{$moduleName}");

        if (!File::exists($basePath)) {
            File::makeDirectory($basePath, 0755, true);
            File::makeDirectory("{$basePath}/Components", 0755, true);
            File::makeDirectory("{$basePath}/Composables", 0755, true);
            File::makeDirectory("{$basePath}/Utils", 0755, true);

            // Archivo Raíz
            $this->createVueFile($basePath, 'Index', $moduleName);

            // Componentes
            foreach (['Create', 'Edit', 'Delete', 'Tour'] as $comp) {
                $this->createVueFile("{$basePath}/Components", $comp, $moduleName);
            }

            // Composables
            foreach (['useCreate', 'useEdit', 'useIndex'] as $file) {
                File::put("{$basePath}/Composables/{$file}.js", "export function {$file}() {\n    // Lógica\n}");
            }

            // Utils
            foreach (['createRules', 'editRules'] as $file) {
                File::put("{$basePath}/Utils/{$file}.js", "export const {$file} = {};");
            }
            
            $this->line("   - Frontend Vue creado.");
        }
    }

    protected function generateBackendCustomStructures($model)
    {
        // SERVICES
        $this->createFolderAndFiles(app_path("Services/{$model}"), [
            "StoreService.php", "UpdateService.php", "DeleteService.php"
        ], "namespace App\Services\\{$model};\n\nclass ");

        // REQUESTS (Reorganizar el default de Laravel a carpeta)
        $this->createFolderAndFiles(app_path("Http/Requests/{$model}"), [
            "StoreRequest.php", "UpdateRequest.php"
        ], "namespace App\Http\Requests\\{$model};\n\nuse Illuminate\Foundation\Http\FormRequest;\n\nclass ");

        // EVENTS
        $this->createFolderAndFiles(app_path("Events/{$model}"), [
            "{$model}Created.php", "{$model}Updated.php", "{$model}Deleted.php"
        ], "namespace App\Events\\{$model};\n\nclass ");

        // RESOURCES
        $this->createFolderAndFiles(app_path("Http/Resources/{$model}"), [
            "{$model}Resource.php", "{$model}Collection.php"
        ], "namespace App\Http\Resources\\{$model};\n\nuse Illuminate\Http\Resources\Json\JsonResource;\n\nclass ");

        // OBSERVER
        $this->call('make:observer', ['name' => "{$model}Observer", '--model' => $model]);
        
        $this->line("   - Estructuras de Backend (Services, Events, Resources) creadas.");
    }

    protected function createFolderAndFiles($path, $files, $contentPrefix)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        foreach ($files as $file) {
            $className = str_replace('.php', '', $file);
            File::put("{$path}/{$file}", "<?php\n\n{$contentPrefix}{$className} \n{\n    //\n}");
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
    // 1. Imports
    // 2. Props & Emits
    // 3. State
    // 4. Computed
    // 5. Methods
    // 6. Watchers
    // 7. Lifecycle
    // 8. Expose
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

    protected function registerObserver($model)
    {
        $this->warn("⚠️  Recuerda registrar el Observer en AppServiceProvider.php:");
        $this->line("{$model}::observe({$model}Observer::class);");
    }
}