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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique(); // AGT+xxxxx
            $table->string('first_name');
            $table->string('last_name');
            $table->string('location');
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
