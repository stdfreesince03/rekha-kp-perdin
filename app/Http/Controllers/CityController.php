<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');

        $citiesQuery = City::orderBy('name');

        if ($filter === 'domestic') {
            $citiesQuery->domestic();
        } elseif ($filter === 'foreign') {
            $citiesQuery->foreign();
        } elseif ($filter === 'active') {
            $citiesQuery->active();
        }

        $cities = $citiesQuery->get();

        return view('cities.index', compact('cities', 'filter'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'island' => 'nullable|string|max:255',
            'is_foreign' => 'required|boolean',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        City::create($validated);

        return redirect()->route('cities.index')->with('success', 'City added successfully!');
    }

    public function edit(City $city)
    {
        return view('cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'island' => 'nullable|string|max:255',
            'is_foreign' => 'required|boolean',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        $city->update($validated);

        return redirect()->route('cities.index')->with('success', 'City updated successfully!');
    }

    public function destroy(City $city)
    {
        $city->delete();

        return redirect()->route('cities.index')->with('success', 'City deleted successfully!');
    }
}
