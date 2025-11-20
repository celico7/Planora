<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Supprimer la clé étrangère d'abord (si elle existe)
            $table->dropForeign(['assignee_id']);
            // Puis supprimer la colonne
            $table->dropColumn('assignee_id');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('assignee_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }
};
