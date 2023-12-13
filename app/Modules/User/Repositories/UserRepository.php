<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return ['user1', 'user2'];
    }

    public function find($id)
    {
        return 'user' . $id;
    }
}
