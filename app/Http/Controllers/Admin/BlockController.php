<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\District;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function index()
    {
        $blocks = Block::with('district')->orderBy('name')->paginate(20);
        $districts = District::orderBy('name')->get();

        return view('admin.masters.blocks', compact('blocks', 'districts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'district_id' => ['required', 'exists:districts,id'],
        ]);
        Block::create($data + ['is_active' => true]);

        return back()->with('success', 'Block added.');
    }

    public function update(Request $request, Block $block)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'district_id' => ['required', 'exists:districts,id'],
        ]);
        $block->update($data);

        return back()->with('success', 'Block updated.');
    }

    public function destroy(Block $block)
    {
        $block->delete();

        return back()->with('success', 'Block deleted.');
    }
}
