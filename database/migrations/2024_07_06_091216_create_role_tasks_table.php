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
