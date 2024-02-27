<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeModule extends Command
{
    protected $signature = 'make:module {module_name}';

    protected $description = 'Create a new module folder inside app/Modules';

    public function handle()
    {
        $moduleName = $this->argument('module_name');
        $modulePath = app_path("Modules/{$moduleName}");

        if (File::exists($modulePath)) {
            $this->error("Module '{$moduleName}' already exists.");

            return;
        }

        File::makeDirectory($modulePath, 0755, true);

        $this->info("Module '{$moduleName}' created successfully.");
    }
}
