<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->cascadeOnDelete();
            $table->foreignId('planning_agent_id')->nullable()->constrained('planning_agents')->nullOnDelete();
            $table->date('date');
            $table->string('status', 32); // WORKED / ABSENT / REST / REPLACEMENT
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->unique(['agent_id', 'date']); // 1 ligne par agent/jour
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
