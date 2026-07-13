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
        $status = $request->query('status', 'all');

        $sort = $request->query('sort', 'latest');
        $dir = $request->query('dir', 'asc') === 'desc' ? 'desc' : 'asc';

        $minCity = max(0, (int) $request->query('min_city', 0));

        $query = Business::with(['city', 'category'])
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%' . $request->q . '%'))
            ->when($status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($status === 'hidden', fn ($q) => $q->where('is_active', false))
            ->when($minCity > 1, fn ($q) => $q->whereIn('city_id', function ($sub) use ($minCity) {
                $sub->from('businesses')
                    ->select('city_id')
                    ->groupBy('city_id')
                    ->havingRaw('COUNT(*) >= ?', [$minCity]);
            }));

        match ($sort) {
            'name' => $query->orderBy('name', $dir),
            'city' => $query->leftJoin('cities', 'businesses.city_id', '=', 'cities.id')
                ->orderBy('cities.name', $dir)
                ->orderBy('businesses.name')
                ->select('businesses.*'),
            default => $query->latest(),
        };

        $businesses = $query->paginate(20)->withQueryString();

        return view('admin.businesses.index', [
            'businesses' => $businesses,
            'status' => $status,
            'sort' => $sort,
            'dir' => $dir,
            'minCity' => $minCity,
            'counts' => [
                'all' => Business::count(),
                'active' => Business::where('is_active', true)->count(),
                'hidden' => Business::where('is_active', false)->count(),
            ],
        ]);
    }

    /** Bulk activate / disable from the list. */
    public function bulk(Request $request)
    {
        $data = $request->validate([
            'action' => ['required', 'in:activate,disable'],
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ]);

        $count = Business::whereIn('id', $data['ids'])
            ->update(['is_active' => $data['action'] === 'activate']);

        return back()->with('ok', $data['action'] === 'activate'
            ? "{$count} businesses activated and visible on the site."
            : "{$count} businesses disabled and hidden from the site.");
    }

    /** Quick active/disabled switch from the list. */
    public function toggle(Business $business)
    {
        $business->update(['is_active' => ! $business->is_active]);

        return back()->with('ok', $business->is_active
            ? "\"{$business->name}\" is now active and visible on the site."
            : "\"{$business->name}\" is now disabled and hidden from the site.");
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
