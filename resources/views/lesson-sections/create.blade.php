@extends('layouts.app')

@section('title', 'Add New Lesson Section')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center mb-4 sm:mb-0">
            <i class="fas fa-plus-circle mr-3 text-blue-600"></i> Add New Lesson Section
        </h1>
        <a href="{{ route('lesson-sections.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
            <i class="fas fa-arrow-left mr-2"></i> Back to Lesson Sections
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-blue-500 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 flex justify-between items-center text-white">
            <h2 class="text-lg font-bold flex items-center"><i class="fas fa-edit mr-2"></i>Lesson Section Details</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('lesson-sections.store') }}" method="POST">
                @csrf
                
                <!-- Name Field -->
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <label for="name" class="block text-sm font-medium text-gray-700 md:w-1/4 mb-2 md:mb-0">
                            Name <span class="text-red-600">*</span>
                        </label>
                        <div class="md:w-3/4">
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('name') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                                placeholder="Enter section name" required>
                            
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <p class="mt-2 text-sm text-gray-500">Example: Morning Session, Afternoon Session, Evening Session</p>
                        </div>
                    </div>
                </div>
                
                <!-- Start Time Field -->
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <label for="start_time" class="block text-sm font-medium text-gray-700 md:w-1/4 mb-2 md:mb-0">
                            Start Time <span class="text-red-600">*</span>
                        </label>
                        <div class="md:w-3/4">
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" 
                                    class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('start_time') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                                    required>
                            </div>
                            
                            @error('start_time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- End Time Field -->
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <label for="end_time" class="block text-sm font-medium text-gray-700 md:w-1/4 mb-2 md:mb-0">
                            End Time <span class="text-red-600">*</span>
                        </label>
                        <div class="md:w-3/4">
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-clock text-gray-400"></i>
                                </div>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" 
                                    class="pl-10 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('end_time') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                                    required>
                            </div>
                            
                            @error('end_time')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Description Field -->
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-start">
                        <label for="description" class="block text-sm font-medium text-gray-700 md:w-1/4 mb-2 md:mb-0 md:pt-2">
                            Description
                        </label>
                        <div class="md:w-3/4">
                            <textarea id="description" name="description" rows="3" 
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md @error('description') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror" 
                                placeholder="Enter optional description">{{ old('description') }}</textarea>
                            
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Status Field -->
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <label for="is_active" class="block text-sm font-medium text-gray-700 md:w-1/4 mb-2 md:mb-0">
                            Status
                        </label>
                        <div class="md:w-3/4">
                            <div class="flex items-center">
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                        {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Weekly Repeat Field -->
                <div class="mb-6">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <label for="repeat_weekly" class="block text-sm font-medium text-gray-700 md:w-1/4 mb-2 md:mb-0">
                            Weekly Repeat
                        </label>
                        <div class="md:w-3/4">
                            <div class="flex items-center">
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="repeat_weekly" name="repeat_weekly" value="1" 
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                        {{ old('repeat_weekly', '1') == '1' ? 'checked' : '' }}>
                                    <label for="repeat_weekly" class="ml-2 block text-sm text-gray-900">
                                        Repeat Weekly
                                    </label>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">When enabled, this lesson section will be scheduled to repeat every week</p>
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex flex-col md:flex-row md:items-center">
                    <div class="md:w-1/4"></div>
                    <div class="md:w-3/4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <i class="fas fa-save mr-2"></i> Save Lesson Section
                        </button>
                    </div>
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
                // Use SweetAlert if available, otherwise fallback to regular alert
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Invalid Time Range',
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
