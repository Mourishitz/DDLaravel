<?php

namespace App\Modules\User;

use App\Core\Interfaces\ModuleInterface;
use App\Modules\User\Repositories\UserRepository;
use App\Modules\User\Interfaces\UserRepositoryInterface;

class UserModule implements ModuleInterface
{

    public static function interface(): string
    {
        return UserRepositoryInterface::class;
    }

    public static function repository(): string
    {
        return UserRepository::class;
    }
}
