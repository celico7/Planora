<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer l'ancienne table avec la mauvaise structure
        Schema::dropIfExists('notifications');
        
        // Recréer avec la structure standard Laravel (comme en local)
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->morphs('notifiable'); // Crée déjà l'index automatiquement
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
         });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        
        // Recréer l'ancienne structure si rollback
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->nullable();
            $table->string('type');
            $table->text('contenu')->nullable();
            $table->string('canal')->nullable();
            $table->timestamps();
            $table->foreignId('task_id')->nullable();
            $table->foreignId('user_id')->nullable();
        });
    }
};
