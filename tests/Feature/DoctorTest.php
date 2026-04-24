<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\DoctorAvailability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DoctorTest extends TestCase
{
    use RefreshDatabase;

    private User $doctor;
    private User $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->doctor  = User::factory()->create(['role' => 'DOCTOR']);
        $this->patient = User::factory()->create(['role' => 'PATIENT']);
        $this->actingAs($this->doctor);
    }

    // ✅ Test 1 : doctor peut accéder à son dashboard
    public function test_doctor_can_access_dashboard(): void
    {
        $response = $this->get('/doctor');
        $response->assertStatus(200);
    }

    // ❌ Test 2 : patient ne peut pas accéder au dashboard doctor
    public function test_patient_cannot_access_doctor_dashboard(): void
    {
        $response = $this->actingAs($this->patient)->get('/doctor');

        $response->assertRedirect(route('dashboard'));
    }


    // ✅ Test 3 : doctor peut voir la liste de ses patients
    public function test_doctor_can_view_patients_list(): void
    {
        $response = $this->get('/doctor/patients');
        $response->assertStatus(200);
    }

    // ✅ Test 4 : doctor peut voir son planning
    public function test_doctor_can_view_schedule(): void
    {
        $response = $this->get('/doctor/schedule');
        $response->assertStatus(200);
    }

    // ✅ Test 5 : doctor peut sauvegarder ses disponibilités
    public function test_doctor_can_save_availability(): void
    {
        $response = $this->post('/doctor/availability', [
            'availabilities' => [
                [
                    'day_of_week' => 1,
                    'is_working'  => true,
                    'start_time'  => '09:00',
                    'end_time'    => '17:00',
                ],
                [
                    'day_of_week' => 2,
                    'is_working'  => true,
                    'start_time'  => '08:00',
                    'end_time'    => '16:00',
                ],
            ],
        ]);

        $this->assertDatabaseHas('doctor_availabilities', [
            'user_id'     => $this->doctor->id,
            'day_of_week' => 1,
            'is_working'  => true,
        ]);
    }

    // ✅ Test 6 : doctor peut confirmer un RDV PENDING
    public function test_doctor_can_confirm_pending_appointment(): void
    {
        $rdv = RendezVous::create([
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'date_heure' => now()->addDay(),
            'statut'     => 'PENDING',
            'motif'      => 'Consultation',
        ]);

        $response = $this->post("/doctor/rendezvous/{$rdv->id}/confirm");

        $this->assertDatabaseHas('rendez_vous', [
            'id'     => $rdv->id,
            'statut' => 'CONFIRMED',
        ]);
    }

    // ❌ Test 7 : doctor ne peut pas confirmer un RDV déjà CONFIRMED
    public function test_doctor_cannot_confirm_already_confirmed_appointment(): void
    {
        $rdv = RendezVous::create([
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'date_heure' => now()->addDay(),
            'statut'     => 'CONFIRMED',
            'motif'      => 'Consultation',
        ]);

        $response = $this->post("/doctor/rendezvous/{$rdv->id}/confirm");

        $response->assertSessionHas('error');
    }

    // ✅ Test 8 : doctor peut créer une consultation
    public function test_doctor_can_store_consultation(): void
    {
        $rdv = RendezVous::create([
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'date_heure' => \Carbon\Carbon::today(),
            'statut'     => 'CONFIRMED',
            'motif'      => 'Consultation directe',
        ]);

        $response = $this->post("/doctor/patients/{$this->patient->id}/consultation", [
            'poids'            => 75,
            'temperature'      => 37.5,
            'tension'          => '120/80',
            'rythme_cardiaque' => 72,
            'compte_rendu'     => 'Patient en bonne santé.',
            'notes_privees'    => 'RAS',
            'medicaments'      => 'Paracétamol 500mg',
        ]);

        $this->assertDatabaseHas('consultations', [
            'rendez_vous_id' => $rdv->id,
            'tension'        => '120/80',
        ]);
    }


    // ✅ Test 9 : doctor peut marquer un RDV comme COMPLETED
    public function test_doctor_can_complete_appointment(): void
    {
        $rdv = RendezVous::create([
            'patient_id' => $this->patient->id,
            'medecin_id' => $this->doctor->id,
            'date_heure' => now(),
            'statut'     => 'CONFIRMED',
            'motif'      => 'Consultation',
        ]);

        $response = $this->post("/doctor/consultation/{$rdv->id}");

        $this->assertDatabaseHas('rendez_vous', [
            'id'     => $rdv->id,
            'statut' => 'COMPLETED',
        ]);
    }

    // ✅ Test 10 : doctor peut voir son profil
    public function test_doctor_can_view_profile(): void
    {
        $response = $this->get('/doctor/profile');
        $response->assertStatus(200);
    }

    // ✅ Test 11 : doctor peut mettre à jour son profil
    public function test_doctor_can_update_profile(): void
    {
        $response = $this->post('/doctor/profile', [
            'name'          => 'Dr. Updated',
            'email'         => $this->doctor->email,
            'specialiste'   => 'Cardiologue',
            'diplome'       => 'MD',
            'telephone_pro' => '0612345678',
        ]);

        $this->assertDatabaseHas('users', [
            'id'   => $this->doctor->id,
            'name' => 'Dr. Updated',
        ]);
    }

    // ❌ Test 12 : doctor ne peut pas voir le dossier d'un patient qui n'est pas le sien
    public function test_doctor_cannot_view_other_doctors_patient(): void
    {
        $otherDoctor  = User::factory()->create(['role' => 'DOCTOR']);
        $otherPatient = User::factory()->create(['role' => 'PATIENT']);

        // RDV avec un autre médecin
        RendezVous::create([
            'patient_id' => $otherPatient->id,
            'medecin_id' => $otherDoctor->id,
            'date_heure' => now()->addDay(),
            'statut'     => 'PENDING',
            'motif'      => 'Test',
        ]);

        $response = $this->get("/doctor/patients/{$otherPatient->id}");
        $response->assertStatus(404);
    }
}
