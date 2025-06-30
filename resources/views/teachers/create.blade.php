@extends('layouts.app')

@section('title', 'Add New Teacher')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Add New Teacher</h1>
        <a href="{{ route('teachers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md">
            Back to Teachers
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Information Section -->
                <div class="col-span-2">
                    <h2 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">User Account Information</h2>
                </div>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-600">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-600">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-600">*</span></label>
                    <input type="password" name="password" id="password" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-600">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                </div>
                
                <!-- Teacher Information Section -->
                <div class="col-span-2 mt-4">
                    <h2 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Teacher Information</h2>
                </div>
                
                <div>
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-1">Teacher ID <span class="text-red-600">*</span></label>
                    <input type="text" name="teacher_id" id="teacher_id" value="{{ old('teacher_id') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select name="department_id" id="department_id" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="centre_id" class="block text-sm font-medium text-gray-700 mb-1">Centre <span class="text-red-600">*</span></label>
                    <select name="centre_id" id="centre_id" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        <option value="">Select Centre</option>
                        @foreach($centres as $centre)
                            <option value="{{ $centre->id }}" {{ old('centre_id') == $centre->id ? 'selected' : '' }}>
                                {{ $centre->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('centre_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="qualification" class="block text-sm font-medium text-gray-700 mb-1">Qualification</label>
                    <input type="text" name="qualification" id="qualification" value="{{ old('qualification') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    @error('qualification')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Subjects section removed -->
                
                <!-- Classes selection section removed -->
                
                <div class="col-span-2">
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio/Description</label>
                    <textarea name="bio" id="bio" rows="4"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">{{ old('bio') }}</textarea>
                    @error('bio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="col-span-2">
                    <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-1">Profile Image</label>
                    <input type="file" name="profile_image" id="profile_image"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <p class="mt-1 text-sm text-gray-500">Allowed formats: JPG, PNG. Max size: 2MB</p>
                    @error('profile_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <a href="{{ route('teachers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md mr-2">
                    Cancel
                </a>
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md">
                    Create Teacher
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add any JavaScript for form handling here
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select2 or other plugins if needed
    });
</script>
@endpush
