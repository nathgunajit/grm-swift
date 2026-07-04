<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cpiu;
use Illuminate\Http\Request;

class CpiuController extends Controller
{
    public function index()
    {
        $cpius = Cpiu::withCount('beels')->orderBy('name')->paginate(20);

        return view('admin.masters.cpius', compact('cpius'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:20'],
        ]);
        Cpiu::create($data + ['is_active' => true]);

        return back()->with('success', 'CPIU added.');
    }

    public function update(Request $request, Cpiu $cpiu)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:20'],
        ]);
        $cpiu->update($data + ['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'CPIU updated.');
    }

    public function destroy(Cpiu $cpiu)
    {
        $cpiu->delete();

        return back()->with('success', 'CPIU deleted.');
    }
}
