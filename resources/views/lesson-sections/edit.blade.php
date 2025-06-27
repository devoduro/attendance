@extends('layouts.app')

@section('title', 'Edit Lesson Section')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center mb-4 sm:mb-0">
            <i class="fas fa-edit mr-3 text-yellow-600"></i> Edit Lesson Section
        </h1>
        <a href="{{ route('lesson-sections.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Back to Lesson Sections
        </a>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-yellow-500 mb-6">
        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-4 py-3 flex justify-between items-center text-white">
            <h2 class="text-lg font-bold flex items-center"><i class="fas fa-clock mr-2"></i>Lesson Section Details</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('lesson-sections.update', $lessonSection->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Name Field -->
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <label for="name" class="block text-sm font-medium text-gray-700 md:w-1/4 mb-2 md:mb-0">
                            <div class="flex items-center">
                                <i class="fas fa-signature text-gray-400 mr-2"></i>
                                Name <span class="text-red-500">*</span>
                            </div>
                        </label>
                        <div class="md:w-3/4">
                            <input type="text" id="name" name="name" value="{{ old('name', $lessonSection->name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 {{ $errors->has('name') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : '' }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Example: Morning Session, Afternoon Session, Evening Session</p>
                        </div>
                    </div>
                </div>
                
                <!-- Start Time Field -->
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <label for="start_time" class="block text-sm font-medium text-gray-700 md:w-1/4 mb-2 md:mb-0">
                            <div class="flex items-center">
                                <i class="fas fa-hourglass-start text-gray-400 mr-2"></i>
                                Start Time <span class="text-red-500">*</span>
                            </div>
                        </label>
                        <div class="md:w-3/4">
                            <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $lessonSection->start_time) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 {{ $errors->has('start_time') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : '' }}">
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- End Time Field -->
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <label for="end_time" class="block text-sm font-medium text-gray-700 md:w-1/4 mb-2 md:mb-0">
                            <div class="flex items-center">
                                <i class="fas fa-hourglass-end text-gray-400 mr-2"></i>
                                End Time <span class="text-red-500">*</span>
                            </div>
                        </label>
                        <div class="md:w-3/4">
                            <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $lessonSection->end_time) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 {{ $errors->has('end_time') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : '' }}">
                            @error('end_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Description Field -->
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-start">
                        <label for="description" class="block text-sm font-medium text-gray-700 md:w-1/4 mb-2 md:mb-0 md:pt-2">
                            <div class="flex items-center">
                                <i class="fas fa-align-left text-gray-400 mr-2"></i>
                                Description
                            </div>
                        </label>
                        <div class="md:w-3/4">
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 {{ $errors->has('description') ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : '' }}">{{ old('description', $lessonSection->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Status Field -->
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <label for="is_active" class="block text-sm font-medium text-gray-700 md:w-1/4 mb-2 md:mb-0">
                            <div class="flex items-center">
                                <i class="fas fa-toggle-on text-gray-400 mr-2"></i>
                                Status
                            </div>
                        </label>
                        <div class="md:w-3/4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 h-5 w-5" id="is_active" name="is_active" value="1" {{ old('is_active', $lessonSection->is_active) ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end mt-8">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i> Update Lesson Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Validate that end time is after start time
        $('form').on('submit', function(e) {
            const startTime = $('#start_time').val();
            const endTime = $('#end_time').val();
            
            if (startTime >= endTime) {
                e.preventDefault();
                
                // Use SweetAlert2 if available, otherwise use native alert
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Validation Error',
                        text: 'End time must be after start time',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    alert('End time must be after start time');
                }
                
                return false;
            }
        });
    });
</script>
@endsection
