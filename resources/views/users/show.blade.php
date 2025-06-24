@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">User Details</h1>
        <div class="flex space-x-2">
            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Users
            </a>
            @can('update', $user)
            <a href="{{ route('users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit User
            </a>
            @endcan
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col md:flex-row">
                <!-- User Profile Photo -->
                <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="h-32 w-32 object-cover rounded-full">
                    @else
                        <div class="h-32 w-32 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-500 text-4xl font-medium">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                
                <!-- User Details -->
                <div class="flex-grow">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Name</h3>
                            <p class="mt-1 text-lg font-semibold">{{ $user->name }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Email</h3>
                            <p class="mt-1 text-lg">{{ $user->email }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Phone Number</h3>
                            <p class="mt-1 text-lg">{{ $user->phone_number ?? 'Not provided' }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($user->status ?? 'active') }}
                                </span>
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                            <p class="mt-1 text-lg">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Last Updated</h3>
                            <p class="mt-1 text-lg">{{ $user->updated_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    <!-- User Roles -->
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500">Roles</h3>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @forelse($user->roles as $role)
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @empty
                                <span class="text-gray-500">No roles assigned</span>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Additional Profile Information -->
                    @if($user->isTeacher() && $user->teacherProfile)
                        <div class="mt-6 border-t pt-4">
                            <h3 class="text-lg font-medium text-gray-900">Teacher Profile</h3>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Staff ID</h4>
                                    <p class="mt-1">{{ $user->teacherProfile->staff_id }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Department</h4>
                                    <p class="mt-1">{{ $user->teacherProfile->department->name ?? 'Not assigned' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Qualification</h4>
                                    <p class="mt-1">{{ $user->teacherProfile->qualification }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($user->isStudent() && $user->studentProfile)
                        <div class="mt-6 border-t pt-4">
                            <h3 class="text-lg font-medium text-gray-900">Student Profile</h3>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Student ID</h4>
                                    <p class="mt-1">{{ $user->studentProfile->student_id }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Class</h4>
                                    <p class="mt-1">{{ $user->studentProfile->class->name ?? 'Not assigned' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Guardian Name</h4>
                                    <p class="mt-1">{{ $user->studentProfile->guardian_name ?? 'Not provided' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500">Guardian Contact</h4>
                                    <p class="mt-1">{{ $user->studentProfile->guardian_phone ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
