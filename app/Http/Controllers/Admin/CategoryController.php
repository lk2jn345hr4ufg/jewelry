<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['businesses', 'cities'])->orderBy('name')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.form', [
            'category' => new Category(),
            'cities' => City::orderBy('name')->get(),
            'linked' => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $category = Category::create($data);
        $category->cities()->sync($request->input('cities', []));

        return redirect()->route('admin.categories.index')->with('ok', 'Category created.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', [
            'category' => $category,
            'cities' => City::orderBy('name')->get(),
            'linked' => $category->cities()->pluck('cities.id')->all(),
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $category->update($this->validated($request, $category->id));
        $category->cities()->sync($request->input('cities', []));

        return redirect()->route('admin.categories.index')->with('ok', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('ok', 'Category deleted.');
    }

    protected function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:150', 'unique:categories,slug' . ($id ? ",$id" : '')],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
