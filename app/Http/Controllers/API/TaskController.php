<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Services\TaskServiceInterface;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskServiceInterface $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        $tasks = $this->taskService->getAllTasks($request);
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $task = $this->taskService->storeTask($request);
        return response()->json($task, 201);
    }

    public function show($id)
    {
        $task = $this->taskService->findById($id);
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $this->taskService->updateTask($request, $task);
        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $this->taskService->deleteTask($task);
        return response()->json(null, 204);
    }
}