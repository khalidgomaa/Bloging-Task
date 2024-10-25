<?php

namespace App\Repositories\Tag;

use App\Models\User;

use App\Models\Tag;

interface TagRepositoryInterface
{
    public function all();
    public function create(array $data): Tag;
    public function update(Tag $tag, array $data): Tag;
    public function delete(Tag $tag): bool;
    public function findById($id): Tag;
}