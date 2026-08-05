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
        $data['description'] = $this->sanitizeHtml($data['description'] ?? null);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    /** Allow only a small set of safe formatting tags from the rich-text editor. */
    protected function sanitizeHtml(?string $html): ?string
    {
        if (blank($html)) {
            return null;
        }

        // Strip everything except a basic allowlist.
        $clean = strip_tags($html, '<p><br><strong><em><u><b><i><ul><ol><li><a>');

        // Remove any attributes except href on <a>, and force safe links.
        $clean = preg_replace_callback('/<a\b[^>]*>/i', function ($m) {
            if (preg_match('/href\s*=\s*"([^"]*)"/i', $m[0], $h)) {
                $url = $h[1];
                if (preg_match('~^(https?:)?//~i', $url) || str_starts_with($url, '/')) {
                    return '<a href="'.htmlspecialchars($url, ENT_QUOTES).'" rel="nofollow noopener" target="_blank">';
                }
            }
            return '<a>';
        }, $clean);

        // Drop attributes from all other allowed tags.
        $clean = preg_replace('/<(p|br|strong|em|u|b|i|ul|ol|li)\b[^>]*>/i', '<$1>', $clean);

        return trim($clean) === '' ? null : $clean;
    }

}