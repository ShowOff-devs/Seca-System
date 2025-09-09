@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
    <div class="card shadow-sm border-0" style="background: #22243a; border-radius: 18px;">
        <div class="card-body d-flex flex-column align-items-start py-4">
            <span 
                class="d-inline-flex align-items-center justify-content-center mb-2" 
                style="background: #27ae60; color: #fff; width: 48px; height: 48px; border-radius: 50%; font-size: 1.6rem; font-weight: bold;">
                {{$moved_in}}
            </span>
            <span class="fw-semibold" style="color: #b7b9dc; font-size: 1.1rem;">
                No. of Assets <span style="color: #27ae60;">Moved IN</span>
            </span>
        </div>
    </div>
</div>
<div class="col-xl-3 col-md-6 mb-4">
    <div class="card shadow-sm border-0" style="background: #22243a; border-radius: 18px;">
        <div class="card-body d-flex flex-column align-items-start py-4">
            <span 
                class="d-inline-flex align-items-center justify-content-center mb-2" 
                style="background: #e74c3c; color: #fff; width: 48px; height: 48px; border-radius: 50%; font-size: 1.6rem; font-weight: bold;">
                {{$moved_out}}
            </span>
            <span class="fw-semibold" style="color: #b7b9dc; font-size: 1.1rem;">
                No. of Assets <span style="color: #e74c3c;">Moved OUT</span>
            </span>
        </div>
    </div>
</div>


{{-- Search Bar Row --}}
        <div class="row mt-4">
    <div class="col-12 d-flex justify-content-end mb-3">
        <form action="{{ route('tracking.search') }}" method="GET" class="d-flex align-items-center" style="gap: 8px; max-width: 350px;">
            <input 
                type="text" 
                name="query" 
                value="{{ request('query') }}" 
                class="form-control bg-dark text-light border-0 rounded-pill px-3 py-2 shadow-sm"
                placeholder="Search by RFID, Asset, or Laboratory..."
                style="min-width: 220px; font-size: 15px;"
                autocomplete="off"
            >
            <button 
                class="btn rounded-pill px-4 py-2"
                type="submit"
                style="background: linear-gradient(90deg, #2196F3 0%, #0D47A1 100%); color: #fff; border: none; font-weight: 500;"
            >
                Search
            </button>
        </form>
    </div>
