<?php

namespace App\Repositories;

use App\Contracts\DataUserRepositoryInterface;
use App\Models\User;

class DataUserRepository implements DataUserRepositoryInterface
{
    public function getAllUserPaginated(int $perPage = 10)
    {
        return User::paginate($perPage);
    }
}
