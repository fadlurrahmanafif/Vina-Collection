<?php

namespace App\Contracts;

interface DataUserRepositoryInterface
{
    public function getAllUserPaginated(int $perPage = 10);
}
