<?php
namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

interface TaskServiceInterface
{
    public function getAllTasks(Request $request, User $user);
    public function findById($id, User $user);
    public function storeTask(Request $request, User $user);
    public function updateTask(Request $request, Task $task, User $user);
    public function deleteTask(Task $task, User $user);
}