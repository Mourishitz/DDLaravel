<?php

namespace App\Modules\User\Routers;

use Illuminate\Routing\Router;
use App\Core\Interfaces\RouterInterface;
use App\Modules\User\Controllers\UserController;

class UserRouter implements RouterInterface
{
    private const CONTROLLER = UserController::class;

    public static function routes(Router $api): void
    {
        $api->group(['prefix' => 'testee'], function (Router $api) {
            $api->put('/usert', [self::CONTROLLER, 'index']);
        });
    }

}
