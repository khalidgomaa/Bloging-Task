<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface
{
    public function register(array $data): User;
    public function findByPhone(string $phone): ?User;
    public function update(User $user, array $data): bool;
    public function createToken(User $user): string;
}
