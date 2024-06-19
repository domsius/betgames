<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Task;

interface TaskServiceInterface
{
    public function getAllTasks(Request $request);
    public function findById($id);
    public function storeTask(Request $request);
    public function updateTask(Request $request, Task $task);
    public function deleteTask(Task $task);
}