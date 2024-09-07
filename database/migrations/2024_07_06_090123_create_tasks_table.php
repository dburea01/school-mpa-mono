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

            ['id' => 'viewAnyClassroom',  'name' => 'Voir toutes les classes'],
            ['id' => 'viewClassroom',     'name' => 'Voir une classe'],
            ['id' => 'createClassroom',   'name' => 'Créer une classe'],
            ['id' => 'updateClassroom',   'name' => 'Modifier une classe'],
            ['id' => 'deleteClassroom',   'name' => 'Supprimer une classe'],

            ['id' => 'viewAnyAssignment',  'name' => 'Voir toutes les affectations d\'une classe'],
            ['id' => 'viewAssignment',     'name' => 'Voir une affectation'],
            ['id' => 'createAssignment',   'name' => 'Créer une affectation dans une classe'],
            ['id' => 'updateAssignment',   'name' => 'Modifier une affectation'],
            ['id' => 'deleteAssignment',   'name' => 'Supprimer une affectation'],

            ['id' => 'viewAnyWorkType',  'name' => 'Voir tous les types de travail'],
            ['id' => 'viewWorkType',     'name' => 'Voir un type de travail'],
            ['id' => 'createWorkType',   'name' => 'Créer un type de travail'],
            ['id' => 'updateWorkType',   'name' => 'Modifier un type de travail'],
            ['id' => 'deleteWorkType',   'name' => 'Supprimer un type de travail'],

            ['id' => 'viewAnyAppreciation',  'name' => 'Voir toutes les appreciations'],
            ['id' => 'viewAppreciation',     'name' => 'Voir une appreciation'],
            ['id' => 'createAppreciation',   'name' => 'Créer une appreciation'],
            ['id' => 'updateAppreciation',   'name' => 'Modifier une appreciation'],
            ['id' => 'deleteAppreciation',   'name' => 'Supprimer une appreciation'],

            ['id' => 'viewAnyWork',  'name' => 'Voir tous les travaux'],
            ['id' => 'viewWork',     'name' => 'Voir un travail'],
            ['id' => 'createWork',   'name' => 'Créer un travail'],
            ['id' => 'updateWork',   'name' => 'Modifier un travail'],
            ['id' => 'deleteWork',   'name' => 'Supprimer un travail'],

            ['id' => 'viewAnyResult',  'name' => 'Voir tous les résultats'],
            ['id' => 'viewResult',     'name' => 'Voir un résultat'],
            ['id' => 'createResult',   'name' => 'Créer un résultat'],
            ['id' => 'updateResult',   'name' => 'Modifier un résultat'],
            ['id' => 'deleteResult',   'name' => 'Supprimer un résultat'],
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
