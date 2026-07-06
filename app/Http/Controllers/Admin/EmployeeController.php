<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Autocomplete source for committee member entry.
     * Returns matching employees (users) with their designation.
     */
    public function search(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $employees = User::query()
            ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w
                ->where('name', 'like', "%$q%")
                ->orWhere('empid', 'like', "%$q%")
                ->orWhere('designation', 'like', "%$q%")))
            ->orderBy('name')
            ->limit(10)
            ->get(['name', 'designation', 'empid'])
            ->map(fn ($u) => [
                'name' => $u->name,
                'designation' => $u->designation ?? '',
                'empid' => $u->empid ?? '',
            ]);

        return response()->json($employees);
    }
}
