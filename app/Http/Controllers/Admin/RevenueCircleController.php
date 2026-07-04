<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\RevenueCircle;
use Illuminate\Http\Request;

class RevenueCircleController extends Controller
{
    public function index()
    {
        $circles = RevenueCircle::with('district')->orderBy('name')->paginate(20);
        $districts = District::orderBy('name')->get();

        return view('admin.masters.revenue-circles', compact('circles', 'districts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'district_id' => ['required', 'exists:districts,id'],
        ]);
        RevenueCircle::create($data + ['is_active' => true]);

        return back()->with('success', 'Revenue circle added.');
    }

    public function update(Request $request, RevenueCircle $revenue_circle)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'district_id' => ['required', 'exists:districts,id'],
        ]);
        $revenue_circle->update($data);

        return back()->with('success', 'Revenue circle updated.');
    }

    public function destroy(RevenueCircle $revenue_circle)
    {
        $revenue_circle->delete();

        return back()->with('success', 'Revenue circle deleted.');
    }
}
