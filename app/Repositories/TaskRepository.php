<?php
namespace App\Repositories;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAll(Request $request)
    {
        return Task::with('category')
            ->when($request->status, function($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->category, function($query) use ($request) {
                return $query->where('category_id', $request->category);
            })
            ->when($request->priority, function($query) use ($request) {
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
    }

    public function delete(Task $task)
    {
        $task->delete();
    }
}