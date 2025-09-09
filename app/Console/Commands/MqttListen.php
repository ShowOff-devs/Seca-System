<?php

namespace App\Console\Commands;

use App\Models\RfidLog;
use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttListen extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Subscribe to MQTT topic';

    public function handle()
    {
        $server   = '192.168.78.100'; // Your laptop IP if accessed over LAN
        $port     = 1883;
        $clientId = 'laravel_subscriber';
        $username = null;
        $password = null;

        $mqtt = new MqttClient($server, $port, $clientId);
        $settings = (new ConnectionSettings())
            ->setKeepAliveInterval(60)
            ->setUsername($username)
            ->setPassword($password);

        $mqtt->connect($settings, true);

        $mqtt->subscribe('rfid/scans', function (string $topic, string $message) {

            echo $message;
            // $data = json_decode($message, true);
        
            // \Log::info("[MQTT] Message received:", $data);
        
            // \App\Models\RfidLog::create([
            //     'rfid_no'    => $data['rfid_no'],
            //     'device_id'  => $data['device_id'],
            //     'scan_type'  => $data['scan_type'],
            //     'scanned_at' => $data['scanned_at'],
            // ]);
        }, 0);
        

        $mqtt->loop(true);
    }
}
