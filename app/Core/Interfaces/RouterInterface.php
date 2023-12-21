<?php

namespace App\Core\Interfaces;

use Illuminate\Routing\Router;

interface RouterInterface
{
    /**
     * Register routes on application.
     */
    public static function routes(Router $api): void;
}
