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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_name');
            $table->string('report_type'); // monthly, weekly, annual, custom
            $table->date('start_date');
            $table->date('end_date');
            $table->json('report_parameters')->nullable(); // Store custom report parameters
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->string('file_path')->nullable(); // Path to generated report file
            $table->string('schedule_frequency')->nullable(); // For scheduled reports
            $table->timestamp('last_generated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
