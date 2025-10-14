<?php

namespace App\Http\Controllers\PLAuthentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));
    }

    // This method shows the create user form
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    // This method handles the form submission
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['required', 'array']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->roles);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'roles' => 'required|array',
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // Prepare the data for updating the user model
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // If a new password was provided, add it to the update data
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Update the user's core details
        $user->update($updateData);

        // Sync the user's roles
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
}
