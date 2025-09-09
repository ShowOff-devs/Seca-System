<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;
use App\Models\RfidScan;
use App\Models\Asset;
use App\Models\Log;
use Illuminate\Support\Facades\Broadcast;

class MqttRfidListener extends Command
{
    protected $signature = 'mqtt:listen-rfid';
    protected $description = 'Subscribe to rfid/scans and store to DB';

    public function handle()
    {
        $clientId = 'laravel-subscriber-' . uniqid();
        $mqtt = MQTT::connection();

        $mqtt->subscribe('rfid/scans', function (string $topic, string $message) {
            $data = json_decode($message, true);

            if (!$data || !isset($data['rfid_no'])) {
                return;
            }

            $asset = \App\Models\Asset::where('rfid_no', $data['rfid_no'])->first();
            if (!$asset) {
                Log::create(['description' => "Unknown RFID scanned: {$data['rfid_no']}"]);
                return;
            }

            $scan = RfidScan::create([
                'rfid_no'    => $data['rfid_no'],
                'device_id'  => $data['device_id'],
                'scan_type'  => $data['scan_type'],
                'scanned_at' => $data['scanned_at'],
            ]);

            Log::create([
                'description' => "Asset '{$asset->name}' scanned as {$data['scan_type']} from {$data['device_id']}"
            ]);

            broadcast(new \App\Events\RfidScanned($scan, $asset))->toOthers();
        });

        $mqtt->loop(true); // run forever
    }
}
