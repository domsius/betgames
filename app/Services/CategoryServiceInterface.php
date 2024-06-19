<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Category;

interface CategoryServiceInterface
{
    public function getAllCategories();
    public function storeCategory(Request $request);
    public function updateCategory(Request $request, Category $category);
    public function deleteCategory(Category $category);
}