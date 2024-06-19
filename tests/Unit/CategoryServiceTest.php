<?php
namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CategoryServiceInterface;
use App\Models\Category;
use Illuminate\Http\Request;
use Mockery;
use App\Repositories\CategoryRepositoryInterface;

class CategoryServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected $categoryService;
    protected $categoryRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the CategoryRepositoryInterface
        $this->categoryRepositoryMock = Mockery::mock(CategoryRepositoryInterface::class);

        // Bind the mock to the service container
        $this->app->instance(CategoryRepositoryInterface::class, $this->categoryRepositoryMock);

        // Resolve the CategoryServiceInterface from the container
        $this->categoryService = $this->app->make(CategoryServiceInterface::class);
    }

    public function testGetAllCategories()
    {
        $this->categoryRepositoryMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn(collect([]));

        $categories = $this->categoryService->getAllCategories();

        $this->assertCount(0, $categories);
    }

    public function testStoreCategory()
    {
        $request = new Request([
            'name' => 'Test Category',
        ]);

        $this->categoryRepositoryMock
            ->shouldReceive('create')
            ->once()
            ->with($request->all())
            ->andReturn((new Category)->forceFill($request->all()));

        $category = $this->categoryService->storeCategory($request);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Test Category', $category->name);
    }

    public function testUpdateCategory()
    {
        $category = Category::factory()->create([
            'name' => 'Old Category Name',
        ]);

        $request = new Request([
            'name' => 'Updated Category Name',
        ]);

        $this->categoryRepositoryMock
            ->shouldReceive('update')
            ->once()
            ->with($category, $request->all())
            ->andReturnUsing(function ($category, $data) {
                $category->forceFill($data)->save();
                return $category;
            });

        $this->categoryService->updateCategory($request, $category);

        $this->assertEquals('Updated Category Name', $category->name);
    }

    public function testDeleteCategory()
    {
        $category = Category::factory()->create();

        $this->categoryRepositoryMock
            ->shouldReceive('delete')
            ->once()
            ->with($category)
            ->andReturnUsing(function ($category) {
                $category->delete();
                return true;
            });

        $this->categoryService->deleteCategory($category);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}