<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->text('compte_rendu');
            $table->text('notes_prives');
            $table->string('tension');
            $table->float('poids');
            $table->float('temperature');
            $table->integer('rythme_cardiaque');
            $table->foreignId('rendez_vouses_id')->constrained('rendez_vouses')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
