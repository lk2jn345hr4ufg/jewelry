<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $businesses = Business::with(['city', 'category'])
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->q . '%'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.businesses.index', compact('businesses'));
    }

    public function create()
    {
        return $this->form(new Business());
    }

    public function store(Request $request)
    {
        Business::create($this->validated($request));
        return redirect()->route('admin.businesses.index')->with('ok', 'Profile created.');
    }

    public function edit(Business $business)
    {
        return $this->form($business);
    }

    public function update(Request $request, Business $business)
    {
        $business->update($this->validated($request, $business->id));
        return redirect()->route('admin.businesses.index')->with('ok', 'Profile updated.');
    }

    public function destroy(Business $business)
    {
        $business->delete();
        return redirect()->route('admin.businesses.index')->with('ok', 'Profile deleted.');
    }

    protected function form(Business $business)
    {
        return view('admin.businesses.form', [
            'business' => $business,
            'cities' => City::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
            'days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        ]);
    }

    protected function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', 'unique:businesses,slug' . ($id ? ",$id" : '')],
            'category_id' => ['required', 'exists:categories,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'about' => ['nullable', 'string', 'max:5000'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'phone_alt' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'url', 'max:255'],
            'email' => ['nullable', 'email', 'max:150'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'hours' => ['nullable', 'array'],
            'hours.*' => ['nullable', 'string', 'max:50'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['hours'] = array_filter($request->input('hours', []), fn ($v) => filled($v));

        return $data;
    }
}
