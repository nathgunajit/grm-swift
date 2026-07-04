<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserTypeController extends Controller
{
    public function index()
    {
        $types = UserType::withCount('users')->orderBy('name')->paginate(20);

        return view('admin.masters.user-types', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
        UserType::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name'], '_'),
            'description' => $data['description'] ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', 'User type added.');
    }

    public function update(Request $request, UserType $user_type)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
        $user_type->update($data + ['is_active' => $request->boolean('is_active')]);

        return back()->with('success', 'User type updated.');
    }

    public function destroy(UserType $user_type)
    {
        $user_type->delete();

        return back()->with('success', 'User type deleted.');
    }
}
