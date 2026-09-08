<?php

namespace App\Http\Controllers\Admin;

use HasinHayder\TyroDashboard\Http\Controllers\RoleController as BaseRoleController;
use HasinHayder\Tyro\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends BaseRoleController
{
    /**
     * Display a listing of roles.
     * Overriding to ensure we get the is_active column.
     */
    public function index(Request $request)
    {
        $perPage = config('tyro-dashboard.pagination.roles', 15);

        $query = Role::withCount(['users', 'privileges']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $roles = $query->latest()->paginate($perPage)->withQueryString();
        $protectedRoles = config('tyro-dashboard.protected.roles', []);

        return view('tyro-dashboard::roles.index', $this->getViewData([
            'roles' => $roles,
            'protectedRoles' => $protectedRoles,
            'filters' => $request->only(['search']),
        ]));
    }

    /**
     * Toggle role status (Active/Inactive)
     */
    public function toggleStatus($id)
    {
        $role = Role::findOrFail($id);

        // Don't toggle protected roles if needed, or just let it be.
        // Usually, we want even protected roles to be toggleable unless they are super-admin.

        $role->is_active = !$role->is_active;

        // Since we didn't add is_active to $fillable in the vendor model, and we can't easily,
        // we'll use save() which works regardless of fillable if the property is set.
        $role->save();

        return redirect()
            ->back()
            ->with('success', 'Role status updated successfully.');
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug'],
            'privileges' => ['array'],
            'privileges.*' => ['exists:privileges,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $role = new Role();
        $role->name = $validated['name'];
        $role->slug = $validated['slug'] ?: \Illuminate\Support\Str::slug($validated['name']);
        $role->is_active = $request->has('is_active');
        $role->save();

        if (!empty($validated['privileges'])) {
            $role->privileges()->sync($validated['privileges']);
        }

        return redirect()
            ->route('tyro-dashboard.roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit($id)
    {
        $role = Role::with('privileges')->findOrFail($id);
        $privileges = \HasinHayder\Tyro\Models\Privilege::all();
        $protectedRoles = config('tyro-dashboard.protected.roles', []);

        return view('tyro-dashboard::roles.edit', $this->getViewData([
            'role' => $role,
            'privileges' => $privileges,
            'protectedRoles' => $protectedRoles,
        ]));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:roles,slug,' . $role->id],
            'privileges' => ['array'],
            'privileges.*' => ['exists:privileges,id'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $role->name = $validated['name'];
        $role->slug = $validated['slug'] ?: \Illuminate\Support\Str::slug($validated['name']);
        $role->is_active = $request->has('is_active');
        $role->save();

        if (isset($validated['privileges'])) {
            $role->privileges()->sync($validated['privileges']);
        }

        return redirect()
            ->route('tyro-dashboard.roles.index')
            ->with('success', 'Role updated successfully.');
    }
}
