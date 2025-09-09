<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Laboratory;
use App\Models\Log;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with('laboratory');

        // Filtering by status
        if ($request->has('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('status', $request->status);
        }

        // Searching by name, serial number, or RFID number
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('serial_number', 'LIKE', "%$search%")
                  ->orWhere('rfid_no', 'LIKE', "%$search%");
            });
        }

        $assets = $query->paginate(10);
        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        $laboratories = Laboratory::all();
        return view('assets.create', compact('laboratories'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'rfid_no' => 'nullable|string|unique:assets,rfid_no|max:255',
        //     'serial_number' => 'required|string|unique:assets,serial_number|max:255',
        //     'type' => 'required|in:hardware,consumables,others',
        //     'date_added' => 'required|date',
        //     'laboratory_id' => 'nullable|exists:laboratories,id',
        //     'status' => 'required|in:active,inactive'
        // ]);
        Asset::create($request->all());
        Log::create(['description' => "Asset '{$request->name}' created."]);
        return redirect()->route('inventory.index')->with('success', 'Asset added successfully.');
    }

    public function show(Asset $asset)
    {
        return view('assets.show', compact('asset'));
    }

    public function edit($asset_id)
    {
        $asset = Asset::findOrFail($asset_id);
        $laboratories = Laboratory::all();
        return view('assets.edit', compact('asset', 'laboratories'));
    }

    public function update(Request $request, $asset_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'rfid_no' => 'nullable|string|unique:assets,rfid_no,' . $asset->id,
            // 'serial_number' => 'required|string|unique:assets,serial_number,' . $asset->id,
            // 'type' => 'required|in:hardware,consumables,others',
            // 'date_added' => 'required|date',
            // 'laboratory_id' => 'nullable|exists:laboratories,id',
            // 'status' => 'required|in:active,inactive'
        ]);
        $asset = Asset::findOrFail($asset_id);
        $asset->update($request->all());
        Log::create(['description' => "Asset '{$asset->name}' updated."]);
        return redirect()->route('inventory.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy($asset_id)
    {
        $asset = Asset::findOrFail($asset_id);
        $asset->delete();
        Log::create(['description' => "Asset '{$asset->name}' deleted."]);
        return redirect()->route('inventory.index')->with('success', 'Asset deleted successfully.');
    }
}
