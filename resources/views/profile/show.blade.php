@extends('layouts.app')

@section('title', 'User Profile')

@section('content')
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Profile</h1>
                <p class="text-sm text-gray-600">View and manage your account information</p>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="p-6">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Photo & Basic Info -->
            <div class="md:col-span-1">
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                    <div class="flex flex-col items-center">
                        <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-200 mb-4">
                            @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-500">
                                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ ucfirst($user->roles->first()->name ?? 'User') }}</p>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('profile.update') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit Profile
                        </a>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('password.change') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                            Change Password
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Account Information</h3>
                    </div>
                    <div class="p-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->phone_number ?? 'Not provided' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Account Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($user->roles->first()->name ?? 'User') }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('F d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Role-specific information -->
                @if($user->isTeacher() && $user->teacherProfile)
                <div class="mt-6 bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Teacher Information</h3>
                    </div>
                    <div class="p-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Employee ID</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->teacherProfile->employee_id ?? 'Not provided' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Department</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->teacherProfile->department ?? 'Not provided' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Specialization</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->teacherProfile->specialization ?? 'Not provided' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                @endif

                @if($user->isStudent() && $user->studentProfile)
                <div class="mt-6 bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Student Information</h3>
                    </div>
                    <div class="p-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Student ID</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->studentProfile->student_id ?? 'Not provided' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Class</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->studentProfile->schoolClass->name ?? 'Not assigned' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->studentProfile->date_of_birth ? $user->studentProfile->date_of_birth->format('F d, Y') : 'Not provided' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Guardian Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->studentProfile->guardian_name ?? 'Not provided' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Guardian Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->studentProfile->guardian_phone ?? 'Not provided' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                @endif

                @if($user->isAdmin())
                <div class="mt-6 bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Administrator Information</h3>
                    </div>
                    <div class="p-4">
                        <p class="text-sm text-gray-600">You have administrator privileges on this system.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
