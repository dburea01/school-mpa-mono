<?php

use App\Models\Role;
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
        Schema::create('roles', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('comment')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_assignable')->default(false);
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        $roles = [
           
            [
                'id' => 'ADMIN',
                'name' => 'Administrateur',
                'is_active' => true,
            ],
            [
                'id' => 'TEACHER',
                'name' => 'Enseignant',
                'is_active' => true,
                'is_assignable' => true,
            ],
            [
                'id' => 'PARENT',
                'name' => 'Parent',
                'is_active' => true,
            ],
            [
                'id' => 'STUDENT',
                'name' => 'Elève',
                'is_active' => true,
                'is_assignable' => true,
            ],
            

        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
