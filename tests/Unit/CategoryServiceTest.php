<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CategoryServiceInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Mockery;

class CategoryServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected $categoryService;
    protected $categoryRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->categoryRepositoryMock = Mockery::mock(CategoryRepositoryInterface::class);

        $this->app->instance(CategoryRepositoryInterface::class, $this->categoryRepositoryMock);

        $this->categoryService = $this->app->make(CategoryServiceInterface::class);
    }

    public function testGetAllCategories()
    {
        $user = User::factory()->create();
        $this->categoryRepositoryMock
            ->shouldReceive('getAllForUser')
            ->once()
            ->with($user)
            ->andReturn(collect([]));

        $categories = $this->categoryService->getAllCategories($user);

        $this->assertCount(0, $categories);
    }

    public function testStoreCategory()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $request = new Request([
            'name' => 'Test Category',
        ]);

        $categoryData = $request->only(['name']);
        $categoryData['user_id'] = $user->id;

        $this->categoryRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($categoryData)
            ->andReturn((new Category)->forceFill($categoryData));

        $category = $this->categoryService->storeCategory($request, $user);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Test Category', $category->name);
    }

    public function testUpdateCategory()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create([
            'name' => 'Old Category Name',
            'user_id' => $user->id,
        ]);

        $request = new Request([
            'name' => 'Updated Category Name',
        ]);

        $categoryData = $request->only(['name']);

        $this->categoryRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($category, $categoryData)
            ->andReturnUsing(function ($category, $categoryData) {
                $category->forceFill($categoryData);
                return $category;
            });

        $this->categoryService->updateCategory($request, $category, $user);

        $this->assertEquals('Updated Category Name', $category->name);
    }

    public function testDeleteCategory()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $this->categoryRepositoryMock
            ->shouldReceive('delete')
            ->once()
            ->with($category)
            ->andReturnUsing(function ($category) {
                $category->delete();
                return true;
            });

        $this->categoryService->deleteCategory($category, $user);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}