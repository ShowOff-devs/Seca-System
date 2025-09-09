<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Asset Movement PDF Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .logo-header {
            text-align: center;
            margin-bottom: 15px;
        }
        .report-title {
            text-align: center;
            margin-bottom: 8px;
        }
        .report-info {
            margin-bottom: 12px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border: .5px solid #222;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: .5px solid #222;
            padding: 6px 5px;
            text-align: left;
        }
        th {
            background: #f5f5f5;
        }
    </style>
</head>
<body>
    <!-- LOGO -->
    <div class="logo-header">
        <img src="{{ public_path('assets/images/seca.png') }}" alt="Logo" style="height:65px;">
    </div>

    <!-- TITLE -->
    <h2 class="report-title">Asset Movement Report</h2>

    <!-- FILTER INFO -->
    <div class="report-info">
        <div>
            <strong>Date Range:</strong>
            {{ date('M d, Y', strtotime($report->start_date)) }} - {{ date('M d, Y', strtotime($report->end_date)) }}
        </div>
        <div>
            <strong>Laboratory:</strong>
            @if(isset($selectedLab) && $selectedLab)
                {{ $selectedLab->name }}
            @else
                All Laboratories
            @endif
        </div>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th>Asset</th>
                <th>RFID</th>
                <th>Laboratory</th>
                <th>Movement</th>
                <th>Date and Time</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($movements as $movement)
            <tr>
                <td>
                    @php
                        $asset = \App\Models\Asset::where('rfid_no', $movement->rfid_no)->first();
                    @endphp
                    {{ $asset ? $asset->name : '-' }}
                </td>
                <td>{{ $movement->rfid_no }}</td>
                <td>
                    @php
                        $lab = \App\Models\Laboratory::find($movement->device_id);
                    @endphp
                    {{ $lab ? $lab->name : '-' }}
                </td>
                <td>{{ strtoupper($movement->scan_type) }}</td>
                <td>{{ date('M d, Y h:i:s A', strtotime($movement->created_at)) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
