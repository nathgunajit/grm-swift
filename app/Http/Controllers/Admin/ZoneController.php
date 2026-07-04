<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::withCount('cpius')->orderBy('name')->paginate(20);

        return view('admin.masters.zones', compact('zones'));
    }

    public function store(Request $request)
    {
        Zone::create($this->validated($request) + ['is_active' => true]);

        return back()->with('success', 'Zone added.');
    }

    public function update(Request $request, Zone $zone)
    {
        $zone->update($this->validated($request) + ['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'Zone updated.');
    }

    public function destroy(Zone $zone)
    {
        $zone->delete();

        return back()->with('success', 'Zone deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
