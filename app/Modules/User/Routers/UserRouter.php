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
        $api->group(['prefix' => 'userss'], function (Router $api) {
            $api->put('/', self::CONTROLLER, 'index');
        });
    }

}
