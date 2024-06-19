<?php
namespace App\Services;

use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryService implements CategoryServiceInterface
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->getAll();
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);

        return $this->categoryRepository->create($request->all());
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
        ]);

        $this->categoryRepository->update($category, $request->all());
    }

    public function deleteCategory(Category $category)
    {
        $this->categoryRepository->delete($category);
    }
}