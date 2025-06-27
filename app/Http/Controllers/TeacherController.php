<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    /**
     * Display a listing of the teachers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Teacher::with(['user', 'department']);
        
        // Filter by search term
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('staff_id', 'like', "%{$search}%")
                ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        
        // Filter by department
        if ($request->filled('department')) {
            $query->where('department_id', $request->input('department'));
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        
        // Subject filtering removed
        
        // Class filtering removed
        
        $teachers = $query->orderBy('created_at', 'desc')->paginate(15);
        $teachers->appends($request->except('page')); // Maintain filters when paginating
            
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $departments = Department::all();
        
        return view('teachers.create', compact('departments'));
    }

    /**
     * Store a newly created teacher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'teacher_id' => 'required|string|max:255|unique:teachers,staff_id',
            'qualification' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            // 'bio' field validation removed as it doesn't exist in the database
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Classes validation removed
        ]);

        DB::beginTransaction();

        try {
            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            
            // Assign teacher role to user
            $user->assignRole('teacher');

            if ($request->hasFile('profile_image')) {
                $request->file('profile_image')->store('teacher_photos', 'public');
                // Note: We're not saving the path since there's no column for it
            }

            // Create teacher profile
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'staff_id' => $request->teacher_id,
                'qualification' => $request->qualification,
                'department_id' => $request->department_id,
                'phone_number' => $request->phone,
                'date_employed' => now(),
                'status' => 'active',
                // 'bio' field removed as it doesn't exist in the database
                // 'profile_image' field removed as it doesn't exist in the database
            ]);

            // Subjects relationship handling removed

            // Classes relationship handling removed

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Teacher created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating teacher: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified teacher.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\View\View
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['user']);
        
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\View\View
     */
    public function edit(Teacher $teacher)
    {
        $departments = Department::all();
        
        return view('teachers.edit', compact(
            'teacher', 
            'departments'
        ));
    }

    /**
     * Update the specified teacher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->user_id,
            'teacher_id' => 'required|string|max:255|unique:teachers,staff_id,' . $teacher->id,
            'qualification' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            // 'bio' field validation removed as it doesn't exist in the database
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
            // Classes validation removed
        ]);

        DB::beginTransaction();

        try {
            // Update user account
            $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
            
            // Update password if provided
            if ($request->filled('password')) {
                $teacher->user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            $updateData = [
                'staff_id' => $request->teacher_id,
                'qualification' => $request->qualification,
                'department_id' => $request->department_id,
                'phone_number' => $request->phone,
                // 'bio' field removed as it doesn't exist in the database
            ];

            if ($request->hasFile('profile_image')) {
                // Handle profile image upload but don't store in database
                // since profile_image column doesn't exist in the teachers table
                $request->file('profile_image')->store('teacher_photos', 'public');
                // Note: We're not saving the path since there's no column for it
            }

            // Update teacher profile
            $teacher->update($updateData);

            // Subjects relationship handling removed

            // Classes relationship handling removed

            DB::commit();

            return redirect()->route('teachers.show', $teacher)
                ->with('success', 'Teacher updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating teacher: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified teacher from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Teacher $teacher)
    {
        try {
            // Delete the user account (will cascade delete the teacher profile)
            $teacher->user->delete();
            
            return redirect()->route('teachers.index')
                ->with('success', 'Teacher deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting teacher: ' . $e->getMessage());
        }
    }

    /**
     * Search for teachers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $teachers = Teacher::with('user')
            ->whereHas('user', function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhere('staff_id', 'like', "%{$query}%")
            ->orWhere('phone_number', 'like', "%{$query}%")
            ->orWhere('specialization', 'like', "%{$query}%")
            ->paginate(15);
            
        return view('teachers.index', compact('teachers', 'query'));
    }

    /**
     * Filter by class method removed
     */
    
    /**
     * Perform bulk actions on multiple teachers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'teacher_ids' => 'required|array',
            'teacher_ids.*' => 'exists:teachers,id',
        ]);
        
        $action = $request->input('action');
        $teacherIds = $request->input('teacher_ids');
        $count = count($teacherIds);
        
        DB::beginTransaction();
        
        try {
            switch ($action) {
                case 'delete':
                    // Find all users associated with these teachers
                    $userIds = Teacher::whereIn('id', $teacherIds)->pluck('user_id');
                    
                    // Delete the users (will cascade delete the teachers)
                    User::whereIn('id', $userIds)->delete();
                    $message = "{$count} teachers have been deleted successfully.";
                    break;
                    
                case 'activate':
                    Teacher::whereIn('id', $teacherIds)->update(['status' => 'active']);
                    $message = "{$count} teachers have been activated successfully.";
                    break;
                    
                case 'deactivate':
                    Teacher::whereIn('id', $teacherIds)->update(['status' => 'inactive']);
                    $message = "{$count} teachers have been deactivated successfully.";
                    break;
            }
            
            DB::commit();
            return redirect()->route('teachers.index')->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('teachers.index')
                ->with('error', 'Error performing bulk action: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle the status of a teacher between active and inactive.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(Teacher $teacher)
    {
        try {
            $newStatus = $teacher->status === 'active' ? 'inactive' : 'active';
            $teacher->update(['status' => $newStatus]);
            
            $message = "Teacher status has been changed to {$newStatus}.";
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error changing teacher status: ' . $e->getMessage());
        }
    }
}
