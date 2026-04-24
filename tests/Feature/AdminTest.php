<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RendezVous;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Crée un admin et connecte-le avant chaque test
        $this->admin = User::factory()->create(['role' => 'ADMIN']);
        $this->actingAs($this->admin);
    }

    // ✅ Test 1 : admin peut accéder au dashboard
    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->get('/admin');
        $response->assertStatus(200);
    }

    // ❌ Test 2 : patient ne peut pas accéder au dashboard admin
    public function test_patient_cannot_access_admin_dashboard(): void
    {
        $patient = User::factory()->create(['role' => 'PATIENT']);

        $response = $this->actingAs($patient)->get('/admin');
        $response->assertRedirect();
        $response->assertRedirectToRoute('patient.dashboard');
    }

    // ✅ Test 3 : admin peut voir la liste des médecins
    public function test_admin_can_view_doctors_list(): void
    {
        User::factory()->count(3)->create(['role' => 'DOCTOR']);

        $response = $this->get('/admin/doctors');
        $response->assertStatus(200);
    }

    // ✅ Test 4 : admin peut créer un médecin
    public function test_admin_can_create_doctor(): void
    {
        $response = $this->post('/admin/doctors', [
            'name'     => 'Dr. Test',
            'email'    => 'doctor@test.com',
            'password' => 'password123',
            'specialiste'   => 'Cardiologue',
            'telephone_pro' => '0612345678',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'doctor@test.com',
            'role'  => 'DOCTOR',
        ]);
    }

    // ✅ Test 5 : admin peut créer une secrétaire
    public function test_admin_can_create_secretary(): void
    {
        $response = $this->post('/admin/secretaries', [
            'name'     => 'Sec Test',
            'email'    => 'sec@test.com',
            'password' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'sec@test.com',
            'role'  => 'SECRETARY',
        ]);
    }

    // ✅ Test 6 : admin peut supprimer un utilisateur sans RDV
    public function test_admin_can_delete_user_without_appointments(): void
    {
        $user = User::factory()->create(['role' => 'PATIENT']);

        $response = $this->delete("/admin/users/{$user->id}");

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    // ❌ Test 7 : admin ne peut pas supprimer un user avec des RDV
    public function test_admin_cannot_delete_user_with_appointments(): void
    {
        $doctor  = User::factory()->create(['role' => 'DOCTOR']);
        $patient = User::factory()->create(['role' => 'PATIENT']);

        RendezVous::create([
            'medecin_id' => $doctor->id,
            'patient_id' => $patient->id,
            'date_heure' => now()->addDay(),
            'statut'     => 'PENDING',
            'motif'      => 'Test',
        ]);

        $response = $this->delete("/admin/users/{$patient->id}");

        $this->assertDatabaseHas('users', ['id' => $patient->id]);
        $response->assertSessionHas('error');
    }

    // ❌ Test 8 : admin ne peut pas se supprimer lui-même
    public function test_admin_cannot_delete_himself(): void
    {
        $response = $this->delete("/admin/users/{$this->admin->id}");

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
        $response->assertSessionHas('error');
    }
}
