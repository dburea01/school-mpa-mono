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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // $table->integer('role_id');
            $table->string('last_name');
            $table->string('first_name');
            $table->char('gender_id', 1)->nullable()->comment('1 : boy / 2 : girl');
            $table->char('civility_id', 10)->nullable()->comment('Mde / Melle / Mr');
            $table->date('birth_date')->nullable();
            $table->string('email')->unique();
            $table->string('email_verification_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('login_status_id');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
