<?php

use App\Core\Routers\RouteFactory;

$router = new RouteFactory();

$router->registerRoutes([
    App\Modules\User\Routers\UserRouter::class,
]);
