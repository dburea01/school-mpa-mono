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
            $table->boolean('displayable')->default(false);
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        $roles = [
            [
                'id' => 'DIRECTOR',
                'name' => 'Directeur',
                'displayable' => true,
            ],
            [
                'id' => 'TEACHER',
                'name' => 'Enseignant',
                'displayable' => true,
            ],
            [
                'id' => 'PARENT',
                'name' => 'Parent',
                'displayable' => true,
            ],
            [
                'id' => 'STUDENT',
                'name' => 'Elève',
                'displayable' => true,
            ],
            [
                'id' => 'SUPERVISOR',
                'name' => 'Surveillant',
                'displayable' => true,
            ],
            [
                'id' => 'CPE',
                'name' => 'Conseiller principal éducation',
                'displayable' => true,
            ],
            [
                'id' => 'ACCOUNTANT',
                'name' => 'Comptable',
                'displayable' => true,
            ],
            [
                'id' => 'SECRETARY',
                'name' => 'Secrétaire',
                'displayable' => true,
            ],
            [
                'id' => 'ADMIN',
                'name' => 'Administrateur',
                'displayable' => true,
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
