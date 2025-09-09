<?php

namespace App\Http\Controllers;

use App\Models\BorrowedItems;
use App\Models\Asset;
use App\Models\Laboratory;
use Illuminate\Http\Request;
use App\Models\Log;

class BorrowedItemsController extends Controller
{
    // Display a list of borrowed items
    public function index()
    {
        $borrowedItems = BorrowedItems::with(['asset', 'laboratory'])->paginate(10);
        return view('borrowed_items.index', compact('borrowedItems'));
    }

    // Show the form for creating a new borrowed item
    public function create()
    {
        $assets = \App\Models\Asset::all();
        $laboratories = \App\Models\Laboratory::all();
        return view('borrowed_items.create', compact('assets', 'laboratories'));
    }

    // Store a new borrowed item in the database
    public function store(Request $request)
    {
        $request->validate([
            'borrower_name' => 'required|string|max:255',
            'asset_id' => 'required|exists:assets,id',
            'laboratory_id' => 'required|exists:laboratories,id',
            'date_borrowed' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:date_borrowed',
            // 'status' => 'required|in:Borrowed,Returned',
        ]);

        \App\Models\BorrowedItem::create([
            'borrower_name' => $request->borrower_name,
            'asset_id' => $request->asset_id,
            'laboratory_id' => $request->laboratory_id,
            'date_borrowed' => $request->date_borrowed,
            'return_date' => $request->return_date,
        ]);
        Log::create(['description' => "Borrower '{$request->borrower}' borrowed asset ID {$request->asset_id}."]);

        return redirect()->route('borrowed_items.index')->with('success', 'Borrowed item added successfully.');
    }

    // Show the form for editing an existing borrowed item
    public function edit(BorrowedItems $borrowedItem)
    {
        $assets = Asset::all();
        $laboratories = Laboratory::all();
        return view('borrowed_items.edit', compact('borrowedItem', 'assets', 'laboratories'));
    }

    // Update the borrowed item in the database
    public function update(Request $request, BorrowedItems $borrowedItem)
    {
        $request->validate([
            'borrower_name' => 'required|string|max:255',
            'asset_id' => 'required|exists:assets,id',
            'laboratory_id' => 'required|exists:laboratories,id',
            'date_borrowed' => 'required|date',
            'due_date' => 'required|date|after:date_borrowed',
            'status' => 'required|in:pending,returned'
        ]);

        $borrowedItem->update($request->all());
        Log::create(['description' => "Borrowed item for '{$borrowedItem->borrower}' updated."]);
        return redirect()->route('borrowed_items.index')->with('success', 'Borrowed item updated.');
    }

    // Delete a borrowed item from the database
    public function destroy(BorrowedItems $borrowedItem)
    {
        $borrower = $borrowedItem->borrower;
        $borrowedItem->delete();
        Log::create(['description' => "Borrowed item for '{$borrower}' deleted."]);
        return redirect()->route('borrowed_items.index')->with('success', 'Borrowed item deleted.');
    }
}
