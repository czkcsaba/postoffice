<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\County;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::with('county');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('city', 'like', '%' . $search . '%')
                  ->orWhere('zip_code', 'like', '%' . $search . '%')
                  ->orWhere('population', 'like', '%' . $search . '%')
                  ->orWhereHas('county', function ($countyQuery) use ($search) {
                      $countyQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('county')) {
            $query->where('id_county', $request->county);
        }

        $perPage = $request->get('per_page', 50);

        $allowedPerPage = [25, 50, 100, 200];

        if (!in_array((int)$perPage, $allowedPerPage)) {
            $perPage = 50;
        }

        $cities = $query
            ->orderBy('city')
            ->orderBy('zip_code')
            ->paginate($perPage)
            ->withQueryString();

        $counties = County::orderBy('name')->get();

        return view('cities.index', compact('cities', 'counties'));
    }

    public function create()
    {
        $counties = County::orderBy('name')->get();

        return view('cities.create', compact('counties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'zip_code' => 'required|integer|min:1000|max:9999',
            'city' => 'required|string|max:50',
            'id_county' => 'required|exists:counties,id',
            'population' => 'required|integer|min:0',
        ]);

        City::create($validated);

        $this->updateCountyPopulation($validated['id_county']);

        return redirect()
            ->route('cities.index')
            ->with('success', 'A település sikeresen létrehozva.');
    }

    public function edit(City $city)
    {
        $city->load('county');

        $counties = County::orderBy('name')->get();

        return view('cities.edit', compact('city', 'counties'));
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'zip_code' => 'required|integer|min:1000|max:9999',
            'city' => 'required|string|max:50',
            'id_county' => 'required|exists:counties,id',
            'population' => 'required|integer|min:0',
        ]);

        $oldCountyId = $city->id_county;

        $city->update($validated);

        $this->updateCountyPopulation($oldCountyId);
        $this->updateCountyPopulation($validated['id_county']);

        return redirect()
            ->route('cities.index')
            ->with('success', 'A település sikeresen módosítva.');
    }

    public function destroy(City $city)
    {
        $countyId = $city->id_county;

        $city->delete();

        $this->updateCountyPopulation($countyId);

        return redirect()
            ->route('cities.index')
            ->with('success', 'A település sikeresen törölve.');
    }

    private function updateCountyPopulation($countyId)
    {
        $county = County::find($countyId);

        if (!$county) {
            return;
        }

        $county->population = City::where('id_county', $countyId)
            ->sum('population');

        $county->save();
    }
}