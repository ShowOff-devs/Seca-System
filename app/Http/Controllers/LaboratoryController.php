<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laboratory;
use App\Models\Asset;
use App\Models\Log;

class LaboratoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laboratories = Laboratory::withCount('assets')->paginate(10);
        return view('laboratories.index', compact('laboratories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('laboratories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:laboratories',
            'location' => 'required|string|max:255',
        ]);

        Laboratory::create($request->all());
        Log::create(['description' => "Laboratory '{$request->name}' created."]);

        return redirect()->route('laboratories.index')->with('success', 'Laboratory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Laboratory $laboratory, Request $request)
    {
        $query = $laboratory->assets();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $assets = $query->paginate(10);
        return view('laboratories.show', compact('laboratory', 'assets'));
    }

    /**
     * Show the form for editing the specified laboratory.
     */
    public function edit(Laboratory $laboratory)
    {
        return view('laboratories.edit', compact('laboratory'));
    }

    /**
     * Update the specified laboratory in storage.
     */
    public function update(Request $request, Laboratory $laboratory)
    {
        $request->validate([
            'name' => 'required|unique:laboratories,name,' . $laboratory->id,
            'location' => 'required|string|max:255',
        ]);

        $laboratory->update($request->all());
        Log::create(['description' => "Laboratory '{$laboratory->name}' updated."]);

        return redirect()->route('laboratories.index')->with('success', 'Laboratory updated successfully.');
    }

    /**
     * Remove the specified laboratory from storage.
     */
    public function destroy(Laboratory $laboratory)
    {
        $laboratoryName = $laboratory->name;
        $laboratory->delete();
        Log::create(['description' => "Laboratory '{$laboratoryName}' deleted."]);
        return redirect()->route('laboratories.index')->with('success', 'Laboratory deleted successfully.');
    }
}
