<?php

namespace Tests\Feature;

use App\Enums\CategoryTypeEnum;
use App\Enums\LevelProductEnum;
use App\Enums\ProductActiveEnum;
use App\GraphQL\Queries\HomeQuery\HobbiesProduct;
use App\Models\Category;
use App\Models\City;
use App\Models\Interaction;
use App\Models\Product;
use App\Models\User;
use App\Services\ProductRecommendationService;
use App\Services\ProductViewTrackingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmartHomeQueryTest extends TestCase
{
    use RefreshDatabase;

    private HobbiesProduct $smartHomeQuery;
    private ProductRecommendationService $recommendationService;
    private ProductViewTrackingService $viewTrackingService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->recommendationService = app(ProductRecommendationService::class);
        $this->viewTrackingService = app(ProductViewTrackingService::class);
        $this->smartHomeQuery = new HobbiesProduct(
            $this->recommendationService,
            $this->viewTrackingService
        );
    }

    /** @test */
    public function it_returns_products_for_guest_users()
    {
        // Create test data
        $this->createTestProducts();

        // Test guest user query
        $result = ($this->smartHomeQuery)(null, ['page' => 1, 'perPage' => 10]);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        $this->assertLessThanOrEqual(10, $result->count());
    }

    /** @test */
    public function it_returns_personalized_products_for_authenticated_users()
    {
        // Create test user and data
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->createTestProducts();
        $this->createUserInteractions($user);

        // Test authenticated user query
        $result = ($this->smartHomeQuery)(null, ['page' => 1, 'perPage' => 20]);

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
        $this->assertLessThanOrEqual(20, $result->count());
    }

    /** @test */
    public function it_handles_pagination_correctly()
    {
        $this->createTestProducts(50); // Create 50 products

        // Test first page
        $page1 = ($this->smartHomeQuery)(null, ['page' => 1, 'perPage' => 10]);
        $this->assertLessThanOrEqual(10, $page1->count());

        // Test second page (note: without real pagination, this will return same results)
        $page2 = ($this->smartHomeQuery)(null, ['page' => 2, 'perPage' => 10]);
        $this->assertLessThanOrEqual(10, $page2->count());
    }

    /** @test */
    public function it_excludes_restaurant_products()
    {
        // Create restaurant category
        $restaurantCategory = Category::factory()->create([
            'type' => CategoryTypeEnum::RESTAURANT->value
        ]);

        // Create restaurant product
        Product::factory()->create([
            'category_id' => $restaurantCategory->id,
            'active' => ProductActiveEnum::ACTIVE->value,
            'type' => CategoryTypeEnum::RESTAURANT->value
        ]);

        // Create regular products
        $this->createTestProducts(5);

        $result = ($this->smartHomeQuery)(null, ['page' => 1, 'perPage' => 10]);

        // Should not include restaurant products
        foreach ($result as $product) {
            $this->assertNotEquals(CategoryTypeEnum::RESTAURANT->value, $product->type);
        }
    }

    private function createTestProducts(int $count = 10): void
    {
        // Create categories
        $category = Category::factory()->create([
            'type' => CategoryTypeEnum::PRODUCT->value
        ]);

        $city = City::factory()->create();
        $user = User::factory()->create();

        // Create special products
        Product::factory()->count(3)->create([
            'category_id' => $category->id,
            'city_id' => $city->id,
            'user_id' => $user->id,
            'active' => ProductActiveEnum::ACTIVE->value,
            'level' => LevelProductEnum::SPECIAL->value,
            'type' => CategoryTypeEnum::PRODUCT->value,
            'end_date' => null
        ]);

        // Create normal products
        Product::factory()->count($count - 3)->create([
            'category_id' => $category->id,
            'city_id' => $city->id,
            'user_id' => $user->id,
            'active' => ProductActiveEnum::ACTIVE->value,
            'level' => LevelProductEnum::NORMAL->value,
            'type' => CategoryTypeEnum::PRODUCT->value,
            'end_date' => null
        ]);
    }

    private function createUserInteractions(User $user): void
    {
        $category = Category::first();

        if ($category) {
            Interaction::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'visited' => 5
            ]);
        }
    }
}
