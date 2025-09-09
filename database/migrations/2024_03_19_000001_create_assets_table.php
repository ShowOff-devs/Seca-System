<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('rfid_no')->unique();
            $table->string('serial_number')->unique();
            $table->enum('type', ['hardware', 'consumables', 'others']);
            $table->text('description')->nullable();
            $table->date('date_added');
            $table->unsignedBigInteger('laboratory_id')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('laboratory_id')
                  ->references('id')
                  ->on('laboratories')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
}; 