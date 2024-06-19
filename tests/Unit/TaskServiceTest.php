<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\TaskServiceInterface;
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

        $this->taskRepositoryMock = Mockery::mock(\App\Repositories\TaskRepositoryInterface::class);

        $this->app->instance(\App\Repositories\TaskRepositoryInterface::class, $this->taskRepositoryMock);

        $this->taskService = $this->app->make(TaskServiceInterface::class);
    }

    public function testGetAllTasks()
    {
        $this->taskRepositoryMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn(collect([]));

        $request = new Request();
        $tasks = $this->taskService->getAllTasks($request);

        $this->assertCount(0, $tasks);
    }

    public function testStoreTask()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $category = Category::factory()->create();

        $request = new Request([
            'title' => 'Test Task',
            'description' => 'Task description',
            'due_date' => now()->addWeek(),
            'status' => 'pending',
            'category_id' => $category->id,
            'priority' => 1,
        ]);

        $taskData = $request->only(['title', 'description', 'due_date', 'status', 'priority', 'category_id']);

        $this->taskRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($taskData)
            ->andReturn((new Task)->forceFill($taskData));

        $task = $this->taskService->storeTask($request);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function testUpdateTask()
    {
        $task = Task::factory()->create([
            'title' => 'Old Task Title',
        ]);

        $request = new Request([
            'title' => 'Updated Task Title',
        ]);

        $taskData = $request->only(['title', 'description', 'due_date', 'status', 'priority', 'category_id']);

        $this->taskRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($task, $taskData)
            ->andReturnUsing(function ($task, $taskData) {
                $task->forceFill($taskData);
                return $task;
            });

        $this->taskService->updateTask($request, $task);

        $this->assertEquals('Updated Task Title', $task->title);
    }

    public function testDeleteTask()
    {
        $task = Task::factory()->create();

        $this->taskRepositoryMock
            ->shouldReceive('delete')
            ->once()
            ->with($task)
            ->andReturnUsing(function ($task) {
                $task->delete();
                return true;
            });

        $this->taskService->deleteTask($task);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}