<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beel;
use App\Models\Block;
use App\Models\Cpiu;
use App\Models\District;
use Illuminate\Http\Request;

class BeelController extends Controller
{
    public function index()
    {
        $beels = Beel::with(['district', 'cpiu'])->orderBy('name')->paginate(20);
        $districts = District::orderBy('name')->get();
        $blocks = Block::orderBy('name')->get();
        $cpius = Cpiu::orderBy('name')->get();

        return view('admin.masters.beels', compact('beels', 'districts', 'blocks', 'cpius'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Beel::create($data + ['is_active' => true]);

        return back()->with('success', 'Beel added.');
    }

    public function update(Request $request, Beel $beel)
    {
        $beel->update($this->validateData($request));

        return back()->with('success', 'Beel updated.');
    }

    public function destroy(Beel $beel)
    {
        $beel->delete();

        return back()->with('success', 'Beel deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'block_id' => ['nullable', 'exists:blocks,id'],
            'cpiu_id' => ['nullable', 'exists:cpius,id'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);
    }
}
