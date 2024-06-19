<?php
namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Task;

interface TaskRepositoryInterface
{
    public function getAll(Request $request);
    public function findById($id);
    public function create(array $data);
    public function update(Task $task, array $data);
    public function delete(Task $task);
}