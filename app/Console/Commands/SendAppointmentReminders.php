<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email and in-app reminders for upcoming appointments (24h before)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = \Carbon\Carbon::tomorrow()->toDateString();
        
        $appointments = \App\Models\RendezVous::where('statut', 'CONFIRMED')
            ->whereDate('date_heure', $tomorrow)
            ->with(['patient', 'medecin'])
            ->get();

        $this->info("Found " . $appointments->count() . " appointments for tomorrow.");

        foreach ($appointments as $rdv) {
            // Check if reminder already sent (optional, but good practice if we run this often)
            // For now, assume we run this once a day
            
            try {
                // In-App Notification
                \App\Models\Notification::create([
                    'user_id' => $rdv->patient_id,
                    'type' => 'REMINDER',
                    'message' => "Rappel : Votre rendez-vous avec le Dr. " . ($rdv->medecin->name ?? '') . " est prévu pour demain à " . \Carbon\Carbon::parse($rdv->date_heure)->format('H:i') . ".",
                    'est_lu' => false,
                    'sent_at' => now(),
                ]);

                // Email Notification
                if ($rdv->patient && $rdv->patient->email) {
                    \Illuminate\Support\Facades\Mail::to($rdv->patient->email)->send(new \App\Mail\AppointmentReminder($rdv));
                }
                
                $this->info("Reminder sent to " . $rdv->patient->name);
            } catch (\Exception $e) {
                $this->error("Failed to send reminder for RDV #{$rdv->id}: " . $e->getMessage());
            }
        }
    }
}
