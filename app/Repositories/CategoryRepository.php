<?php
namespace App\Repositories;

use App\Models\Category;
use App\Models\User;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllForUser(User $user)
    {
        return Category::where('user_id', $user->id)->get();
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data)
    {
        $category->update($data);
        return $category;
    }

    public function delete(Category $category)
    {
        return $category->delete();
    }
}