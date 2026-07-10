<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::withCount(['businesses', 'categories'])->orderBy('name')->paginate(20);
        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('admin.cities.form', [
            'city' => new City(),
            'categories' => Category::orderBy('name')->get(),
            'linked' => [],
        ]);
    }

    public function store(Request $request)
    {
        $city = City::create($this->validated($request));
        $city->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.cities.index')->with('ok', 'City created.');
    }

    public function edit(City $city)
    {
        return view('admin.cities.form', [
            'city' => $city,
            'categories' => Category::orderBy('name')->get(),
            'linked' => $city->categories()->pluck('categories.id')->all(),
        ]);
    }

    public function update(Request $request, City $city)
    {
        $city->update($this->validated($request, $city->id));
        $city->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.cities.index')->with('ok', 'City updated.');
    }

    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->route('admin.cities.index')->with('ok', 'City deleted.');
    }

    protected function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:150', 'unique:cities,slug' . ($id ? ",$id" : '')],
            'state' => ['nullable', 'string', 'max:100'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'population' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['population'] = (int) ($data['population'] ?? 0);

        return $data;
    }
}
