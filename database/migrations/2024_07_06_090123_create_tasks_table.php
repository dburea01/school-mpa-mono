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

            ['id' => 'viewAnyPeriod', 'name' => 'Voir toutes les périodes'],
            ['id' => 'viewPeriod',    'name' => 'Voir une période'],
            ['id' => 'createPeriod',  'name' => 'Créer une période'],
            ['id' => 'updatePeriod',  'name' => 'Modifier une période'],
            ['id' => 'deletePeriod',  'name' => 'Supprimer une période'],

            ['id' => 'viewAnyGroup',  'name' => 'Voir tous les groupes'],
            ['id' => 'viewGroup',     'name' => 'Voir un groupe'],
            ['id' => 'createGroup',   'name' => 'Créer un groupe'],
            ['id' => 'updateGroup',   'name' => 'Modifier un groupe'],
            ['id' => 'deleteGroup',   'name' => 'Supprimer un groupe'],

            ['id' => 'viewAnyUserGroup',  'name' => 'Voir les utilisateurs d\'un groupe'],
            ['id' => 'createUserGroup',   'name' => 'Ajouter un utilisateur à un groupe'],
            ['id' => 'deleteUserGroup',   'name' => 'Supprimer un utilisateur d\'un groupe'],

            ['id' => 'viewAnySubject',  'name' => 'Voir toutes les matières'],
            ['id' => 'viewSubject',     'name' => 'Voir une matière'],
            ['id' => 'createSubject',   'name' => 'Créer une matière'],
            ['id' => 'updateSubject',   'name' => 'Modifier une matière'],
            ['id' => 'deleteSubject',   'name' => 'Supprimer une matière'],
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
