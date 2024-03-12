<?php

namespace App\Api\Modules\User\Interfaces;

use App\Core\Interfaces\RepositoryInterface;
use App\Api\User\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function all(): Collection;
}
