<?php
namespace App\Repositories;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\User;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllForUser(Request $request, User $user)
    {
        return Task::with('category')
            ->where('user_id', $user->id)
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->category, function ($query) use ($request) {
                return $query->where('category_id', $request->category);
            })
            ->when($request->priority, function ($query) use ($request) {
                return $query->where('priority', $request->priority);
            })
            ->get();
    }

    public function findById($id)
    {
        return Task::findOrFail($id);
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data)
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task)
    {
        return $task->delete();
    }
}