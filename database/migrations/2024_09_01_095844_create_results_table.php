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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id');
            $table->uuid('work_id');
            $table->decimal('note')->nullable();
            $table->tinyInteger('appreciation_id')->nullable();
            $table->string('comment')->nullable();
            $table->tinyInteger('is_absent')->default(0)->comment('1 => is absent / 0 => is NOT absent');
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by')->nullable();

            $table->unique(['user_id', 'work_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');
            $table->foreign('appreciation_id')->references('id')->on('appreciations')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
