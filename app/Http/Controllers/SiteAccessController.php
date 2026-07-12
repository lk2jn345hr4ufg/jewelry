<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteAccessController extends Controller
{
    public function unlock(Request $request)
    {
        $request->validate(['password' => ['required', 'string']]);

        if (hash_equals((string) config('app.site_password'), $request->input('password'))) {
            $request->session()->put('site_unlocked', true);

            return redirect('/');
        }

        return back()->withErrors(['password' => 'Wrong password, try again.']);
    }
}
