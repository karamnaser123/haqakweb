<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use Laratrust\Models\Role;
use Laratrust\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('admin.users.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'gender' => 'nullable|in:male,female',
            'age' => 'nullable|integer|min:1|max:120',
            'balance' => 'nullable|numeric|min:0',
            'role' => 'required|string|exists:roles,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'nullable|in:on,1,true',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['code'] = User::generateUniqueCode(0); // Will be updated after user creation
        $data['active'] = $request->input('active') === 'on' ? true : false;

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        $user = User::create($data);

        // Update user code with actual ID
        $user->update(['code' => User::generateUniqueCode($user->id)]);

        // Assign role
        $role = Role::where('name', $request->role)->first();
        if ($role) {
            $user->addRole($role);
        }

        return response()->json([
            'success' => __('User created successfully'),
        ]);
    }

    public function edit($id)
    {
        $user = User::with('roles')->find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'data' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:255|unique:users,phone,' . $id,
            'password' => 'nullable|string|min:8',
            'gender' => 'nullable|in:male,female',
            'age' => 'nullable|integer|min:1|max:120',
            'balance' => 'nullable|numeric|min:0',
            'role' => 'required|string|exists:roles,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'active' => 'nullable|in:on,1,true',
        ]);

        $data = $request->all();

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $data['active'] = $request->input('active') === 'on' ? true : false;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $data['image'] = $request->file('image')->store('users', 'public');
        }

        $user->update($data);

        // Update role
        $role = Role::where('name', $request->role)->first();
        if ($role) {
            $user->syncRoles([$role->id]);
        }

        return response()->json([
            'success' => __('User updated successfully'),
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Delete user image if exists
        if ($user->image) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();
        return response()->json([
            'success' => __('User deleted successfully'),
        ]);
    }

    public function status($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->active = !$user->active;
        $user->save();
        return response()->json([
            'success' => __('User status updated successfully'),
        ]);
    }

    public function permissions($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $permissions = Permission::all();
        $userPermissions = $user->permissions->pluck('id')->toArray();

        return response()->json([
            'permissions' => $permissions,
            'userPermissions' => $userPermissions,
        ]);
    }

    public function updatePermissions(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // If permissions is null or empty array, remove all permissions
        $permissions = $request->permissions ?? [];
        $user->syncPermissions($permissions);

        return response()->json([
            'success' => __('User permissions updated successfully'),
        ]);
    }
}
