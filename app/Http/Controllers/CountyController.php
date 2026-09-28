<?php

namespace App\Http\Controllers;

use App\Models\County;
use Illuminate\Http\Request;

class CountyController extends Controller
{
    public function index()
    {
        $counties = County::withCount('cities')
            ->orderBy('name')
            ->get();

        return view('counties.index', compact('counties'));
    }

    public function create()
    {
        return view('counties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:counties,name',
            'badge' => 'required|url|max:2048',
        ]);

        County::create([
            'name' => $validated['name'],
            'population' => 0,
            'badge' => $validated['badge'],
        ]);

        return redirect()
            ->route('counties.index')
            ->with('success', 'A megye sikeresen létrehozva.');
    }

    public function edit(County $county)
    {
        return view('counties.edit', compact('county'));
    }

    public function update(Request $request, County $county)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:counties,name,' . $county->id,
            'badge' => 'required|url|max:2048',
        ]);

        // A population mezőt nem írjuk kézzel:
        // azt a hozzá tartozó városok lakossága adja.
        $county->update([
            'name' => $validated['name'],
            'badge' => $validated['badge'],
        ]);

        return redirect()
            ->route('counties.index')
            ->with('success', 'A megye sikeresen módosítva.');
    }

    public function destroy(County $county)
    {
        // Olyan megyét nem engedünk törölni, amelyhez még város tartozik.
        if ($county->cities()->exists()) {
            return redirect()
                ->route('counties.index')
                ->withErrors([
                    'county' => 'A megye nem törölhető, mert tartozik hozzá település.'
                ]);
        }

        $county->delete();

        return redirect()
            ->route('counties.index')
            ->with('success', 'A megye sikeresen törölve.');
    }
}