</div>

        
        <!-- Real-time Tracking Section -->
        {{-- <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Real-time Asset Tracking</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" id="scanStatus">
                        Waiting for RFID scan...
                    </div>
                    <div id="lastScan" class="mb-4" style="display: none;">
                        <h5>Last Scan:</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Asset Name:</th>
                                    <td id="assetName"></td>
                                </tr>
                                <tr>
                                    <th>RFID No:</th>
                                    <td id="rfidNo"></td>
                                </tr>
                                <tr>
                                    <th>Movement:</th>
                                    <td id="movementType"></td>
                                </tr>
                                <tr>
                                    <th>Laboratory:</th>
                                    <td id="laboratoryName"></td>
                                </tr>
                                <tr>
                                    <th>Time:</th>
                                    <td id="scanTime"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Laboratory Selection -->
        {{-- <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Select Laboratory</h4>
                </div>
                <div class="card-body">
                    <select class="form-control" id="laboratorySelect">
                        <option value="">Select Laboratory</option>
                        @foreach($laboratories as $lab)
                            <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- Recent Movements -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Recent Movements</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Asset</th>
                                    <th>RFID No</th>
                                    <th>Movement</th>
                                    <th>Laboratory</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody id="movementsTable">
                                @foreach($recentMovements as $movement)
                                <tr>
                                    <td>
                                        @php
                                            $asset = App\Models\Asset::where('rfid_no', $movement->rfid_no)->first();
                                        @endphp
                                        @if ($asset)
                                        {{$asset->name}}
                                        @endif
                                    </td>
                                    <td>{{ $movement->rfid_no }}</td>
                                    <td>
                                        <span class="badge {{ $movement->scan_type === 'IN' ? 'badge-success' : 'badge-danger' }}">
                                            {{ strtoupper($movement->movement_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->device_id }}</td>
                                    <td>{{ $movement->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(function() {
        window.location.reload();
    }, 5000);
</script>
<!-- MQTT Client Script -->
<script src="https://unpkg.com/paho-mqtt/mqttws31.min.js"></script>
<script>
    let client = null;
    let selectedLaboratory = null;

    // Initialize tracking mode MQTT client
    const trackingClient = new Paho.MQTT.Client("192.168.29.7", 9010, "tracking-page-" + Math.random());

    trackingClient.connect({
        onSuccess: function () {
            console.log("[MQTT] Connected - switching to tracking mode");
            let msg = new Paho.MQTT.Message("tracking");
            msg.destinationName = "rfid/mode";
            trackingClient.send(msg);
        },
        onFailure: function () {
            console.warn("Failed to connect to MQTT broker.");
        }
    });

    // Initialize MQTT connection
    function initMQTT() {
        const broker = "ws://192.168.29.7:1883/mqtt";
        const clientId = "webclient-" + Math.random();
        
        client = new Paho.MQTT.Client(broker, clientId);
        
        client.onMessageArrived = onMessageArrived;
        client.onConnectionLost = onConnectionLost;
        
        client.connect({
            onSuccess: onConnect,
            onFailure: onFailure
        });
    }

    function onConnect() {
        console.log("Connected to MQTT broker");
        client.subscribe("rfid/scan");
    }

    function onFailure(error) {
        console.error("Connection failed:", error);
        document.getElementById('scanStatus').className = 'alert alert-danger';
        document.getElementById('scanStatus').textContent = 'Failed to connect to MQTT broker';
    }

    function onConnectionLost(responseObject) {
        if (responseObject.errorCode !== 0) {
            console.log("Connection lost:", responseObject.errorMessage);
            document.getElementById('scanStatus').className = 'alert alert-warning';
            document.getElementById('scanStatus').textContent = 'Connection lost. Reconnecting...';
            setTimeout(initMQTT, 5000);
        }
    }

    function onMessageArrived(message) {
        const rfidNo = message.payloadString;
        if (!selectedLaboratory) {
            document.getElementById('scanStatus').className = 'alert alert-warning';
            document.getElementById('scanStatus').textContent = 'Please select a laboratory first';
            return;
        }

        // Send scan data to server
        fetch('/tracking/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                rfid_no: rfidNo,
                laboratory_id: selectedLaboratory
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateLastScan(data.data);
                updateMovementsTable(data.data);
            } else {
                document.getElementById('scanStatus').className = 'alert alert-danger';
                document.getElementById('scanStatus').textContent = data.message;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('scanStatus').className = 'alert alert-danger';
            document.getElementById('scanStatus').textContent = 'Error processing scan';
        });
    }

    function updateLastScan(data) {
        document.getElementById('lastScan').style.display = 'block';
        document.getElementById('assetName').textContent = data.asset.name;
        document.getElementById('rfidNo').textContent = data.asset.rfid_no;
        document.getElementById('movementType').textContent = data.movement.movement_type.toUpperCase();
        document.getElementById('laboratoryName').textContent = data.laboratory.name;
        document.getElementById('scanTime').textContent = new Date(data.movement.timestamp).toLocaleString();
        
        document.getElementById('scanStatus').className = 'alert alert-success';
        document.getElementById('scanStatus').textContent = 'Scan processed successfully';
    }

    function updateMovementsTable(data) {
        const tbody = document.getElementById('movementsTable');
        const newRow = document.createElement('tr');
        
        newRow.innerHTML = `
            <td>${data.asset.name}</td>
            <td>${data.asset.rfid_no}</td>
            <td>
                <span class="badge ${data.movement.movement_type === 'in' ? 'badge-success' : 'badge-danger'}">
                    ${data.movement.movement_type.toUpperCase()}
                </span>
            </td>
            <td>${data.laboratory.name}</td>
            <td>${new Date(data.movement.timestamp).toLocaleString()}</td>
        `;
        
        tbody.insertBefore(newRow, tbody.firstChild);
        if (tbody.children.length > 10) {
            tbody.removeChild(tbody.lastChild);
        }
    }

    // Laboratory selection handler
    document.getElementById('laboratorySelect').addEventListener('change', function(e) {
        selectedLaboratory = e.target.value;
        if (selectedLaboratory) {
            document.getElementById('scanStatus').className = 'alert alert-info';
            document.getElementById('scanStatus').textContent = 'Ready for scanning';
        } else {
            document.getElementById('scanStatus').className = 'alert alert-warning';
            document.getElementById('scanStatus').textContent = 'Please select a laboratory';
        }
    });

    // Initialize MQTT connection when page loads
    document.addEventListener('DOMContentLoaded', initMQTT);
</script>
@endsection 