<?php
namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TaskService implements TaskServiceInterface
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasks(Request $request, User $user)
    {
        return $this->taskRepository->getAllForUser($request, $user);
    }

    public function findById($id, User $user)
    {
        $task = $this->taskRepository->findById($id);
        if ($task->user_id !== $user->id) {
            throw new \Exception("Unauthorized");
        }
        return $task;
    }

    public function storeTask(Request $request, User $user)
    {
        $taskData = $request->only(['title', 'description', 'due_date', 'status', 'priority']);
        $taskData['user_id'] = $user->id;
    
        if ($request->has('category_id')) {
            $taskData['category_id'] = $request->input('category_id');
        }
    
        return $this->taskRepository->create($taskData);
    }

    public function updateTask(Request $request, Task $task, User $user)
    {
        if ($task->user_id !== $user->id) {
            throw new \Exception("Unauthorized");
        }

        $taskData = $request->all();
        return $this->taskRepository->update($task, $taskData);
    }

    public function deleteTask(Task $task, User $user)
    {
        if ($task->user_id !== $user->id) {
            throw new \Exception("Unauthorized");
        }

        return $this->taskRepository->delete($task);
    }
}