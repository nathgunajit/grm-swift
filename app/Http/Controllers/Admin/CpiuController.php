<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cpiu;
use App\Models\Zone;
use Illuminate\Http\Request;

class CpiuController extends Controller
{
    public function index()
    {
        $cpius = Cpiu::with('zone')->withCount('beels')->orderBy('name')->paginate(20);
        $zones = Zone::orderBy('name')->get();

        return view('admin.masters.cpius', compact('cpius', 'zones'));
    }

    public function store(Request $request)
    {
        Cpiu::create($this->validated($request) + ['is_active' => true]);

        return back()->with('success', 'CPIU added.');
    }

    public function update(Request $request, Cpiu $cpiu)
    {
        $cpiu->update($this->validated($request) + ['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'CPIU updated.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:20'],
            'zone_id' => ['nullable', 'exists:zones,id'],
        ]);
    }

    public function destroy(Cpiu $cpiu)
    {
        $cpiu->delete();

        return back()->with('success', 'CPIU deleted.');
    }
}
