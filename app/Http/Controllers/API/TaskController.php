<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
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
        $user = Auth::user();
        $tasks = $this->taskService->getAllTasks($request, $user);
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $task = $this->taskService->storeTask($request, $user);
        return response()->json($task, 201);
    }

    public function show($id)
    {
        $user = Auth::user();
        $task = $this->taskService->findById($id, $user);
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $task = Task::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $this->taskService->updateTask($request, $task, $user);
        return response()->json($task);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $task = Task::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $this->taskService->deleteTask($task, $user);
        return response()->json(null, 204);
    }
}