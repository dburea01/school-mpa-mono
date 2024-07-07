<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roles = [
            ['name' => 'administrateur'],
            ['name' => 'enseignant'],
            ['name' => 'parent'],
            ['name' => 'enfant']
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        $permissions = [
            ['name' => 'Afficher liste des utilisateurs'],
            ['name' => 'Afficher un utilisateur'],
            ['name' => 'Créer un utilisateur'],
            ['name' => 'Modifier un utilisateur'],
            ['name' => 'Supprimer un utilisateur']
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // assign permissions to the role teacher
        /** @var Role $teacherRole */
        $teacherRole = Role::where('name', 'enseignant')->first();
        $teacherRole->givePermissionTo([
            'Afficher liste des utilisateurs',
            'Afficher un utilisateur'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Role::truncate();
        Permission::truncate();
    }
};
