<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First create the table without foreign keys
        Schema::create('tracking_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id');
            $table->unsignedBigInteger('laboratory_id');
            $table->enum('movement_type', ['in', 'out']);
            $table->timestamp('timestamp');
            $table->string('rfid_no');
            $table->timestamps();
        });

        // Then add the foreign key constraints
        Schema::table('tracking_logs', function (Blueprint $table) {
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->foreign('laboratory_id')->references('id')->on('laboratories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_logs');
    }
}; 