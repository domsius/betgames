<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\TaskServiceInterface;
use App\Repositories\TaskRepositoryInterface;
use App\Models\Task;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
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

    public function testGetAllTasks()
    {
        $user = User::factory()->create();
        $this->taskRepositoryMock
            ->shouldReceive('getAllForUser')
            ->once()
            ->with(Mockery::type(Request::class), $user)
            ->andReturn(collect([]));

        $request = new Request();
        $tasks = $this->taskService->getAllTasks($request, $user);

        $this->assertCount(0, $tasks);
    }

    public function testStoreTask()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = Category::factory()->create(['user_id' => $user->id]);

        $request = new Request([
            'title' => 'Test Task',
            'description' => 'Task description',
            'due_date' => now()->addWeek(),
            'status' => 'pending',
            'category_id' => $category->id,
            'priority' => 1,
        ]);

        $taskData = $request->only(['title', 'description', 'due_date', 'status', 'priority', 'category_id']);
        $taskData['user_id'] = $user->id;

        $this->taskRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($taskData)
            ->andReturn((new Task)->forceFill($taskData));

        $task = $this->taskService->storeTask($request, $user);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
    }

    public function testUpdateTask()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create([
            'title' => 'Old Task Title',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $request = new Request([
            'title' => 'Updated Task Title',
        ]);

        $taskData = $request->only(['title']);

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
        $category = Category::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create(['user_id' => $user->id, 'category_id' => $category->id]);

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

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}