<?php

namespace App\Api\Modules\User\Controllers;

class UserController
{
    public function __construct(
        protected UserRepositoryInterface $repository,
    ) {
    }

    public function getAll()
    {
        return $this->repository->all();
    }
}
