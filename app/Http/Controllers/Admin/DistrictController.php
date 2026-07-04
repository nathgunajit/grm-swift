<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::withCount('beels')->orderBy('name')->paginate(20);

        return view('admin.masters.districts', compact('districts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:20'],
        ]);
        District::create($data + ['is_active' => true]);

        return back()->with('success', 'District added.');
    }

    public function update(Request $request, District $district)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $district->update($data + ['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'District updated.');
    }

    public function destroy(District $district)
    {
        $district->delete();

        return back()->with('success', 'District deleted.');
    }
}
