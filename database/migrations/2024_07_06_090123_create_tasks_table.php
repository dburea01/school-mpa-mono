<?php

use App\Models\Task;
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
            $table->string('id')->primary();
            $table->string('name');
            $table->string('comment')->nullable();
            $table->timestamps();
        });

        $tasks = [

            ['id' => 'viewAnyUser', 'name' => 'Voir tous les utilisateurs'],
            ['id' => 'viewUser',    'name' => 'Voir un utilisateur'],
            ['id' => 'createUser',  'name' => 'Créer un utilisateur'],
            ['id' => 'updateUser',  'name' => 'Modifier un utilisateur'],
            ['id' => 'deleteUser',  'name' => 'Supprimer un utilisateur'],

        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
