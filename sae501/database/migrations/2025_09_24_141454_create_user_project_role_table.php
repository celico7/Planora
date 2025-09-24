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
    Schema::create('user_project_role', function (Blueprint $table) {
        $table->id();
        $table->foreignId('utilisateur_id')->constrained('users');
        $table->foreignId('projet_id')->constrained('projects');
        $table->enum('role', ['admin', 'membre', 'invité']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_project_role');
    }
};
