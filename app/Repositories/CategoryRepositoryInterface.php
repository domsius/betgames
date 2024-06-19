<?php
namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update(Category $category, array $data);
    public function delete(Category $category);
}