<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('company_name');
            $table->date('date');

            // Recruitment Metrics
            $table->unsignedInteger('total_candidates')->default(0);
            $table->unsignedInteger('interviewed')->default(0);
            $table->unsignedInteger('passed')->default(0);

            // Employee Type Distribution
            $table->unsignedInteger('hostel')->default(0);
            $table->unsignedInteger('walkin')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_reports');
    }
};
