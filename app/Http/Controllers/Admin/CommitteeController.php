<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Committee;
use App\Models\Cpiu;
use App\Models\District;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    public function index()
    {
        $committees = Committee::with(['members', 'district', 'cpiu'])->orderBy('level')->get();
        $districts = District::orderBy('name')->get();
        $cpius = Cpiu::orderBy('name')->get();

        return view('admin.committees.index', compact('committees', 'districts', 'cpius'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'integer', 'between:1,3'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'cpiu_id' => ['nullable', 'exists:cpius,id'],
        ]);
        Committee::create($data + ['is_active' => true]);

        return back()->with('success', 'Committee created.');
    }

    public function update(Request $request, Committee $committee)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'integer', 'between:1,3'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'cpiu_id' => ['nullable', 'exists:cpius,id'],
        ]);
        $committee->update($data);

        return back()->with('success', 'Committee updated.');
    }

    public function destroy(Committee $committee)
    {
        $committee->delete();

        return back()->with('success', 'Committee deleted.');
    }

    public function addMember(Request $request, Committee $committee)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:chairperson,convenor,member,rapporteur'],
            'is_woman' => ['nullable', 'boolean'],
        ]);
        $committee->members()->create($data + ['is_woman' => $request->boolean('is_woman')]);

        return back()->with('success', 'Member added.');
    }

    public function removeMember(Committee $committee, $member)
    {
        $committee->members()->whereKey($member)->delete();

        return back()->with('success', 'Member removed.');
    }
}
