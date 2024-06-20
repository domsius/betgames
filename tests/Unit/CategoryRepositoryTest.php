<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Category;
use App\Models\User;
use App\Repositories\CategoryRepository;

class CategoryRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected $categoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = new CategoryRepository();
    }

    public function testGetAllForUser()
    {
        $user = User::factory()->create();
        $categories = Category::factory()->count(3)->create(['user_id' => $user->id]);

        $result = $this->categoryRepository->getAllForUser($user);

        $this->assertCount(3, $result);
        $this->assertEquals($categories->pluck('id')->toArray(), $result->pluck('id')->toArray());
    }

    public function testCreateCategory()
    {
        $user = User::factory()->create();
        $categoryData = [
            'name' => 'Test Category',
            'user_id' => $user->id
        ];

        $category = $this->categoryRepository->create($categoryData);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertEquals('Test Category', $category->name);
        $this->assertEquals($user->id, $category->user_id);
    }

    public function testUpdateCategory()
    {
        $category = Category::factory()->create(['name' => 'Old Name']);
        $updateData = ['name' => 'New Name'];

        $updatedCategory = $this->categoryRepository->update($category, $updateData);

        $this->assertInstanceOf(Category::class, $updatedCategory);
        $this->assertEquals('New Name', $updatedCategory->name);
    }

    public function testDeleteCategory()
    {
        $category = Category::factory()->create();

        $result = $this->categoryRepository->delete($category);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}