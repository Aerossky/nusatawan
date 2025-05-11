<?php

namespace Tests\Untest_it\Services;

use App\Models\Category;
use App\Models\Destination;
use App\Models\User;
use App\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;
use Exception;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CategoryService $categoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryService = new CategoryService();
    }


    public function test_it_can_get_all_categories()
    {
        // Arrange
        Category::factory()->count(3)->create();

        // Act
        $result = $this->categoryService->getAllCategories();

        // Assert
        $this->assertCount(3, $result);
        $this->assertInstanceOf(Category::class, $result->first());
        $this->assertArrayHasKey('destinations_count', $result->first()->toArray());
    }


    public function test_it_can_get_categories_list_test_with_search()
    {
        // Arrange
        Category::factory()->create(['name' => 'Pantai']);
        Category::factory()->create(['name' => 'Gunung']);
        Category::factory()->create(['name' => 'Pantai Baru']);

        // Act
        $result = $this->categoryService->getCategoriesList(['search' => 'Pantai']);

        // Assert
        $this->assertCount(2, $result);
        $this->assertTrue($result->contains('name', 'Pantai'));
        $this->assertTrue($result->contains('name', 'Pantai Baru'));
    }


    public function test_it_can_get_categories_test_with_pagination()
    {
        // Arrange
        Category::factory()->count(15)->create();

        // Act
        $result = $this->categoryService->getCategoriesList(['per_page' => 10]);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(15, $result->total());
    }

    public function test_it_can_create_category()
    {
        // Arrange
        $data = [
            'name' => 'Taman Nasional'
        ];

        // Act
        $result = $this->categoryService->createCategory($data);

        // Assert
        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals('Taman Nasional', $result->name);
        $this->assertDatabaseHas('categories', $data);
    }


    public function test_it_can_update_category()
    {
        // Arrange
        $category = Category::factory()->create([
            'name' => 'Pantai',
        ]);

        $updateData = [
            'name' => 'Pantai & Laut',
        ];

        // Act
        $result = $this->categoryService->updateCategory($category, $updateData);

        // Assert
        $this->assertEquals('Pantai & Laut', $result->name);
        $this->assertDatabaseHas('categories', $updateData);
    }


    public function test_it_can_delete_category_when_not_used_by_destinations()
    {
        // Arrange
        $category = Category::factory()->create();

        // Act
        $result = $this->categoryService->deleteCategory($category);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }


    public function test_it_cannot_delete_category_used_by_destinations()
    {
        // Arrange
        User::factory()->create();
        $category = Category::factory()->create();

        // Create a destination using this category
        Destination::factory()->create([
            'category_id' => $category->id
        ]);

        // Assert & Act
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Kategori tidak bisa dihapus karena masih digunakan oleh destinasi.');

        $this->categoryService->deleteCategory($category);

        // Verify category still exists
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
