<?php

use App\Models\WorkStatus;
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
        Schema::create('work_statuses', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('name', 50);
            $table->text('comment')->nullable();
            $table->tinyInteger('position');
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
        });

        $workStatuses = [
            [
                'id' => 'PLANNED',
                'name' => 'Planifié',
                'position' => 10,
            ],
            [
                'id' => 'INPROGRESS',
                'name' => 'En cours',
                'position' => 20,
            ],
            [
                'id' => 'TERMINATED',
                'name' => 'Terminé',
                'position' => 30,
            ],
            [
                'id' => 'INCORRECTION',
                'name' => 'Correction en cours',
                'position' => 40,
            ],
            [
                'id' => 'CORRECTED',
                'name' => 'Correction terminée',
                'position' => 50,
            ],
        ];

        foreach ($workStatuses as $workStatus) {
            WorkStatus::create($workStatus);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_statuses');
    }
};
