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
        Schema::create('candidates', function (Blueprint $table) {
           $table->id(); // Auto-incrementing primary key
            $table->string('company')->nullable();
            $table->string('candidate_id')->nullable(); // Assuming candidate_id is unique
            $table->string('contact_number')->nullable();
            $table->string('name')->nullable();
            $table->string('ic_number')->nullable(); // For identification number
            $table->string('dob')->nullable(); // Date of birth
            $table->string('gender')->nullable();
            $table->string('interview_date')->nullable();
            $table->string('religion')->nullable();
            $table->string('state')->nullable();
            $table->text('address')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('join_accommodation')->nullable();
            $table->string('exit_accommodation')->nullable(); 
            $table->string('join_company')->nullable(); 
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
