<?php

namespace App\Events;

use App\Models\RfidLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class RfidScanned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $rfid_no;
    public $device_id;
    public $scan_type;
    public $scanned_at;

    public function __construct(RfidLog $log)
    {
        $this->rfid_no    = $log->rfid_no;
        $this->device_id  = $log->device_id;
        $this->scan_type  = $log->scan_type;
        $this->scanned_at = $log->scanned_at;
    }

    public function broadcastOn()
    {
        return new Channel('rfid');
    }

    public function broadcastAs()
    {
        return 'scanned';
    }

    public function broadcastWith()
    {
        return [
            'rfid_no'    => $this->rfid_no,
            'device_id'  => $this->device_id,
            'scan_type'  => $this->scan_type,
            'scanned_at' => $this->scanned_at,
        ];
    }
}
