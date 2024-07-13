<?php

use App\Models\LoginStatus;
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
        Schema::create('login_statuses', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->string('name');
            $table->integer('position')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        $loginStatuses = [
            [
                'id' => 'CREATED',
                'name' => 'Profil créé, à valider. Connection impossible.',
                'position' => 10,
            ],
            [
                'id' => 'VALIDATED',
                'name' => 'Profil validé. Connection possible.',
                'position' => 20,
            ],
            [
                'id' => 'BLOCKED',
                'name' => 'Profil bloqué. Connection impossible.',
                'position' => 30,
            ],
        ];

        foreach ($loginStatuses as $loginStatus) {
            LoginStatus::create($loginStatus);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_statuses');
    }
};
