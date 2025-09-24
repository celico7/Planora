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
    Schema::create('files', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tache_id')->constrained('tasks');
        $table->string('chemin_fichier');
        $table->string('nom');
        $table->foreignId('upload_par_id')->constrained('users');
        $table->timestamp('date')->useCurrent();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
