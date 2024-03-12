<?php

namespace App\Console\Commands;

use App\Core\Scripts\ModuleBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModule extends Command
{
    protected $signature = 'make:module {module_name}';

    protected $description = 'Create a new module folder inside app/Api/Modules';

    private string $moduleName;

    private string $modulePath;

    public function handle()
    {
        $this->moduleName = $this->argument('module_name');
        $this->modulePath = app_path("Api/Modules/{$this->moduleName}");

        if (File::exists($this->modulePath)) {
            $this->error("Module '{$this->moduleName}' already exists.");

            return;
        }

        File::makeDirectory($this->modulePath, 0755, true);
        $this->info("Module '{$this->moduleName}' created successfully.");
        $this->info('Generating subdirectories for '.$this->moduleName);
        $this->generateSubmodules(new ModuleBuilder);
    }

    private function generateSubmodules(ModuleBuilder $moduleBuilder)
    {
        $moduleBuilder
            ->build($this->moduleName)
            ->onPath($this->modulePath)
            ->router()
            ->controller()
            // ->request()
            // ->dto()
            ->repository()
            // ->model()
            // ->resource()
            ->withInterfaces(for: [
                'Repository',
            ]);
    }
}
