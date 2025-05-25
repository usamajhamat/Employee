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
        Schema::create('network_infos', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('network_id')->nullable(); // Corresponds to "network_id"
            $table->unsignedBigInteger('device_id')->unique(); // Corresponds to "id"
            $table->string('label')->nullable(); // Corresponds to "label"
            $table->macAddress('mac_address')->nullable(); // Corresponds to "mac_address"
            $table->enum('status', ['online', 'offline'])->default('offline'); // Corresponds to "status"
            $table->string('public_ip_address')->nullable(); // Corresponds to "public_ip_address"
            $table->string('add_date')->nullable(); // Corresponds to "add_date"
            $table->bigInteger('last_timestamp')->nullable(); // UNIX timestamp
            $table->integer('last_uptime')->nullable(); // in minutes
            $table->bigInteger('last_bytes_rx')->nullable();
            $table->bigInteger('last_bytes_tx')->nullable();
            $table->bigInteger('last_pkts_rx')->nullable();
            $table->bigInteger('last_pkts_tx')->nullable();
            $table->tinyInteger('last_load_percentage')->nullable(); // 0–100
            $table->bigInteger('last_total_ram')->nullable();
            $table->bigInteger('last_free_ram')->nullable();
            $table->bigInteger('last_used_ram')->nullable();
            $table->tinyInteger('last_used_ram_percentage')->nullable(); // 0–100
            $table->timestamps(); // created_at and updated_at

            // Foreign key constraint to the networks table
            $table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_infos');
    }
};
