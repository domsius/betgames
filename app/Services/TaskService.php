<?php
namespace App\Services;

use App\Repositories\TaskRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskService implements TaskServiceInterface
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasks(Request $request)
    {
        return $this->taskRepository->getAll($request);
    }

    public function storeTask(Request $request)
    {
        $taskData = $request->only(['title', 'description', 'due_date', 'status', 'priority']);

        if ($request->has('category_id')) {
            $taskData['category_id'] = $request->category_id;
        }

        return $this->taskRepository->create($taskData);
    }

    public function findById($id)
    {
        return $this->taskRepository->findById($id);
    }

    public function updateTask(Request $request, Task $task)
    {
        $taskData = $request->only(['title', 'description', 'due_date', 'status', 'priority', 'category_id']);
        $this->taskRepository->update($task, $taskData);
    }

    public function deleteTask(Task $task)
    {
        $this->taskRepository->delete($task);
    }
}