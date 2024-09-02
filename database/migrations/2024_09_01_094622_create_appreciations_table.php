<?php

use App\Models\Appreciation;
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
        Schema::create('appreciations', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('position');
            $table->string('short_name', 10);
            $table->string('name', 30);
            $table->string('comment')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('short_name');
        });

        $this->initAppreciations();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appreciations');
    }

    public function initAppreciations(): void
    {
        $initAppreciations = [
            [
                'position' => 10,
                'short_name' => 'EXC',
                'name' => 'Excellent',
            ],
            [
                'position' => 20,
                'short_name' => 'TB',
                'name' => 'Très bien',
            ],
            [
                'position' => 30,
                'short_name' => 'B',
                'name' => 'Bien',
            ],
            [
                'position' => 40,
                'short_name' => 'PAS',
                'name' => 'Passable',
            ],
            [
                'position' => 50,
                'short_name' => 'INS',
                'name' => 'Insuffisant',
            ],
        ];

        foreach ($initAppreciations as $initAppreciation) {
            Appreciation::create($initAppreciation);
        }
    }
};
