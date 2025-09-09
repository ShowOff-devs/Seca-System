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
            Schema::create('rfid_logs', function (Blueprint $table) {
                $table->id();
                $table->string('rfid_no');
                $table->string('device_id');
                $table->enum('scan_type', ['IN','OUT']);
                $table->timestamp('scanned_at');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('rfid_logs');
        }
    };
