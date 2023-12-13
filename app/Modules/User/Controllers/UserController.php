<?php

namespace App\Modules\User\Controllers;

use App\Modules\User\Interfaces\UserRepositoryInterface;

class UserController
{

    public function __construct(
        protected UserRepositoryInterface $repository,
    ) {
    }

    public function index()
    {
        return $this->repository->all();
    }

    public function show($id)
    {
        return $this->repository->find($id);
    }
}
