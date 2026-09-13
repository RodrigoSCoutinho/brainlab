<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_the_dashboard()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_student_cannot_access_the_professor_panel()
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)->get('/professor/essays');

        $response->assertStatus(403);
    }

    public function test_professor_can_access_the_professor_panel()
    {
        $professor = User::factory()->create(['role' => 'professor']);

        $response = $this->actingAs($professor)->get('/professor/essays');

        $response->assertStatus(200);
    }

    public function test_student_cannot_access_the_admin_panel()
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_professor_cannot_access_the_admin_panel()
    {
        $professor = User::factory()->create(['role' => 'professor']);

        $response = $this->actingAs($professor)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_the_admin_panel()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_student_can_access_their_own_dashboard()
    {
        $student = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($student)->get('/dashboard');

        $response->assertStatus(200);
    }
}
