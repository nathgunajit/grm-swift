<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cpiu;
use App\Models\District;
use Illuminate\Http\Request;

class CpiuController extends Controller
{
    public function index()
    {
        $cpius = Cpiu::with('districts')->withCount('beels')->orderBy('name')->get();
        $districts = District::orderBy('name')->get();

        return view('admin.masters.cpius', compact('cpius', 'districts'));
    }

    public function store(Request $request)
    {
        $cpiu = Cpiu::create($this->validated($request) + ['is_active' => true]);
        $this->syncDistricts($request, $cpiu);

        return back()->with('success', 'CPIU added.');
    }

    public function update(Request $request, Cpiu $cpiu)
    {
        $cpiu->update($this->validated($request) + ['is_active' => $request->boolean('is_active')]);
        $this->syncDistricts($request, $cpiu);

        return back()->with('success', 'CPIU updated.');
    }

    public function destroy(Cpiu $cpiu)
    {
        // Release its districts, then delete.
        District::where('cpiu_id', $cpiu->id)->update(['cpiu_id' => null]);
        $cpiu->delete();

        return back()->with('success', 'CPIU deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:20'],
            'district_ids' => ['nullable', 'array'],
            'district_ids.*' => ['exists:districts,id'],
        ]);
    }

    /**
     * Assign the checked districts to this CPIU and release any it previously
     * owned that are no longer checked. A district taken by another CPIU is
     * left untouched (the UI disables those).
     */
    private function syncDistricts(Request $request, Cpiu $cpiu): void
    {
        $checked = collect($request->input('district_ids', []))->map('intval');

        // Release districts previously on this CPIU that were unchecked.
        District::where('cpiu_id', $cpiu->id)->whereNotIn('id', $checked)->update(['cpiu_id' => null]);

        // Assign checked districts that are free or already ours.
        District::whereIn('id', $checked)
            ->where(fn ($q) => $q->whereNull('cpiu_id')->orWhere('cpiu_id', $cpiu->id))
            ->update(['cpiu_id' => $cpiu->id]);
    }
}
