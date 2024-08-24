<?php

use App\Models\WorkType;
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
        Schema::create('work_types', function (Blueprint $table) {
            $table->id();
            $table->string('short_name', 10);
            $table->string('name', 30);
            $table->text('comment')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by')->nullable();

            $table->unique('short_name');
        });

        $this->initWorkTypes();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_types');
    }

    public function initWorkTypes(): void
    {

        $workTypes = [
            [
                'short_name' => 'DNS',
                'name' => 'Devoir non surveillé',
            ],
            [
                'short_name' => 'DS',
                'name' => 'Devoir surveillé',
            ],
            [
                'short_name' => 'TP',
                'name' => 'Travaux pratiques',
            ],
            [
                'short_name' => 'ORAL',
                'name' => 'Oral',
            ],
        ];

        foreach ($workTypes as $workType) {
            WorkType::create($workType);
        }
    }
};
