<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DailyProductReport;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoneyReportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_view_money_report(): void
    {
        $response = $this->get(route('money-report.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_money_report_shows_aggregated_totals(): void
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        $product = Product::factory()->create(['category_id' => $category->id, 'user_id' => $this->user->id]);

        DailyProductReport::create([
            'product_id' => $product->id,
            'user_id' => $this->user->id,
            'report_date' => '2026-07-08',
            'quantity_sold' => 5,
            'selling_price' => 20.00,
            'total_revenue' => 100.00,
        ]);

        DailyProductReport::create([
            'product_id' => $product->id,
            'user_id' => $this->user->id,
            'report_date' => '2026-07-09',
            'quantity_sold' => 3,
            'selling_price' => 25.00,
            'total_revenue' => 75.00,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('money-report.index'));

        $response->assertStatus(200);
        $response->assertSee('FRW 175.00');
        $response->assertSee('8');
        $response->assertSee('2');
    }

    public function test_money_report_filters_by_date_range(): void
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        $product = Product::factory()->create(['category_id' => $category->id, 'user_id' => $this->user->id]);

        DailyProductReport::create([
            'product_id' => $product->id,
            'user_id' => $this->user->id,
            'report_date' => '2026-07-08',
            'quantity_sold' => 5,
            'selling_price' => 20.00,
            'total_revenue' => 100.00,
        ]);

        DailyProductReport::create([
            'product_id' => $product->id,
            'user_id' => $this->user->id,
            'report_date' => '2026-07-10',
            'quantity_sold' => 3,
            'selling_price' => 25.00,
            'total_revenue' => 75.00,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('money-report.index', ['date_from' => '2026-07-09', 'date_to' => '2026-07-11']));

        $response->assertStatus(200);
        $response->assertSee('FRW 75.00');
    }

    public function test_money_report_filters_by_product(): void
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        $productA = Product::factory()->create(['category_id' => $category->id, 'name' => 'Product A', 'user_id' => $this->user->id]);
        $productB = Product::factory()->create(['category_id' => $category->id, 'name' => 'Product B', 'user_id' => $this->user->id]);

        DailyProductReport::create([
            'product_id' => $productA->id,
            'user_id' => $this->user->id,
            'report_date' => '2026-07-08',
            'quantity_sold' => 5,
            'selling_price' => 20.00,
            'total_revenue' => 100.00,
        ]);

        DailyProductReport::create([
            'product_id' => $productB->id,
            'user_id' => $this->user->id,
            'report_date' => '2026-07-08',
            'quantity_sold' => 10,
            'selling_price' => 50.00,
            'total_revenue' => 500.00,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('money-report.index', ['product_id' => $productA->id]));

        $response->assertStatus(200);
        $response->assertSee('FRW 100.00');
        $response->assertSee('5');
    }

    public function test_money_report_empty_state(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('money-report.index'));

        $response->assertStatus(200);
        $response->assertSee('No daily reports yet');
    }
}
