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
            $table->string('role_id');
            $table->string('login_status_id');
            $table->string('civility_id', 10)->nullable();
            $table->char('gender_id', 1)->nullable()->comment('1 : boy / 2 : girl');
            $table->date('birth_date')->nullable();

            $table->text('other_comment')->nullable();
            $table->text('health_comment')->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->string('country_id')->nullable();

            $table->string('phone_number')->nullable();
            $table->string('email')->unique();
            $table->string('email_verification_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('photo_url')->nullable();
            
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('country_id')->references('id')->on('countries')->nullOnDelete();
            $table->foreign('civility_id')->references('id')->on('civilities')->nullOnDelete();
            $table->foreign('login_status_id')->references('id')->on('login_statuses');
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
