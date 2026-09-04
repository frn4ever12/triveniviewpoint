<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\RoleDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(RoleDataTable $dataTable)
    {
        $view = request()->routeIs('superadmin.*') ? 'superadmin.role.index' : 'admin.role.index';
        return $dataTable->render($view);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($perm) {
            $parts = explode('.', $perm->name);

            return $parts[0] ?? 'other';
        });

        $view = request()->routeIs('superadmin.*') ? 'superadmin.role.create' : 'admin.role.create';
        return view($view, compact('permissions'));
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create(['name' => strtolower($validated['name']), 'guard_name' => 'web']);

            if (! empty($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            }

            DB::commit();

            $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.roles.index' : 'admin.roles.index';
            return redirect()->route($redirectRoute)
                ->with('success', 'Role "'.$role->name.'" created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Failed to create role: '.$e->getMessage());
        }
    }

    /**
     * Show the form for editing a role.
     */
    public function edit(Role $role)
    {
        // Prevent editing superadmin except by superadmin
        if ($role->name === 'superadmin' && ! auth()->user()->hasRole('superadmin')) {
            abort(403, 'Only superadmin can modify the superadmin role.');
        }

        $permissions = Permission::all()->groupBy(function ($perm) {
            $parts = explode('.', $perm->name);

            return $parts[0] ?? 'other';
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        $view = request()->routeIs('superadmin.*') ? 'superadmin.role.edit' : 'admin.role.edit';
        return view($view, compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role)
    {
        // Prevent modifying superadmin role name
        if ($role->name === 'superadmin') {
            $request->validate([
                'permissions' => 'nullable|array',
                'permissions.*' => 'string|exists:permissions,name',
            ]);

            // Only superadmin can update superadmin
            if (! auth()->user()->hasRole('superadmin')) {
                abort(403, 'Only superadmin can modify the superadmin role.');
            }

            DB::beginTransaction();
            try {
                if (! empty($request->permissions)) {
                    $role->syncPermissions($request->permissions);
                }
                DB::commit();

                $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.roles.index' : 'admin.roles.index';
                return redirect()->route($redirectRoute)
                    ->with('success', 'Permissions updated for "'.$role->name.'".');
            } catch (\Exception $e) {
                DB::rollBack();

                return back()->with('error', 'Failed to update role: '.$e->getMessage());
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        DB::beginTransaction();
        try {
            $role->update(['name' => strtolower($validated['name'])]);

            if (! empty($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            } else {
                $role->syncPermissions([]);
            }

            DB::commit();

            $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.roles.index' : 'admin.roles.index';
            return redirect()->route($redirectRoute)
                ->with('success', 'Role "'.$role->name.'" updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Failed to update role: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Role $role)
    {
        if (in_array($role->name, ['superadmin', 'admin'])) {
            return response()->json([
                'message' => 'Cannot delete the "'.$role->name.'" role.',
            ], 403);
        }

        DB::beginTransaction();
        try {
            $role->delete();
            DB::commit();

            if (request()->expectsJson()) {
                return response()->json(['message' => 'Role deleted successfully.']);
            }

            $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.roles.index' : 'admin.roles.index';
            return redirect()->route($redirectRoute)
                ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to delete role: '.$e->getMessage());
        }
    }
}
