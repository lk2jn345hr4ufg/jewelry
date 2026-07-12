<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RedirectRule;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function index()
    {
        return view('admin.redirects.index', [
            'redirects' => RedirectRule::orderByDesc('id')->paginate(30),
        ]);
    }

    public function create()
    {
        return view('admin.redirects.form', ['redirect' => new RedirectRule(['status_code' => 301, 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        RedirectRule::create($this->validated($request));

        return redirect()->route('admin.redirects.index')->with('ok', 'Redirect created.');
    }

    public function edit(RedirectRule $redirect)
    {
        return view('admin.redirects.form', ['redirect' => $redirect]);
    }

    public function update(Request $request, RedirectRule $redirect)
    {
        $redirect->update($this->validated($request));

        return redirect()->route('admin.redirects.index')->with('ok', 'Redirect updated.');
    }

    public function destroy(RedirectRule $redirect)
    {
        $redirect->delete();

        return back()->with('ok', 'Redirect deleted.');
    }

    /**
     * Bulk upload: one rule per line —
     *   /old-path;/new-path
     *   /old-path;/new-path;302
     *   ^/blog/(.*)$;/articles/$1;301;regex
     */
    public function bulk(Request $request)
    {
        $request->validate(['rules' => ['required', 'string', 'max:200000']]);

        $created = 0;
        $errors = [];

        foreach (preg_split('/\r\n|\r|\n/', $request->input('rules')) as $lineNo => $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            $parts = array_map('trim', explode(';', $line));
            if (count($parts) < 2) {
                $errors[] = 'Line '.($lineNo + 1).': needs at least "from;to"';
                continue;
            }

            $isRegex = in_array(strtolower($parts[3] ?? ''), ['regex', 'regexp', '1', 'true']);
            $code = (int) ($parts[2] ?? 301);
            $code = in_array($code, [301, 302, 307, 308]) ? $code : 301;

            if ($isRegex && @preg_match('#^'.str_replace('#', '\#', $parts[0]).'$#i', '') === false) {
                $errors[] = 'Line '.($lineNo + 1).': invalid regular expression';
                continue;
            }

            RedirectRule::create([
                'from_pattern' => $parts[0],
                'to_pattern' => $parts[1],
                'status_code' => $code,
                'is_regex' => $isRegex,
                'is_active' => true,
            ]);
            $created++;
        }

        $message = "{$created} redirects imported.";
        if ($errors) {
            $message .= ' Skipped: '.implode(' | ', array_slice($errors, 0, 5));
        }

        return back()->with('ok', $message);
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'from_pattern' => ['required', 'string', 'max:500'],
            'to_pattern' => ['required', 'string', 'max:500'],
            'status_code' => ['required', 'in:301,302,307,308'],
        ]);

        $data['is_regex'] = $request->boolean('is_regex');
        $data['is_active'] = $request->boolean('is_active');

        if ($data['is_regex'] && @preg_match('#^'.str_replace('#', '\#', $data['from_pattern']).'$#i', '') === false) {
            abort(back()->withErrors(['from_pattern' => 'Invalid regular expression.'])->withInput());
        }

        return $data;
    }
}
