<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DailyProductReport;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyReportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;
    private array $defaultPayload;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->product = Product::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => Category::factory()->create(['user_id' => $this->user->id])->id,
        ]);

        $this->defaultPayload = [
            'report_date' => '2026-07-08',
            'quantity_sold' => 3,
            'selling_price' => 25.00,
            'payment_method' => 'cash',
            'notes' => 'Sold well today',
        ];
    }

    public function test_guest_cannot_view_report_form(): void
    {
        $response = $this->get(route('daily-reports.create', $this->product));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_report_form(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('daily-reports.create', $this->product));

        $response->assertStatus(200);
        $response->assertSee($this->product->name);
    }

    public function test_authenticated_user_can_create_daily_report(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('daily-reports.store', $this->product), $this->defaultPayload);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('products.show', $this->product));

        $this->assertDatabaseHas('daily_product_reports', [
            'product_id' => $this->product->id,
            'quantity_sold' => 3,
            'selling_price' => 25.00,
            'total_revenue' => 75.00,
            'payment_method' => 'cash',
            'notes' => 'Sold well today',
        ]);
    }

    public function test_total_revenue_is_auto_calculated(): void
    {
        $this->actingAs($this->user)
            ->post(route('daily-reports.store', $this->product), [
                'report_date' => '2026-07-08',
                'quantity_sold' => 5,
                'selling_price' => 10.50,
                'payment_method' => 'mobile_money',
            ]);

        $this->assertDatabaseHas('daily_product_reports', [
            'product_id' => $this->product->id,
            'quantity_sold' => 5,
            'selling_price' => 10.50,
            'total_revenue' => 52.50,
        ]);
    }

    public function test_same_product_different_prices_on_different_days(): void
    {
        $this->actingAs($this->user)
            ->post(route('daily-reports.store', $this->product), [
                'report_date' => '2026-07-08',
                'quantity_sold' => 3,
                'selling_price' => 25.00,
                'payment_method' => 'cash',
            ]);

        $this->actingAs($this->user)
            ->post(route('daily-reports.store', $this->product), [
                'report_date' => '2026-07-09',
                'quantity_sold' => 3,
                'selling_price' => 30.00,
                'payment_method' => 'mobile_money',
            ]);

        $reports = $this->product->fresh()->dailyReports()->orderBy('report_date')->get();
        $this->assertCount(2, $reports);
        $this->assertEquals(75.00, $reports[0]->total_revenue);
        $this->assertEquals(90.00, $reports[1]->total_revenue);
        $this->assertEquals('cash', $reports[0]->payment_method);
        $this->assertEquals('mobile_money', $reports[1]->payment_method);
    }

    public function test_authenticated_user_can_edit_report(): void
    {
        $report = DailyProductReport::create([
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'report_date' => '2026-07-08',
            'quantity_sold' => 3,
            'selling_price' => 25.00,
            'total_revenue' => 75.00,
            'payment_method' => 'cash',
        ]);

        $response = $this->actingAs($this->user)
            ->patch(route('daily-reports.update', [$this->product, $report]), [
                'report_date' => '2026-07-08',
                'quantity_sold' => 5,
                'selling_price' => 20.00,
                'payment_method' => 'mobile_money',
                'notes' => 'Updated',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('products.show', $this->product));

        $this->assertDatabaseHas('daily_product_reports', [
            'id' => $report->id,
            'quantity_sold' => 5,
            'selling_price' => 20.00,
            'total_revenue' => 100.00,
            'payment_method' => 'mobile_money',
            'notes' => 'Updated',
        ]);
    }

    public function test_authenticated_user_can_delete_report(): void
    {
        $report = DailyProductReport::create([
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'report_date' => '2026-07-08',
            'quantity_sold' => 3,
            'selling_price' => 25.00,
            'total_revenue' => 75.00,
            'payment_method' => 'cash',
        ]);

        $this->actingAs($this->user)
            ->delete(route('daily-reports.destroy', [$this->product, $report]));

        $this->assertDatabaseMissing('daily_product_reports', ['id' => $report->id]);
    }

    public function test_edit_form_shows_existing_values(): void
    {
        $report = DailyProductReport::create([
            'product_id' => $this->product->id,
            'user_id' => $this->user->id,
            'report_date' => '2026-07-08',
            'quantity_sold' => 3,
            'selling_price' => 25.00,
            'total_revenue' => 75.00,
            'payment_method' => 'cash',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('daily-reports.edit', [$this->product, $report]));

        $response->assertStatus(200);
        $response->assertSee($this->product->name);
    }

    public function test_requires_valid_data(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('daily-reports.store', $this->product), [
                'report_date' => '',
                'quantity_sold' => -1,
                'selling_price' => -5,
                'payment_method' => '',
            ]);

        $response->assertSessionHasErrors(['report_date', 'quantity_sold', 'selling_price', 'payment_method']);
    }
}
