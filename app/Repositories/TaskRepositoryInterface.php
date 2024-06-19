<?php
namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

interface TaskRepositoryInterface
{
    public function getAllForUser(Request $request, User $user);
    public function findById($id);
    public function create(array $data);
    public function update(Task $task, array $data);
    public function delete(Task $task);
}