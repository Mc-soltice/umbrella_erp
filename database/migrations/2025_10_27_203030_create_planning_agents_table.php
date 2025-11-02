<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('planning_agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planning_id')->constrained('plannings')->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('agents')->cascadeOnDelete();
            $table->enum('shift', ['morning', 'evening']); // morning = 5-14, evening = 14-23
            $table->string('status', 32); // present/absent/repos/permutation (stocké comme string ou enum)
            $table->string('motif')->nullable();
            $table->foreignId('remplacant_id')->nullable()->constrained('agents')->nullOnDelete();
            $table->timestamps();

            $table->unique(['planning_id', 'agent_id']); // un agent une fois par planning
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planning_agents');
    }
};
