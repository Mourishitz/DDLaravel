<?php

namespace App\Modules\User\Interfaces;

use App\Core\Interfaces\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function all();

    public function find($id);
}
