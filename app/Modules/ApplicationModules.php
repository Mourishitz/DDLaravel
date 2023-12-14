<?php

namespace App\Modules;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use App\Core\Interfaces\ModuleInterface;

class ApplicationModules extends ServiceProvider
{
    private const MODULES = [
        User\UserModule::class,
    ];

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
