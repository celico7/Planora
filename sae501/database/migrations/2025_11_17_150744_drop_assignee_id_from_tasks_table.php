<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifier si la contrainte existe avant de la supprimer
        $foreignKeyExists = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.TABLE_CONSTRAINTS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'tasks' 
            AND CONSTRAINT_NAME = 'tasks_assignee_id_foreign'
        ");

        Schema::table('tasks', function (Blueprint $table) use ($foreignKeyExists) {
            if (!empty($foreignKeyExists)) {
                $table->dropForeign(['assignee_id']);
            }
            
            if (Schema::hasColumn('tasks', 'assignee_id')) {
                $table->dropColumn('assignee_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('assignee_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }
};
