<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;

interface CategoryServiceInterface
{
    public function getAllCategories(User $user);
    public function storeCategory(Request $request, User $user);
    public function updateCategory(Request $request, Category $category, User $user);
    public function deleteCategory(Category $category, User $user);
}