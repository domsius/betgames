<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Services\TaskServiceInterface;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        $categories = Category::all();
        $user = Auth::user();
        $tasks = $this->taskService->getAllTasks($request, $user);

        return view('tasks.index', compact('tasks', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
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

    public function show($id)
    {
        $user = Auth::user();
        $task = $this->taskService->findById($id, $user);

        return view('tasks.show', compact('task'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $task = $this->taskService->findById($id, $user);
        $categories = Category::all();

        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, $id)
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
        $task = $this->taskService->findById($id, $user);
        $this->taskService->updateTask($request, $task, $user);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $task = $this->taskService->findById($id, $user);
        $this->taskService->deleteTask($task, $user);

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}