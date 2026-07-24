<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->manager = User::factory()->create(['role' => 'manager']);
    }

    public function test_manager_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->manager)->get(route('users.index'));
        $response->assertStatus(403);
    }

    public function test_manager_cannot_create_users(): void
    {
        $response = $this->actingAs($this->manager)->get(route('users.create'));
        $response->assertStatus(403);
    }

    public function test_manager_cannot_store_users(): void
    {
        $response = $this->actingAs($this->manager)->post(route('users.store'), [
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'manager',
        ]);
        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_user_management(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('users.index'));
        $response->assertStatus(200);
    }

    public function test_super_admin_can_create_users(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('users.create'));
        $response->assertStatus(200);
    }

    public function test_super_admin_can_store_users(): void
    {
        $response = $this->actingAs($this->superAdmin)->post(route('users.store'), [
            'name' => 'New Manager',
            'email' => 'manager@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'manager',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'manager@test.com', 'role' => 'manager']);
    }

    public function test_super_admin_can_deactivate_users(): void
    {
        $response = $this->actingAs($this->superAdmin)->delete(route('users.deactivate', $this->manager));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $this->manager->id, 'is_active' => false]);
    }

    public function test_super_admin_can_activate_users(): void
    {
        $this->manager->update(['is_active' => false]);

        $response = $this->actingAs($this->superAdmin)->patch(route('users.activate', $this->manager));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $this->manager->id, 'is_active' => true]);
    }

    public function test_super_admin_cannot_deactivate_self(): void
    {
        $response = $this->actingAs($this->superAdmin)->delete(route('users.deactivate', $this->superAdmin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->superAdmin->id, 'is_active' => true]);
    }

    public function test_manager_can_access_products(): void
    {
        $category = Category::factory()->create();
        $response = $this->actingAs($this->manager)->get(route('products.index'));
        $response->assertStatus(200);
    }

    public function test_manager_can_access_categories(): void
    {
        $response = $this->actingAs($this->manager)->get(route('categories.index'));
        $response->assertStatus(200);
    }

    public function test_manager_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->manager)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_deactivated_user_cannot_login(): void
    {
        $this->manager->update(['is_active' => false]);

        $response = $this->post(route('login'), [
            'email' => $this->manager->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors();
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get(route('users.index'));
        $response->assertRedirect(route('login'));
    }
}
