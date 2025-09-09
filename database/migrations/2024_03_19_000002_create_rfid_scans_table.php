<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rfid_scans', function (Blueprint $table) {
            $table->id();
            $table->string('rfid_no');
            $table->string('device_id');
            $table->string('scan_type')->default('IN'); // IN or OUT
            $table->timestamp('scanned_at');
            $table->timestamps();
            
            // Add foreign key if the RFID exists in assets table
            // $table->foreign('rfid_no')
            //       ->references('rfid_tag')
            //       ->on('assets')
            //       ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rfid_scans');
    }
}; 