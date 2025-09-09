<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Models\Asset;
use App\Models\Log;
use Illuminate\Support\Facades\Log as LaravelLog;

class MqttRfidSubscriber extends Command
{
    protected $signature = 'mqtt:rfid-listen';
    protected $description = 'Subscribe to MQTT topic for RFID scans';

    public function handle()
    {
        $mqtt = new MqttClient('192.168.29.7', 1883, 'laravel_subscriber');
        $settings = new ConnectionSettings;
        $mqtt->connect($settings, true);

        $mqtt->subscribe('rfid/scans', function (string $topic, string $message) {
            $data = json_decode($message, true);

            if (!$data) return;

            // Optional: Save to DB
            $asset = Asset::where('rfid_no', $data['rfid_no'])->first();
            if ($asset) {
                Log::create([
                    'description' => "Asset '{$asset->name}' scanned as {$data['scan_type']} at device {$data['device_id']}",
                ]);
            }

            LaravelLog::info("RFID Scan received: " . $message);
        }, 0);

        $mqtt->loop(true);
        $mqtt->disconnect();
    }
}
