<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class userController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('admin.user.index');
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'superadmin')->pluck('name');
        return view('admin.user.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);

            if (!empty($data['role'])) {
                $user->syncRoles([$data['role']]);
            }

            DB::commit();
            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function show(User $user)
    {
        return view('admin.user.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load('roles');
        $roles = Role::where('name', '!=', 'superadmin')->pluck('name');
        return view('admin.user.edit', compact('user', 'roles'));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        DB::beginTransaction();
        try {
            $user->update($data);

            if (!empty($data['role'])) {
                $user->syncRoles([$data['role']]);
            }

            DB::commit();
            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update user. Please try again.');
        }
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            if (request()->expectsJson()) {
                return response()->json(['message' => 'User deleted successfully.']);
            }
            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Failed to delete user.', 'error' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to delete user.');
        }
    }
}
