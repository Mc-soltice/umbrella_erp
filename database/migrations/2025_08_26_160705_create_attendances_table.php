<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * Table des pointages des employés
         */
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date')->default(DB::raw('CURRENT_DATE'));
            $table->time('start_time'); // heure de début (connexion)
            $table->time('end_time')->nullable(); // heure de fin fixée à 18h
            $table->decimal('worked_hours', 5, 2)->nullable();  // heures travaillées
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
