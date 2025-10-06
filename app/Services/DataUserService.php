<?php

namespace App\Services;

use App\Contracts\DataUserRepositoryInterface;

class DataUserService
{
    public function __construct(
        private readonly DataUserRepositoryInterface $dataUserRepo,
    ) {}

    public function getAllUsers(int $perPgae = 10)
    {
        return $this->dataUserRepo->getAllUserPaginated($perPgae);
    }
}
