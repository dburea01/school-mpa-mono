<?php

use App\Models\Civility;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('civilities', function (Blueprint $table) {
            $table->string('id', 10)->primary();
            $table->text('short_name');
            $table->text('name');
            $table->boolean('is_active')->default('true');
            $table->tinyInteger('position');
            $table->timestamps();
        });

        $civilities = [
            [
                'id' => 'MR',
                'short_name' => 'M',
                'name' => 'Monsieur',
                'position' => 10,
            ],
            [
                'id' => 'MISS',
                'short_name' => 'Mde',
                'name' => 'Madame',
                'position' => 20,
            ],
            [
                'id' => 'MELLE',
                'short_name' => 'Melle',
                'name' => 'Mademoiselle',
                'position' => 30,
            ],
        ];

        foreach ($civilities as $civility) {
            Civility::create($civility);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('civilities');
    }
};
