<?php

namespace App\Core\Interfaces;

use Illuminate\Routing\Router;

interface RouterInterface
{
    /**
     * Register routes on application.
     *
     * @param Router $api
     */
    public static function routes(Router $api): void;
}
