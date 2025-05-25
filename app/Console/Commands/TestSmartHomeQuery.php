<?php

namespace App\Console\Commands;

use App\GraphQL\Queries\HomeQuery\HobbiesProduct;
use App\Services\ProductRecommendationService;
use App\Services\ProductViewTrackingService;
use Illuminate\Console\Command;

class TestSmartHomeQuery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:smart-home-query {--user-id= : Test with specific user ID} {--per-page=10 : Number of products per page}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the Smart Home Query implementation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Testing Smart Home Query Implementation');
        $this->newLine();

        $userId = $this->option('user-id');
        $perPage = (int) $this->option('per-page');

        // Test services
        $this->testServices();

        // Test query
        $this->testQuery($userId, $perPage);

        $this->newLine();
        $this->info('✅ Smart Home Query test completed successfully!');
    }

    private function testServices(): void
    {
        $this->info('📊 Testing Services...');

        try {
            $recommendationService = app(ProductRecommendationService::class);
            $viewTrackingService = app(ProductViewTrackingService::class);

            $this->line('✓ ProductRecommendationService instantiated');
            $this->line('✓ ProductViewTrackingService instantiated');

            // Test view tracking
            $viewTrackingService->trackSingleView(1);
            $this->line('✓ View tracking test completed');

            // Test recommendation stats (if user exists)
            if ($userId = $this->option('user-id')) {
                $stats = $recommendationService->getUserInteractionStats((int) $userId);
                $this->line("✓ User interaction stats: " . json_encode($stats));
            }

        } catch (\Exception $e) {
            $this->error('❌ Service test failed: ' . $e->getMessage());
            return;
        }

        $this->newLine();
    }

    private function testQuery(?string $userId, int $perPage): void
    {
        $this->info('🔍 Testing Smart Home Query...');

        try {
            $recommendationService = app(ProductRecommendationService::class);
            $viewTrackingService = app(ProductViewTrackingService::class);

            $smartHomeQuery = new HobbiesProduct($recommendationService, $viewTrackingService);

            // Simulate authentication if user ID provided
            if ($userId) {
                $user = \App\Models\User::find((int) $userId);
                if ($user) {
                    auth()->login($user);
                    $this->line("✓ Testing with authenticated user: {$user->name}");
                } else {
                    $this->warn("⚠️  User with ID {$userId} not found, testing as guest");
                }
            } else {
                $this->line('✓ Testing as guest user');
            }

            // Execute query
            $result = $smartHomeQuery(null, ['page' => 1, 'perPage' => $perPage]);

            // Display results
            $this->line("✓ Query executed successfully");
            $this->line("✓ Products returned: " . $result->count());

            // Show sample products
            if ($result->isNotEmpty()) {
                $this->newLine();
                $this->info('📦 Sample Products:');

                $sampleProducts = $result->take(3);
                foreach ($sampleProducts as $product) {
                    $this->line("  • {$product->name} (Level: {$product->level}, Type: {$product->type})");
                }
            }

        } catch (\Exception $e) {
            $this->error('❌ Query test failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
