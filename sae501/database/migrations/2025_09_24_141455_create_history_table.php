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
    Schema::create('history', function (Blueprint $table) {
        $table->id();
        $table->foreignId('utilisateur_id')->constrained('users');
        $table->foreignId('projet_id')->constrained('projects');
        $table->string('action');
        $table->timestamp('date')->useCurrent();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history');
    }
};
