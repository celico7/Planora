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
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('epic_id')->nullable()->constrained('epics');
        $table->foreignId('sprint_id')->nullable()->constrained('sprints');
        $table->string('nom');
        $table->text('description');
        $table->foreignId('responsable_id')->constrained('users');
        $table->date('echeance');
        $table->enum('statut', ['à faire', 'en cours', 'terminé']);
        $table->enum('priorite', ['basse', 'moyenne', 'haute']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
