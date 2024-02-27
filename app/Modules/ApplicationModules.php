<?php

namespace App\Modules;

use App\Core\Interfaces\ModuleInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class ApplicationModules extends ServiceProvider
{
    private const MODULES = [];

    /**
     * Register application Modules.
     */
    public function register(): void
    {
        Arr::map(self::MODULES, function (string $module) {
            /** @var ModuleInterface $module */
            $this->app->bind($module::interface(), $module::repository());
        });
    }
}
