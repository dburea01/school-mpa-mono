<?php

use App\Models\RoleTask;
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
        Schema::create('role_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('role_id');
            $table->string('task_id');
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->unique(['task_id', 'role_id']);
        });

        // As the admin is authorized to all tasks, not necessary to create a role_task for him
        $roleTasks = [
            ['role_id' => 'TEACHER', 'task_id' => 'viewAnyUser'],
            ['role_id' => 'TEACHER', 'task_id' => 'viewUser'],
            ['role_id' => 'TEACHER', 'task_id' => 'viewAnyGroup'],
            ['role_id' => 'TEACHER', 'task_id' => 'viewGroup'],
            ['role_id' => 'TEACHER', 'task_id' => 'viewAnySubject'],
            ['role_id' => 'TEACHER', 'task_id' => 'viewSubject'],
            ['role_id' => 'TEACHER', 'task_id' => 'viewAnyClassroom'],
            ['role_id' => 'TEACHER', 'task_id' => 'viewClassroom'],
            ['role_id' => 'TEACHER', 'task_id' => 'viewAnyAssignment'],
            ['role_id' => 'TEACHER', 'task_id' => 'viewAssignment'],
            ['role_id' => 'TEACHER', 'task_id' => 'viewAnyWork'],
            ['role_id' => 'TEACHER', 'task_id' => 'viewWork'],
            ['role_id' => 'TEACHER', 'task_id' => 'createWork'],
            ['role_id' => 'TEACHER', 'task_id' => 'updateWork'],
            ['role_id' => 'TEACHER', 'task_id' => 'deleteWork'],

            ['role_id' => 'TEACHER', 'task_id' => 'viewAnyResult'],
            ['role_id' => 'TEACHER', 'task_id' => 'viewResult'],
            ['role_id' => 'TEACHER', 'task_id' => 'createResult'],
            ['role_id' => 'TEACHER', 'task_id' => 'updateResult'],
            ['role_id' => 'TEACHER', 'task_id' => 'deleteResult'],
        ];

        foreach ($roleTasks as $roleTask) {
            RoleTask::create($roleTask);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_tasks');
    }
};
