<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\StaffDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StaffRequest;
use App\Models\User;
use App\Traits\FileUploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    use FileUploadTrait;

    public function index(StaffDataTable $dataTable)
    {
        return $dataTable->render('admin.staff.index');
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.staff.create', compact('roles'));
    }

    public function store(StaffRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
                $data['login_enabled'] = true;
            } else {
                $data['password'] = null;
                $data['login_enabled'] = false;
                unset($data['password_confirmation']);
            }

            $staff = User::create($data);

            if ($request->hasFile('profile_image')) {
                $this->uploadToMediaLibrary($staff, $request->file('profile_image'), 'profile_image');
            }

            $staff->syncRoles([$request->input('role')]);

            DB::commit();

            return redirect()->route('admin.staff.index')->with('success', 'Staff created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create staff. Please try again.');
        }
    }

    public function show(User $staff)
    {
        $staff->load('media');
        return view('admin.staff.show', compact('staff'));
    }

    public function edit(User $staff)
    {
        $roles = Role::all();
        $staff->load('media');
        return view('admin.staff.edit', compact('staff', 'roles'));
    }

    public function update(StaffRequest $request, User $staff)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
            $data['login_enabled'] = true;
        } else {
            unset($data['password']);
        }

        DB::beginTransaction();

        try {
            $staff->fill($data)->save();

            if ($request->hasFile('profile_image')) {
                $this->deleteFromMediaLibrary($staff, 'profile_image');
                $this->uploadToMediaLibrary($staff, $request->file('profile_image'), 'profile_image');
            }

            $staff->syncRoles([$request->input('role')]);

            DB::commit();

            return redirect()->route('admin.staff.index')->with('success', 'Staff updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update staff. Please try again.');
        }
    }

    public function destroy(User $staff)
    {
        DB::beginTransaction();
        try {
            $staff->clearMediaCollection('profile_image');
            $staff->delete();
            DB::commit();
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Staff deleted successfully.']);
            }
            return redirect()->route('admin.staff.index')
                ->with('success', 'Staff deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Failed to delete staff.', 'error' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to delete staff. Please try again.');
        }
    }

    public function toggleLogin(User $staff): JsonResponse
    {
        $staff->login_enabled = !$staff->login_enabled;
        if ($staff->login_enabled && !$staff->password) {
            return response()->json([
                'message' => 'Set a password for this staff member before enabling login.',
                'success' => false,
            ], 422);
        }
        $staff->save();

        return response()->json([
            'message' => $staff->login_enabled ? 'Login enabled for ' . $staff->name : 'Login disabled for ' . $staff->name,
            'success' => true,
            'login_enabled' => $staff->login_enabled,
        ]);
    }
}
