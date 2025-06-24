<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return view('profile.admin_edit', compact('user'));
        } elseif ($user->isTeacher()) {
            $teacher = $user->teacherProfile;
            return view('profile.teacher_edit', compact('user', 'teacher'));
        } else {
            $student = $user->studentProfile;
            return view('profile.student_edit', compact('user', 'student'));
        }
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        try {
            // Update user
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            
            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($user->profile_photo_path) {
                    Storage::delete('public/' . $user->profile_photo_path);
                }
                
                $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
                $user->profile_photo_path = str_replace('public/', '', $photoPath);
            }
            
            $user->save();
            
            // Update role-specific profile
            if ($user->isTeacher() && $request->has('teacher')) {
                $teacher = $user->teacherProfile;
                $teacher->update($request->teacher);
            } elseif ($user->isStudent() && $request->has('student')) {
                $student = $user->studentProfile;
                $student->update($request->student);
            }
            
            return redirect()->route('profile.show')
                ->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating profile: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for changing the user's password.
     *
     * @return \Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        return view('profile.change_password');
    }

    /**
     * Change the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->password_changed_at = now();
        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Password changed successfully.');
    }

    /**
     * Remove the user's profile photo.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeProfilePhoto()
    {
        $user = Auth::user();
        
        if ($user->profile_photo_path) {
            Storage::delete('public/' . $user->profile_photo_path);
            $user->profile_photo_path = null;
            $user->save();
        }
        
        return redirect()->route('profile.show')
            ->with('success', 'Profile photo removed successfully.');
    }
}
