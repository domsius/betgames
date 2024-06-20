<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\TaskServiceInterface;
use App\Repositories\TaskRepositoryInterface;
use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use Mockery;

class TaskServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected $taskService;
    protected $taskRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskRepositoryMock = Mockery::mock(TaskRepositoryInterface::class);

        $this->app->instance(TaskRepositoryInterface::class, $this->taskRepositoryMock);

        $this->taskService = $this->app->make(TaskServiceInterface::class);
    }

    public function testGetAllTasksWithTasks()
    {
        $user = User::factory()->create();
        $tasks = Task::factory()->count(3)->create(['user_id' => $user->id]);

        $this->taskRepositoryMock
            ->shouldReceive('getAllForUser')
            ->once()
            ->with(Mockery::type(Request::class), $user)
            ->andReturn($tasks);

        $request = new Request();
        $result = $this->taskService->getAllTasks($request, $user);

        $this->assertCount(3, $result);
        $this->assertEquals($tasks->pluck('id'), $result->pluck('id'));
    }

    public function testGetAllTasksWithoutTasks()
    {
        $user = User::factory()->create();

        $this->taskRepositoryMock
            ->shouldReceive('getAllForUser')
            ->once()
            ->with(Mockery::type(Request::class), $user)
            ->andReturn(collect([]));

        $request = new Request();
        $result = $this->taskService->getAllTasks($request, $user);

        $this->assertCount(0, $result);
    }

    public function testStoreTask()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $request = new Request([
            'title' => 'Test Task',
            'description' => 'Task description',
            'due_date' => now()->addWeek(),
            'status' => 'pending',
            'priority' => 1,
            'category_id' => $category->id,
        ]);

        $taskData = $request->only(['title', 'description', 'due_date', 'status', 'priority', 'category_id']);
        $taskData['user_id'] = $user->id;

        $task = Task::create($taskData);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals($category->id, $task->category_id);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'category_id' => $category->id,
        ]);
    }

    public function testUpdateTask()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'title' => 'Old Task Title',
            'user_id' => $user->id,
        ]);

        $request = new Request([
            'title' => 'Updated Task Title',
        ]);

        $taskData = $request->all();

        $this->taskRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($task, $taskData)
            ->andReturnUsing(function ($task, $taskData) {
                $task->forceFill($taskData);
                return $task;
            });

        $this->taskService->updateTask($request, $task, $user);

        $this->assertEquals('Updated Task Title', $task->title);
    }

    public function testDeleteTask()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->taskRepositoryMock
            ->shouldReceive('delete')
            ->once()
            ->with($task)
            ->andReturnUsing(function ($task) {
                $task->delete();
                return true;
            });

        $this->taskService->deleteTask($task, $user);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function testFindByIdAuthorized()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->taskRepositoryMock
            ->shouldReceive('findById')
            ->once()
            ->with($task->id)
            ->andReturn($task);

        $result = $this->taskService->findById($task->id, $user);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals($task->id, $result->id);
    }

    public function testUnauthorizedFindById()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $this->taskRepositoryMock
            ->shouldReceive('findById')
            ->once()
            ->with($task->id)
            ->andReturn($task);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Unauthorized");

        $this->taskService->findById($task->id, $user);
    }

    public function testUnauthorizedUpdateTask()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $request = new Request(['title' => 'Updated Task Title']);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Unauthorized");

        $this->taskService->updateTask($request, $task, $user);
    }

    public function testUnauthorizedDeleteTask()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $otherUser->id]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Unauthorized");

        $this->taskService->deleteTask($task, $user);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}