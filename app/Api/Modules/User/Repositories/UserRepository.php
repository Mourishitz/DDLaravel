<?php

namespace App\Api\Modules\User\Repositories;

use App\Api\Modules\User\Repositories\UserRepositoryInterface;
use App\Api\Modules\User\Models\UserModel;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function all(): Collection {
      return User::all();
    }
}