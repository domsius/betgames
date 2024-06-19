<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryServiceInterface;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $user = Auth::user();
        $categories = $this->categoryService->getAllCategories($user);

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories|max:255',
        ]);

        $user = Auth::user();
        $this->categoryService->storeCategory($request, $user);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $user = Auth::user();
        if ($category->user_id !== $user->id) {
            return redirect()->route('categories.index')->with('error', 'Unauthorized');
        }

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
        ]);

        $user = Auth::user();
        $this->categoryService->updateCategory($request, $category, $user);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $user = Auth::user();
        $this->categoryService->deleteCategory($category, $user);

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}