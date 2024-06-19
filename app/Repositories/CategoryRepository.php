<?php
namespace App\Repositories;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAll()
    {
        return Category::latest()->get();
    }

    public function findById($id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data)
    {
        $category->update($data);
    }

    public function delete(Category $category)
    {
        $category->delete();
    }
}