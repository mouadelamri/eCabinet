<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RendezVous;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecretaryTest extends TestCase
{
    use RefreshDatabase;

    private User $secretary;
    private User $doctor;
    private User $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->secretary = User::factory()->create(['role' => 'SECRETARY']);
        $this->doctor    = User::factory()->create(['role' => 'DOCTOR']);
        $this->patient   = User::factory()->create(['role' => 'PATIENT']);
        $this->actingAs($this->secretary);
    }

    // ✅ Test 1 : secrétaire peut accéder à son dashboard
    public function test_secretary_can_access_dashboard(): void
    {
        $response = $this->get('/secretary/dashboard');
        $response->assertStatus(200);
    }

    // ❌ Test 2 : guest ne peut pas accéder au dashboard secrétaire
    public function test_guest_cannot_access_secretary_dashboard(): void
    {
        $this->app['auth']->logout();

        $response = $this->get('/secretary/dashboard');
        $response->assertRedirect('/login');
    }

    // ✅ Test 3 : secrétaire peut voir la liste des patients
    public function test_secretary_can_view_patients(): void
    {
        User::factory()->count(3)->create(['role' => 'PATIENT']);

        $response = $this->get('/secretary/patients');
        $response->assertStatus(200);
    }

    // ✅ Test 4 : secrétaire peut voir les RDV en attente
    public function test_secretary_can_view_pending_appointments(): void
    {
        RendezVous::create([
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'date_heure' => now()->addDay(),
            'statut'     => 'PENDING',
            'motif'      => 'Test',
        ]);

        $response = $this->get('/secretary/PendingrendezVous');
        $response->assertStatus(200);
    }

    // ✅ Test 5 : secrétaire peut voir les RDV confirmés
    public function test_secretary_can_view_confirmed_appointments(): void
    {
        RendezVous::create([
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'date_heure' => now()->addDay(),
            'statut'     => 'CONFIRMED',
            'motif'      => 'Test',
        ]);

        $response = $this->get('/secretary/ConfirmedrendezVous');
        $response->assertStatus(200);
    }

    // ✅ Test 6 : secrétaire peut voir les RDV annulés
    public function test_secretary_can_view_cancelled_appointments(): void
    {
        RendezVous::create([
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'date_heure' => now()->addDay(),
            'statut'     => 'CANCELLED',
            'motif'      => 'Test',
        ]);

        $response = $this->get('/secretary/rendezVousAnnulee');
        $response->assertStatus(200);
    }

    // ✅ Test 7 : secrétaire peut confirmer un RDV PENDING
    public function test_secretary_can_confirm_pending_appointment(): void
    {
        $rdv = RendezVous::create([
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'date_heure' => now()->addDay(),
            'statut'     => 'PENDING',
            'motif'      => 'Test',
        ]);

        $response = $this->patch("/secretary/rendezvous/{$rdv->id}/confirm");

        $this->assertDatabaseHas('rendez_vous', [
            'id'     => $rdv->id,
            'statut' => 'CONFIRMED',
        ]);
    }

    // ❌ Test 8 : secrétaire ne peut pas confirmer un RDV déjà CONFIRMED
    public function test_secretary_cannot_confirm_already_confirmed_appointment(): void
    {
        $rdv = RendezVous::create([
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'date_heure' => now()->addDay(),
            'statut'     => 'CONFIRMED',
            'motif'      => 'Test',
        ]);

        $response = $this->patch("/secretary/rendezvous/{$rdv->id}/confirm");

        $response->assertSessionHas('error');
    }

    // ✅ Test 9 : secrétaire peut annuler un RDV PENDING
    public function test_secretary_can_cancel_pending_appointment(): void
    {
        $rdv = RendezVous::create([
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'date_heure' => now()->addDay(),
            'statut'     => 'PENDING',
            'motif'      => 'Test',
        ]);

        $response = $this->patch("/secretary/rendezvous/{$rdv->id}/cancel");

        $this->assertDatabaseHas('rendez_vous', [
            'id'     => $rdv->id,
            'statut' => 'CANCELLED',
        ]);
    }

    // ❌ Test 10 : secrétaire ne peut pas annuler un RDV CONFIRMED
    public function test_secretary_cannot_cancel_confirmed_appointment(): void
    {
        $rdv = RendezVous::create([
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'date_heure' => now()->addDay(),
            'statut'     => 'CONFIRMED',
            'motif'      => 'Test',
        ]);

        $response = $this->patch("/secretary/rendezvous/{$rdv->id}/cancel");

        $this->assertDatabaseHas('rendez_vous', [
            'id'     => $rdv->id,
            'statut' => 'CONFIRMED',
        ]);

        $response->assertSessionHas('error');
    }

    // ✅ Test 11 : secrétaire peut mettre à jour ses paramètres
    public function test_secretary_can_update_settings(): void
    {
        $response = $this->patch('/secretary/parametres', [
            'name'  => 'Sec Updated',
            'email' => $this->secretary->email,
        ]);

        $this->assertDatabaseHas('users', [
            'id'   => $this->secretary->id,
            'name' => 'Sec Updated',
        ]);
    }
}
