<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Services\TaskServiceInterface;
use App\Services\CategoryServiceInterface;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $taskService;
    protected $categoryService;

    public function __construct(TaskServiceInterface $taskService, CategoryServiceInterface $categoryService)
    {
        $this->taskService = $taskService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $categories = $this->categoryService->getAllCategories($user);
        $tasks = $this->taskService->getAllTasks($request, $user);

        return view('tasks.index', compact('tasks', 'categories'));
    }

    public function create()
    {
        $user = Auth::user();
        $categories = $this->categoryService->getAllCategories($user);

        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'category_id' => 'nullable|exists:categories,id',
            'priority' => 'required|integer',
        ]);

        $user = Auth::user();
        $this->taskService->storeTask($request, $user);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $user = Auth::user();
        $categories = $this->categoryService->getAllCategories($user);

        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'category_id' => 'nullable|exists:categories,id',
            'priority' => 'required|integer',
        ]);

        $user = Auth::user();
        $this->taskService->updateTask($request, $task, $user);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $user = Auth::user();
        $this->taskService->deleteTask($task, $user);

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}