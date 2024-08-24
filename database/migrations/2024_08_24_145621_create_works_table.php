<?php

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
        Schema::create('works', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('work_type_id')->nullable();
            $table->uuid('subject_id');
            $table->uuid('classroom_id');
            $table->string('work_status_id');
            $table->string('title');
            $table->decimal('note_min', 8, 2)->nullable();
            $table->decimal('note_max', 8, 2)->nullable();
            $table->decimal('note_increment', 8, 2)->nullable();
            $table->text('description')->nullable();
            $table->date('given_at')->nullable();
            $table->date('expected_at')->nullable();
            $table->tinyInteger('estimated_duration')->nullable()->comment('duration in minutes');
            $table->text('instruction')->nullable();

            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by')->nullable();

            $table->foreign('work_type_id')->references('id')->on('work_types')->nullOnDelete();
            $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();
            $table->foreign('classroom_id')->references('id')->on('classrooms')->cascadeOnDelete();
            $table->foreign('work_status_id')->references('id')->on('work_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('works');
    }
};
