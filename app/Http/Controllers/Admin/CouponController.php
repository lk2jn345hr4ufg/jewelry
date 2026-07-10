<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with('business')->latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return $this->form(new Coupon());
    }

    public function store(Request $request)
    {
        Coupon::create($this->validated($request));
        return redirect()->route('admin.coupons.index')->with('ok', 'Deal created.');
    }

    public function edit(Coupon $coupon)
    {
        return $this->form($coupon);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $coupon->update($this->validated($request));
        return redirect()->route('admin.coupons.index')->with('ok', 'Deal updated.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('ok', 'Deal deleted.');
    }

    protected function form(Coupon $coupon)
    {
        return view('admin.coupons.form', [
            'coupon' => $coupon,
            'businesses' => Business::orderBy('name')->get(),
        ]);
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'business_id' => ['required', 'exists:businesses,id'],
            'title' => ['required', 'string', 'max:150'],
            'code' => ['nullable', 'string', 'max:60'],
            'discount' => ['nullable', 'string', 'max:60'],
            'description' => ['nullable', 'string', 'max:2000'],
            'expires_at' => ['nullable', 'date'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
