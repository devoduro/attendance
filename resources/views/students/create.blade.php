@extends('layouts.app')

@section('title', 'Add New Student')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Add New Student</h1>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('students.index') }}" class="btn-outline">
                <i class="fas fa-arrow-left mr-1"></i> Back to Students
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <form action="{{ route('students.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Student Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 form-input block w-full rounded-md" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 form-input block w-full rounded-md" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Enrollment Code -->
                <div>
                    <label for="enrollment_code" class="block text-sm font-medium text-gray-700">Enrollment Code <span class="text-red-500">*</span></label>
                    <input type="text" name="enrollment_code" id="enrollment_code" value="{{ old('enrollment_code') }}" class="mt-1 form-input block w-full rounded-md" required>
                    @error('enrollment_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 form-input block w-full rounded-md">
                    @error('date_of_birth')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <select name="gender" id="gender" class="mt-1 form-select block w-full rounded-md">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mobile Phone -->
                <div>
                    <label for="mobile_phone" class="block text-sm font-medium text-gray-700">Mobile Phone</label>
                    <input type="tel" name="mobile_phone" id="mobile_phone" value="{{ old('mobile_phone') }}" class="mt-1 form-input block w-full rounded-md">
                    @error('mobile_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" id="address" rows="3" class="mt-1 form-textarea block w-full rounded-md">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Class -->
                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-700">Class <span class="text-red-500">*</span></label>
                    <select name="class_id" id="class_id" class="mt-1 form-select block w-full rounded-md" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Program -->
                <div>
                    <label for="program_id" class="block text-sm font-medium text-gray-700">Program <span class="text-red-500">*</span></label>
                    <select name="program_id" id="program_id" class="mt-1 form-select block w-full rounded-md" required>
                        <option value="">Select Program</option>
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
                        @endforeach
                    </select>
                    @error('program_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- House -->
                <div>
                    <label for="house_id" class="block text-sm font-medium text-gray-700">House</label>
                    <select name="house_id" id="house_id" class="mt-1 form-select block w-full rounded-md">
                        <option value="">Select House</option>
                        @foreach($houses as $house)
                            <option value="{{ $house->id }}" {{ old('house_id') == $house->id ? 'selected' : '' }}>{{ $house->name }}</option>
                        @endforeach
                    </select>
                    @error('house_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="mt-1 form-select block w-full rounded-md" required>
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Guardian Information Section -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Guardian Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Guardian Name -->
                    <div>
                        <label for="guardian_name" class="block text-sm font-medium text-gray-700">Guardian Name</label>
                        <input type="text" name="guardian_name" id="guardian_name" value="{{ old('guardian_name') }}" class="mt-1 form-input block w-full rounded-md">
                        @error('guardian_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Guardian Relationship -->
                    <div>
                        <label for="guardian_relationship" class="block text-sm font-medium text-gray-700">Relationship</label>
                        <input type="text" name="guardian_relationship" id="guardian_relationship" value="{{ old('guardian_relationship') }}" class="mt-1 form-input block w-full rounded-md">
                        @error('guardian_relationship')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Guardian Phone -->
                    <div>
                        <label for="guardian_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" name="guardian_phone" id="guardian_phone" value="{{ old('guardian_phone') }}" class="mt-1 form-input block w-full rounded-md">
                        @error('guardian_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Guardian Email -->
                    <div>
                        <label for="guardian_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email" name="guardian_email" id="guardian_email" value="{{ old('guardian_email') }}" class="mt-1 form-input block w-full rounded-md">
                        @error('guardian_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="mt-8">
                <label for="notes" class="block text-sm font-medium text-gray-700">Additional Notes</label>
                <textarea name="notes" id="notes" rows="3" class="mt-1 form-textarea block w-full rounded-md">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-1"></i> Create Student
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
