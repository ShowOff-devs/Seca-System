<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Models\RfidLog;
use App\Events\RfidScanned;
use Carbon\Carbon;

class SubscribeMqtt extends Command
{
    protected $signature = 'mqtt:subscribe';
    protected $description = 'Subscribe to MQTT topic and store RFID logs';

    public function handle()
    {
        $server = '192.168.29.7';
        $port = 1883;
        $clientId = 'laravel-subscriber';

        $mqtt = new MqttClient($server, $port, $clientId);
        $settings = new ConnectionSettings();

        $mqtt->connect($settings, true);
        $this->info("Connected to MQTT broker.");

        $mqtt->subscribe('rfid/scans', function (string $topic, string $message) {
            $data = json_decode($message, true);
            if (!$data) {
                \Log::warning('Invalid JSON: ' . $message);
                return;
            }

            $log = RfidLog::create([
                'rfid_no' => $data['rfid_no'],
                'device_id' => $data['device_id'],
                'scan_type' => $data['scan_type'],
                'scanned_at' => Carbon::parse($data['scanned_at']),
            ]);

            event(new RfidScanned($log));

            \Log::info("RFID logged: " . $log->rfid_no);
        }, 0);

        $mqtt->loop(true);
    }
}
