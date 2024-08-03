<?php

use App\Models\Subject;
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
        Schema::create('subjects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('short_name');
            $table->string('name');
            $table->string('comment')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by')->nullable();

            $table->unique('short_name');
        });

        $this->initSubjects();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }

    public function initSubjects(): void
    {

        $subjects = [
            [
                'short_name' => 'FRA',
                'name' => 'Français',
            ],
            [
                'short_name' => 'MATH',
                'name' => 'Mathématiques',
            ],
            [
                'short_name' => 'ENG',
                'name' => 'Anglais',
            ],
            [
                'short_name' => 'DEU',
                'name' => 'Allemand',
            ],
            [
                'short_name' => 'LAT',
                'name' => 'Latin',
            ],
            [
                'short_name' => 'GREC',
                'name' => 'Grec',
            ],
            [
                'short_name' => 'ESP',
                'name' => 'Espagnol',
            ],
            [
                'short_name' => 'HIS/GEO',
                'name' => 'Histoire & Géographie',
            ],
            [
                'short_name' => 'SVT',
                'name' => 'Sciences de la vie et de la terre',
            ],
            [
                'short_name' => 'EPS',
                'name' => 'Education physique & sportive',
            ],
            [
                'short_name' => 'MUS',
                'name' => 'Musique',
            ],
            [
                'short_name' => 'PHI',
                'name' => 'Philosophie',
            ],
            [
                'short_name' => 'ART',
                'name' => 'Art plastique',
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
};
