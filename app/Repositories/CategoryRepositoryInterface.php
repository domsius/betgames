<?php
namespace App\Repositories;

use App\Models\Category;
use App\Models\User;

interface CategoryRepositoryInterface
{
    public function getAllForUser(User $user);
    public function create(array $data);
    public function update(Category $category, array $data);
    public function delete(Category $category);
}