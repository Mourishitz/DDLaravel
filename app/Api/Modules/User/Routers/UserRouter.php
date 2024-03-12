<?php

namespace Api\Modules\User\Routers;

use Api\Modules\User\Controllers\UserController;
use App\Core\Interfaces\RouterInterface;
use Illuminate\Routing\Router;

class UserRouter implements RouterInterface
{
    private const CONTROLLER = UserController::class;

    public static function routes(Router $router): void
    {
        $router->group(['prefix' => 'user'], function (Router $api) {
            $api->post('/', [self::CONTROLLER, 'all']);
        });
    }
}
