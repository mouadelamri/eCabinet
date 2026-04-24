<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RendezVous;
use App\Models\DoctorAvailability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    private User $patient;
    private User $doctor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->patient = User::factory()->create(['role' => 'PATIENT']);
        $this->doctor  = User::factory()->create(['role' => 'DOCTOR']);
        $this->actingAs($this->patient);
    }

    // ✅ Test 1 : patient peut accéder à son dashboard
    public function test_patient_can_access_dashboard(): void
    {
        $response = $this->get('/patient');
        $response->assertStatus(200);
    }

    // ❌ Test 2 : guest ne peut pas accéder au dashboard patient
        public function test_guest_cannot_access_patient_dashboard(): void
    {
        $this->app['auth']->logout();

        $response = $this->get('/patient');
        $response->assertRedirect('/login');
    }


    // ✅ Test 3 : patient peut voir la page de ses rendez-vous
    public function test_patient_can_view_appointments(): void
    {
        $response = $this->get('/patient/rendezvous');
        $response->assertStatus(200);
    }

    // ✅ Test 4 : patient peut voir le formulaire de prise de RDV
    public function test_patient_can_view_book_appointment_form(): void
    {
        $response = $this->get('/patient/rendezvous/nouveau');
        $response->assertStatus(200);
    }

    // ✅ Test 5 : patient peut créer un rendez-vous
    public function test_patient_can_request_appointment(): void
    {
        // Crée une disponibilité pour le médecin (lundi)
        DoctorAvailability::create([
            'user_id'     => $this->doctor->id,
            'day_of_week' => 1, // lundi
            'is_working'  => true,
            'start_time'  => '09:00',
            'end_time'    => '17:00',
        ]);

        // Trouve le prochain lundi
        $nextMonday = now()->next('Monday')->format('Y-m-d H:i:s');

        $response = $this->post('/patient/rendezvous', [
            'medecin_id' => $this->doctor->id,
            'date_heure' => $nextMonday,
            'motif'      => 'Consultation générale',
        ]);

        $this->assertDatabaseHas('rendez_vous', [
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'statut'     => 'PENDING',
        ]);
    }

    // ❌ Test 6 : patient ne peut pas créer un RDV sans motif
    public function test_patient_cannot_request_appointment_without_motif(): void
    {
        $response = $this->post('/patient/rendezvous', [
            'medecin_id' => $this->doctor->id,
            'date_heure' => now()->addDay()->format('Y-m-d H:i:s'),
            'motif'      => '',
        ]);

        $response->assertSessionHasErrors('motif');
    }

    // ❌ Test 7 : patient ne peut pas créer un RDV avec un medecin_id invalide
    public function test_patient_cannot_request_appointment_with_invalid_doctor(): void
    {
        $response = $this->post('/patient/rendezvous', [
            'medecin_id' => 9999,
            'date_heure' => now()->addDay()->format('Y-m-d H:i:s'),
            'motif'      => 'Test',
        ]);

        $response->assertSessionHasErrors('medecin_id');
    }

    // ✅ Test 8 : patient peut annuler un RDV PENDING
    public function test_patient_can_cancel_pending_appointment(): void
    {
        $rdv = RendezVous::create([
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'date_heure' => now()->addDay(),
            'statut'     => 'PENDING',
            'motif'      => 'Test',
        ]);

        $response = $this->post("/patient/rendezvous/{$rdv->id}/cancel");

        $this->assertDatabaseHas('rendez_vous', [
            'id'     => $rdv->id,
            'statut' => 'CANCELLED',
        ]);
    }

    // ❌ Test 9 : patient ne peut pas annuler un RDV CONFIRMED
    public function test_patient_cannot_cancel_completed_appointment(): void
    {
        $rdv = RendezVous::create([
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'date_heure' => now()->subDay(),
            'statut'     => 'CONFIRMED',
            'motif'      => 'Test',
        ]);

        $response = $this->post("/patient/rendezvous/{$rdv->id}/cancel");

        // Vérifie que le statut n'a pas changé
        $this->assertDatabaseHas('rendez_vous', [
            'id'     => $rdv->id,
            'statut' => 'CONFIRMED',
        ]);

        // Vérifie que le message d'erreur est présent
        $response->assertSessionHas('error');
    }


    // ✅ Test 10 : patient peut accéder à son dossier médical
    public function test_patient_can_view_medical_record(): void
    {
        $response = $this->get('/patient/dossier');
        $response->assertStatus(200);
    }

    // ✅ Test 11 : patient peut accéder à ses paramètres
    public function test_patient_can_view_settings(): void
    {
        $response = $this->get('/patient/parametres');
        $response->assertStatus(200);
    }

    // ✅ Test 12 : patient peut mettre à jour son profil
    public function test_patient_can_update_settings(): void
    {
        $response = $this->patch('/patient/parametres', [
            'name'      => 'Nouveau Nom',
            'email'     => $this->patient->email,
            'telephone' => '0612345678',
        ]);

        $this->assertDatabaseHas('users', [
            'id'   => $this->patient->id,
            'name' => 'Nouveau Nom',
        ]);
    }
}
