<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowed_items', function (Blueprint $table) {
            $table->id();
            $table->string('borrower_name'); // Who borrowed the asset
            $table->foreignId('asset_id')->constrained()->onDelete('cascade'); // Reference to assets
            $table->foreignId('laboratory_id')->constrained()->onDelete('cascade'); // Reference to laboratories
            $table->date('date_borrowed');
            $table->date('due_date');
            $table->enum('status', ['pending', 'returned'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowed_items');
    }
};
