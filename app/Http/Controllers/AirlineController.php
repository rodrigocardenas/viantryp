<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AirlineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $airlines = Airline::all();
        return view('airlines.index', compact('airlines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('airlines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'carrier_code' => 'nullable|string|max:10|unique:airlines',
            'country' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $airline = Airline::create([
            'name' => $request->name,
            'carrier_code' => $request->carrier_code,
            'country' => $request->country,
            'logo_path' => $logoPath,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $airline->id,
                'name' => $airline->name,
                'country' => $airline->country
            ]);
        }

        return redirect()->route('airlines.index')->with('success', 'Airline created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $airline = Airline::findOrFail($id);
        return view('airlines.show', compact('airline'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $airline = Airline::findOrFail($id);
        return view('airlines.edit', compact('airline'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $airline = Airline::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'carrier_code' => 'nullable|string|max:10|unique:airlines,carrier_code,' . $id,
            'country' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $logoPath = $airline->logo_path;
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $airline->update([
            'name' => $request->name,
            'carrier_code' => $request->carrier_code,
            'country' => $request->country,
            'logo_path' => $logoPath,
        ]);

        return redirect()->route('airlines.index')->with('success', 'Airline updated successfully.');
    }

    /**
     * API endpoint for Select2 to fetch airlines.
     */
    public function apiIndex(Request $request)
    {
        $search = $request->get('q', '');
        $airlines = Airline::where('name', 'like', '%' . $search . '%')
            ->select('id', 'name as text', 'country')
            ->get();

        return response()->json($airlines);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $airline = Airline::findOrFail($id);

        // Delete logo if exists
        if ($airline->logo_path) {
            Storage::disk('public')->delete($airline->logo_path);
        }

        $airline->delete();

        return redirect()->route('airlines.index')->with('success', 'Airline deleted successfully.');
    }
}
