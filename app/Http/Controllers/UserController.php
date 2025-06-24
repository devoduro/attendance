<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter by role
        if ($request->has('role') && $request->role) {
            // Use whereHas to filter by role using Spatie's relationship
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('id', $request->role);
            });
        }
        
        // Filter by search term
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->with('roles')->paginate(15);
        $roles = Role::all();
        
        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'status' => 'nullable|string|in:active,inactive',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);
        
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'status' => $request->status ?? 'active',
        ];
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $userData['profile_photo'] = $path;
        }
        
        $user = User::create($userData);
        
        $user->syncRoles($request->roles);
        
        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone_number' => 'nullable|string|max:20',
            'status' => 'nullable|string|in:active,inactive',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);
        
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'status' => $request->status ?? 'active',
        ];
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $userData['profile_photo'] = $path;
        }
        
        $user->update($userData);
        
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }
        
        $user->syncRoles($request->roles);
        
        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
    
    /**
     * Search users by name or email.
     */
    public function search(Request $request)
    {
        $term = $request->term;
        
        $users = User::where('name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->with('roles')
            ->limit(10)
            ->get();
            
        return response()->json($users);
    }
    
    /**
     * Filter users by role.
     */
    public function filterByRole(Request $request)
    {
        $role = $request->role;
        
        $users = User::whereHas('roles', function($q) use ($role) {
                $q->where('id', $role);
            })
            ->with('roles')
            ->paginate(15);
            
        $roles = Role::all();
        
        return view('users.index', compact('users', 'roles'));
    }
    
    /**
     * Handle bulk actions on users
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'bulk_action' => 'required|in:activate,deactivate,delete',
            'selected_users' => 'required|array',
            'selected_users.*' => 'exists:users,id'
        ]);
        
        $action = $request->input('bulk_action');
        $selectedUsers = $request->input('selected_users');
        $currentUserId = auth()->id();
        
        // Remove current user from selection if present
        $selectedUsers = array_filter($selectedUsers, function($userId) use ($currentUserId) {
            return $userId != $currentUserId;
        });
        
        if (empty($selectedUsers)) {
            return redirect()->route('users.index')
                ->with('error', 'No valid users selected for the action.');
        }
        
        try {
            DB::beginTransaction();
            
            switch ($action) {
                case 'activate':
                    User::whereIn('id', $selectedUsers)->update(['status' => 'active']);
                    $message = count($selectedUsers) . ' users have been activated successfully.';
                    break;
                    
                case 'deactivate':
                    User::whereIn('id', $selectedUsers)->update(['status' => 'inactive']);
                    $message = count($selectedUsers) . ' users have been deactivated successfully.';
                    break;
                    
                case 'delete':
                    // Check if any of the users have related records that would prevent deletion
                    $usersWithRecords = [];
                    
                    foreach ($selectedUsers as $userId) {
                        $user = User::find($userId);
                        $hasRelatedRecords = false;
                        
                        // Check if user has related records through teacher profile
                        if ($user->isTeacher() && $user->teacherProfile) {
                            // Check if the hasRelatedRecords method exists
                            if (method_exists($user->teacherProfile, 'hasRelatedRecords')) {
                                $hasRelatedRecords = $user->teacherProfile->hasRelatedRecords();
                            } else {
                                // Fallback: check for basic relationships
                                $hasRelatedRecords = $user->teacherProfile->subjects()->count() > 0 || 
                                                   $user->teacherProfile->classes()->count() > 0;
                            }
                        }
                        
                        // Check if user has related records through student profile
                        if (!$hasRelatedRecords && $user->isStudent() && $user->studentProfile) {
                            // Check if the hasRelatedRecords method exists
                            if (method_exists($user->studentProfile, 'hasRelatedRecords')) {
                                $hasRelatedRecords = $user->studentProfile->hasRelatedRecords();
                            } else {
                                // Fallback: check for basic relationships
                                $hasRelatedRecords = $user->studentProfile->academicRecords()->count() > 0 || 
                                                   $user->studentProfile->transcripts()->count() > 0;
                            }
                        }
                        
                        if ($hasRelatedRecords) {
                            $usersWithRecords[] = $user->name;
                        }
                    }
                    
                    if (!empty($usersWithRecords)) {
                        DB::rollBack();
                        return redirect()->route('users.index')
                            ->with('error', 'The following users cannot be deleted because they have related records: ' . implode(', ', $usersWithRecords));
                    }
                    
                    // Delete users without related records
                    User::whereIn('id', $selectedUsers)->delete();
                    $message = count($selectedUsers) . ' users have been deleted successfully.';
                    break;
            }
            
            DB::commit();
            return redirect()->route('users.index')->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.index')
                ->with('error', 'An error occurred while processing the bulk action: ' . $e->getMessage());
        }
    }
}
