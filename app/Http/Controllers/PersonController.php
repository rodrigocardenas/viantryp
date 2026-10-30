<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $persons = Person::where('user_id', Auth::id())->orderBy('name')->get();
        return view('persons.index', compact('persons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('persons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('persons', 'email')->where(fn ($query) => $query->where('user_id', Auth::id())),
            ],
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:client,agent',
        ]);

        $validated['user_id'] = Auth::id();
        Person::create($validated);

        return redirect()->route('persons.index')->with('success', 'Person created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $person = Person::where('user_id', Auth::id())->findOrFail($id);
        return view('persons.show', compact('person'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $person = Person::where('user_id', Auth::id())->findOrFail($id);
        return view('persons.edit', compact('person'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $person = Person::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('persons', 'email')
                    ->where(fn ($query) => $query->where('user_id', Auth::id()))
                    ->ignore($person->id),
            ],
            'phone' => 'nullable|string|max:20',
            'type' => 'required|in:client,agent',
        ]);

        $person->update($validated);

        return redirect()->route('persons.index')->with('success', 'Person updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $person = Person::where('user_id', Auth::id())->findOrFail($id);
        $person->delete();

        return redirect()->route('persons.index')->with('success', 'Person deleted successfully.');
    }

    /**
     * Get agents for select options
     */
    public function getAgents()
    {
        $agents = Person::where('user_id', Auth::id())
            ->where('type', 'agent')
            ->get(['id', 'name']);
        return response()->json($agents);
    }
}
