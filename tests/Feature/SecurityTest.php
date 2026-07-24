<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DailyProductReport;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $category;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create(['user_id' => $this->user->id]);
        $this->product = Product::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);
    }

    public function test_sql_injection_in_product_name_is_rejected(): void
    {
        $payload = [
            'category_id' => $this->category->id,
            'name' => "Test' OR '1'='1",
            'sku' => 'INJ-001',
            'purchase_price' => 10,
            'selling_price' => 20,
            'stock_quantity' => 10,
            'low_stock_threshold' => 5,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('products.store'), $payload);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_sql_injection_in_product_sku_is_rejected(): void
    {
        $payload = [
            'category_id' => $this->category->id,
            'name' => 'Safe Name',
            'sku' => 'SKU"; DROP TABLE products; --',
            'purchase_price' => 10,
            'selling_price' => 20,
            'stock_quantity' => 10,
            'low_stock_threshold' => 5,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('products.store'), $payload);

        $response->assertSessionHasErrors(['sku']);
    }

    public function test_sql_injection_in_product_description_is_rejected(): void
    {
        $payload = [
            'category_id' => $this->category->id,
            'name' => 'Safe Name',
            'sku' => 'SAFE-001',
            'description' => "'; DELETE FROM products; --",
            'purchase_price' => 10,
            'selling_price' => 20,
            'stock_quantity' => 10,
            'low_stock_threshold' => 5,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('products.store'), $payload);

        $response->assertSessionHasErrors(['description']);
    }

    public function test_sql_injection_in_category_name_is_rejected(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('categories.store'), [
                'name' => "Cat' OR '1'='1",
            ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_sql_injection_in_daily_report_notes_is_rejected(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('daily-reports.store', $this->product), [
                'report_date' => '2026-07-10',
                'quantity_sold' => 1,
                'selling_price' => 10,
                'payment_method' => 'cash',
                'notes' => "'; DROP TABLE daily_product_reports; --",
            ]);

        $response->assertSessionHasErrors(['notes']);
    }

    public function test_sql_injection_in_stock_reason_is_rejected(): void
    {
        $response = $this->actingAs($this->user)
            ->patch(route('products.adjust-stock', $this->product), [
                'change' => 5,
                'reason' => "'; UPDATE products SET stock_quantity = 9999; --",
            ]);

        $response->assertSessionHasErrors(['reason']);
    }

    public function test_xss_in_product_name_is_escaped_in_output(): void
    {
        Product::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'name' => '<script>alert("xss")</script>',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertDontSee('<script>alert("xss")</script>', false);
    }

    public function test_mass_assignment_protection_on_products(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('products.store'), [
                'category_id' => $this->category->id,
                'name' => 'Test',
                'sku' => 'MASS-001',
                'purchase_price' => 10,
                'selling_price' => 20,
                'stock_quantity' => 10,
                'low_stock_threshold' => 5,
                'id' => 99999,
                'created_at' => '2020-01-01',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('products', ['id' => 99999]);
    }

    public function test_invalid_payment_method_is_rejected(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('daily-reports.store', $this->product), [
                'report_date' => '2026-07-10',
                'quantity_sold' => 1,
                'selling_price' => 10,
                'payment_method' => 'credit_card',
            ]);

        $response->assertSessionHasErrors(['payment_method']);
    }

    public function test_negative_stock_quantity_is_rejected(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('products.store'), [
                'category_id' => $this->category->id,
                'name' => 'Test',
                'sku' => 'NEG-001',
                'purchase_price' => 10,
                'selling_price' => 20,
                'stock_quantity' => -5,
                'low_stock_threshold' => 5,
            ]);

        $response->assertSessionHasErrors(['stock_quantity']);
    }

    public function test_negative_selling_price_is_rejected(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('products.store'), [
                'category_id' => $this->category->id,
                'name' => 'Test',
                'sku' => 'NEG-002',
                'purchase_price' => 10,
                'selling_price' => -1,
                'stock_quantity' => 10,
                'low_stock_threshold' => 5,
            ]);

        $response->assertSessionHasErrors(['selling_price']);
    }

    public function test_invalid_image_upload_is_rejected(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('products.store'), [
                'category_id' => $this->category->id,
                'name' => 'Test',
                'sku' => 'IMG-001',
                'purchase_price' => 10,
                'selling_price' => 20,
                'stock_quantity' => 10,
                'low_stock_threshold' => 5,
                'image' => UploadedFile::fake()->create('malicious.php', 100),
            ]);

        $response->assertSessionHasErrors(['image']);
    }

    public function test_unauthenticated_user_cannot_access_any_protected_route(): void
    {
        $report = DailyProductReport::create([
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'report_date' => '2026-07-10',
            'quantity_sold' => 1,
            'selling_price' => 10,
            'total_revenue' => 10,
            'payment_method' => 'cash',
        ]);

        $routes = [
            'GET' => [route('dashboard'), route('products.index'), route('categories.index'),
                       route('money-report.index'), route('profile.edit')],
            'POST' => [route('products.store'), route('categories.store'),
                       route('daily-reports.store', $this->product)],
            'PATCH' => [route('products.adjust-stock', $this->product),
                        route('daily-reports.update', [$this->product, $report])],
            'DELETE' => [route('products.destroy', $this->product),
                         route('categories.destroy', $this->category),
                         route('daily-reports.destroy', [$this->product, $report])],
        ];

        foreach ($routes as $method => $urls) {
            foreach ($urls as $url) {
                $response = match ($method) {
                    'GET' => $this->get($url),
                    'POST' => $this->post($url),
                    'PATCH' => $this->patch($url),
                    'DELETE' => $this->delete($url),
                };
                $response->assertRedirect(route('login'));
            }
        }
    }

    public function test_backslash_in_input_is_rejected(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('categories.store'), [
                'name' => 'Invalid\\Name',
            ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_backtick_in_input_is_rejected(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('categories.store'), [
                'name' => 'Invalid`Name',
            ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_hash_comment_in_input_is_rejected(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('categories.store'), [
                'name' => 'Invalid#Name',
            ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        Product::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('categories.destroy', $this->category));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $this->category->id]);
    }

    public function test_duplicate_sku_is_rejected(): void
    {
        Product::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'sku' => 'DUP-001',
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('products.store'), [
                'category_id' => $this->category->id,
                'name' => 'Test',
                'sku' => 'DUP-001',
                'purchase_price' => 10,
                'selling_price' => 20,
                'stock_quantity' => 10,
                'low_stock_threshold' => 5,
            ]);

        $response->assertSessionHasErrors(['sku']);
    }
}
