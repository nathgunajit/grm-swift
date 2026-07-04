<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beel;
use App\Models\Cpiu;
use App\Models\District;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['userType', 'district', 'cpiu', 'beel'])->orderBy('name')->paginate(20);

        return view('admin.users.index', compact('users') + $this->refData());
    }

    public function create()
    {
        return view('admin.users.create', $this->refData());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'empid' => ['nullable', 'string', 'max:50', 'unique:users,empid'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'mobile' => ['nullable', 'regex:/^[6-9]\d{9}$/', 'unique:users,mobile'],
            'designation' => ['nullable', 'string', 'max:255'],
            'office_address' => ['nullable', 'string', 'max:1000'],
            'user_type_id' => ['required', 'exists:user_types,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'cpiu_id' => ['nullable', 'exists:cpius,id'],
            'beel_id' => ['nullable', 'exists:beels,id'],
            'password' => ['required', 'string', 'min:8'],
            'assign_date' => ['nullable', 'date'],
        ]);

        $user = User::create([
            ...collect($data)->except(['password', 'assign_date'])->toArray(),
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        $user->assignments()->create([
            'user_type_id' => $user->user_type_id,
            'cpiu_id' => $user->cpiu_id,
            'district_id' => $user->district_id,
            'beel_id' => $user->beel_id,
            'assign_date' => $data['assign_date'] ?? now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User registered and assigned.');
    }

    public function edit(User $user)
    {
        $user->load('assignments.userType');

        return view('admin.users.edit', compact('user') + $this->refData());
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'empid' => ['nullable', 'string', 'max:50', Rule::unique('users', 'empid')->ignore($user->id)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'mobile' => ['nullable', 'regex:/^[6-9]\d{9}$/', Rule::unique('users', 'mobile')->ignore($user->id)],
            'designation' => ['nullable', 'string', 'max:255'],
            'office_address' => ['nullable', 'string', 'max:1000'],
            'user_type_id' => ['required', 'exists:user_types,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'cpiu_id' => ['nullable', 'exists:cpius,id'],
            'beel_id' => ['nullable', 'exists:beels,id'],
            'password' => ['nullable', 'string', 'min:8'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $update = collect($data)->except('password')->toArray();
        $update['is_active'] = $request->boolean('is_active');
        if (! empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }
        $user->update($update);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function assign(Request $request, User $user)
    {
        $data = $request->validate([
            'user_type_id' => ['required', 'exists:user_types,id'],
            'cpiu_id' => ['nullable', 'exists:cpius,id'],
            'district_id' => ['nullable', 'exists:districts,id'],
            'beel_id' => ['nullable', 'exists:beels,id'],
            'assign_date' => ['required', 'date'],
            'relieving_date' => ['nullable', 'date', 'after_or_equal:assign_date'],
        ]);

        $user->assignments()->create($data);
        // Reflect current assignment on the user record.
        $user->update([
            'user_type_id' => $data['user_type_id'],
            'cpiu_id' => $data['cpiu_id'] ?? null,
            'district_id' => $data['district_id'] ?? null,
            'beel_id' => $data['beel_id'] ?? null,
        ]);

        return back()->with('success', 'Assignment recorded.');
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'You cannot delete your own account.');
        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    private function refData(): array
    {
        return [
            'userTypes' => UserType::orderBy('name')->get(),
            'districts' => District::orderBy('name')->get(),
            'cpius' => Cpiu::orderBy('name')->get(),
            'beels' => Beel::orderBy('name')->get(),
        ];
    }
}
