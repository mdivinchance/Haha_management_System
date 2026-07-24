<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->category = Category::factory()->create(['user_id' => $this->user->id]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_products(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('products.index'));

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_a_product(): void
    {
        $data = [
            'category_id' => $this->category->id,
            'name' => 'Test Product',
            'sku' => 'TST-001',
            'purchase_price' => 10.00,
            'selling_price' => 25.00,
            'stock_quantity' => 50,
            'low_stock_threshold' => 5,
        ];

        $response = $this->actingAs($this->user)
            ->post(route('products.store'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'sku' => 'TST-001',
            'stock_quantity' => 50,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_product_appears_in_list_with_correct_stock(): void
    {
        $product = Product::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'name' => 'Visible Product',
            'stock_quantity' => 25,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Visible Product');
        $response->assertSee('25');
    }

    public function test_stock_adjustment_creates_movement_record(): void
    {
        $product = Product::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'stock_quantity' => 10,
        ]);

        $this->actingAs($this->user)
            ->patch(route('products.adjust-stock', $product), [
                'change' => 5,
                'reason' => 'Restock',
            ]);

        $this->assertEquals(15, $product->fresh()->stock_quantity);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'change_amount' => 5,
            'reason' => 'Restock',
        ]);
    }
}
