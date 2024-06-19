<?php
namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\User;

class CategoryService implements CategoryServiceInterface
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories(User $user)
    {
        return $this->categoryRepository->getAllForUser($user);
    }

    public function storeCategory(Request $request, User $user)
    {
        $categoryData = $request->only(['name']);
        $categoryData['user_id'] = $user->id;

        return $this->categoryRepository->create($categoryData);
    }

    public function updateCategory(Request $request, Category $category, User $user)
    {
        if ($category->user_id !== $user->id) {
            throw new \Exception("Unauthorized");
        }

        $categoryData = $request->only(['name']);
        return $this->categoryRepository->update($category, $categoryData);
    }

    public function deleteCategory(Category $category, User $user)
    {
        if ($category->user_id !== $user->id) {
            throw new \Exception("Unauthorized");
        }

        return $this->categoryRepository->delete($category);
    }
}