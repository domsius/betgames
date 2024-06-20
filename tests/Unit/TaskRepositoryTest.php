<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use App\Repositories\TaskRepository;
use Illuminate\Http\Request;

class TaskRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected $taskRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->taskRepository = new TaskRepository();
    }

    public function testGetAllForUser()
    {
        $user = User::factory()->create();
        $tasks = Task::factory()->count(3)->create(['user_id' => $user->id]);

        $request = new Request();

        $result = $this->taskRepository->getAllForUser($request, $user);

        $this->assertCount(3, $result);
        $this->assertEquals($tasks->pluck('id')->toArray(), $result->pluck('id')->toArray());
    }

    public function testGetAllForUserWithStatusFilter()
    {
        $user = User::factory()->create();
        $status = 'in_progress';
        $tasks = Task::factory()->count(3)->create(['user_id' => $user->id, 'status' => $status]);
        Task::factory()->count(2)->create(['user_id' => $user->id, 'status' => 'pending']);

        $request = new Request(['status' => $status]);

        $result = $this->taskRepository->getAllForUser($request, $user);

        $this->assertCount(3, $result);
        foreach ($result as $task) {
            $this->assertEquals($status, $task->status);
        }
    }

    public function testGetAllForUserWithCategoryFilter()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $tasks = Task::factory()->count(3)->create(['user_id' => $user->id, 'category_id' => $category->id]);
        Task::factory()->count(2)->create(['user_id' => $user->id]);

        $request = new Request(['category' => $category->id]);

        $result = $this->taskRepository->getAllForUser($request, $user);

        $this->assertCount(3, $result);
        foreach ($result as $task) {
            $this->assertEquals($category->id, $task->category_id);
        }
    }

    public function testGetAllForUserWithPriorityFilter()
    {
        $user = User::factory()->create();
        $priority = 1;
        $tasks = Task::factory()->count(3)->create(['user_id' => $user->id, 'priority' => $priority]);
        Task::factory()->count(2)->create(['user_id' => $user->id, 'priority' => 2]);

        $request = new Request(['priority' => $priority]);

        $result = $this->taskRepository->getAllForUser($request, $user);

        $this->assertCount(3, $result);
        foreach ($result as $task) {
            $this->assertEquals($priority, $task->priority);
        }
    }

    public function testCreateTask()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Task description',
            'due_date' => now()->addWeek(),
            'status' => 'pending',
            'priority' => 1,
            'user_id' => $user->id,
            'category_id' => $category->id
        ];

        $task = $this->taskRepository->create($taskData);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals($user->id, $task->user_id);
    }

    public function testUpdateTask()
    {
        $task = Task::factory()->create(['title' => 'Old Task Title']);
        $updateData = ['title' => 'Updated Task Title'];

        $updatedTask = $this->taskRepository->update($task, $updateData);

        $this->assertInstanceOf(Task::class, $updatedTask);
        $this->assertEquals('Updated Task Title', $updatedTask->title);
    }

    public function testDeleteTask()
    {
        $task = Task::factory()->create();

        $result = $this->taskRepository->delete($task);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function testFindById()
    {
        $task = Task::factory()->create();

        $result = $this->taskRepository->findById($task->id);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals($task->id, $result->id);
    }
}