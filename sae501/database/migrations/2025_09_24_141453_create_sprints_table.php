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
    Schema::create('epics', function (Blueprint $table) {
    $table->id();
    $table->foreignId('project_id')->constrained('projects');
    $table->foreignId('sprint_id')->nullable()->constrained('sprints');
    $table->string('nom');
    $table->text('description');
    $table->date('begining');
    $table->date('end');
    $table->enum('statut', ['prévu', 'en cours', 'terminé']);
    $table->timestamps();
    });

}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sprints');
    }
};
