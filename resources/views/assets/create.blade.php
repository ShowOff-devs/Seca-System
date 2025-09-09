@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add New Asset</h2>
    <form action="{{ route('inventory.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
    <label>RFID No</label>
    <div class="input-group">
        <input type="text" id="rfid_no" name="rfid_no" class="form-control" placeholder="Scan or type manually">
        <button type="button" class="btn btn-info" id="scan-rfid-btn">Scan</button>
    </div>
    <small class="form-text text-muted">If scan doesn’t work, you can manually type the RFID number.</small>
</div>

        <div class="mb-3">
            <label>Serial Number</label>
            <input type="text" name="serial_number" class="form-control">
        </div>

        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control">
                <option value="hardware">Hardware</option>
                <option value="consumables">Consumables</option>
                <option value="others">Others</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Assigned Laboratory</label>
            <select name="laboratory_id" class="form-control" required>
                <option value="">Select Laboratory</option>
                @foreach($laboratories as $lab)
                    <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save Asset</button>
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script src="https://unpkg.com/mqtt@5.13.0/dist/mqtt.min.js"></script>
<script>
    const url = 'ws://192.168.80.7:9010' // no /mqtt unless broker requires it
    const options = {
        clean: true,
        connectTimeout: 10000,
        clientId: 'emqx_test',
        username: '',
        password: '',
    }

    const client = mqtt.connect(url, options)

    client.on('connect', function () {
        console.log('Connected')
        client.subscribe('rfid/scans', function (err) {
            if (!err) {
                client.publish('test', 'Hello mqtt')
            }
        })
    })

    client.on('message', function (topic, message) {
        try {
            const payload = JSON.parse(message.toString());
            const rfid = payload.rfid_no;
            console.log('RFID Number:', rfid);
            $("#rfid_no").val(rfid);
        } catch (error) {
            console.error('Failed to parse message:', error);
        }
        
    })

    client.on('error', function (err) {
        console.error('Connection error:', err)
    })
</script>

{{-- <script>
    $("#scan-rfid-btn").click(function() {
        console.log('hi');
        client.on('connect', () => {
            console.log('Connected to MQTT broker');
            client.subscribe('rfid/scans', (err) => {
                if (err) {
                    console.error('Subscription error:', err);
                }
            });
        });
    });

    const options = {
        clientId: 'web_client_' + Math.random().toString(16).substr(2, 8),
        username: '', // optional
        password: '', // optional
    };

    // Replace 'localhost' with your broker IP if accessed over LAN
    const client = mqtt.connect('ws://localhost:1883', options); // assumes broker is WebSocket-enabled

    client.on('connect', () => {
        console.log('Connected to MQTT broker');
        client.subscribe('rfid/scans', (err) => {
            if (err) {
                console.error('Subscription error:', err);
            }
        });
    });

    client.on('message', (topic, message) => {
        console.log(`Received on ${topic}: ${message}`);
        try {
            const data = JSON.parse(message.toString());
            document.getElementById('rfid_no').value = data.rfid_no || message.toString();
        } catch (e) {
            // Fallback if message is not JSON
            document.getElementById('rfid_no').value = message.toString();
        }
    });

    client.on('error', (err) => {
        console.error('MQTT Error:', err);
    });
</script> --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></>

{{-- <script>
document.addEventListener('DOMContentLoaded', () => {
    let scanning = false;
    let timeout = null;

    const scanBtn   = document.getElementById('scan-rfid-btn');
    const rfid_no = document.getElementById('rfid_no');

    

    scanBtn.addEventListener('click', () => {
    if (scanning) return;
    scanning = true;
    scanBtn.disabled = true;
    scanBtn.textContent = 'Waiting for scan…';
    rfid_no.readOnly = true; // lock manual typing while scanning
    rfid_no.value = '';

    timeout = setTimeout(() => {
        if (scanning) {
            scanning = false;
            scanBtn.disabled = false;
            scanBtn.textContent = 'Scan';
            rfid_no.readOnly = false; // re-enable manual typing
            alert('No RFID Tag scanned.');
        }
    }, 10000);
});

window.Echo.channel('rfid')
    .listen('.scanned', e => {
        if (!scanning) return;

        rfid_no.value = e.rfid_no;
        scanning = false;
        scanBtn.disabled = false;
        scanBtn.textContent = 'Scan';
        rfid_no.readOnly = false;

        playBeep();

        if (timeout) clearTimeout(timeout);
    });

    function playBeep() {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = ctx.createOscillator();
        const gainNode = ctx.createGain();

        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(1000, ctx.currentTime); // 1 kHz
        gainNode.gain.setValueAtTime(0.2, ctx.currentTime);

        oscillator.connect(gainNode);
        gainNode.connect(ctx.destination);

        oscillator.start();
        oscillator.stop(ctx.currentTime + 0.15); // Beep for 150ms
    }
});
</script> --}}

<script>
 
</script>
@endpush
