@extends('layouts.app')

@section('title', 'Add New Lesson Schedule')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-calendar-alt text-blue-500"></i>
            Add New Lesson Schedule
        </h1>
        <a href="{{ route('lesson-schedules.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-semibold rounded shadow hover:bg-gray-700 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Lesson Schedules
        </a>
    </div>

    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-blue-700 flex items-center gap-2">
                <i class="fas fa-info-circle"></i> Lesson Schedule Details
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('lesson-schedules.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="centre_id" class="block text-sm font-medium text-gray-700 mb-1">Centre <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('centre_id') border-red-500 @enderror" id="centre_id" name="centre_id" required>
                        <option value="">Select Centre</option>
                        @foreach($centres as $id => $name)
                            <option value="{{ $id }}" {{ old('centre_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('centre_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="lesson_section_id" class="block text-sm font-medium text-gray-700 mb-1">Lesson Section <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('lesson_section_id') border-red-500 @enderror" id="lesson_section_id" name="lesson_section_id" required>
                        <option value="">Select Lesson Section</option>
                        @foreach($lessonSections as $id => $name)
                            <option value="{{ $id }}" {{ old('lesson_section_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('lesson_section_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-1">Teacher <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('teacher_id') border-red-500 @enderror" id="teacher_id" name="teacher_id" required>
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $id => $name)
                            <option value="{{ $id }}" {{ old('teacher_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">Day of Week <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('day_of_week') border-red-500 @enderror" id="day_of_week" name="day_of_week" required>
                        <option value="">Select Day</option>
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <option value="{{ $day }}" {{ old('day_of_week') == $day ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                    @error('day_of_week')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                    <p class="mt-1 text-sm text-gray-500">Leave blank if the schedule is ongoing</p>
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea class="w-full px-3 py-2 border border-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="flex items-center">
                        <input type="checkbox" class="h-4 w-4 border border-gray-300 rounded bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label class="ml-2 text-sm text-gray-700" for="is_active">Active</label>
                    </div>
                </div>
                
                <div class="mt-8">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded shadow hover:bg-blue-700 transition flex items-center">
                        <i class="fas fa-save mr-2"></i> Save Lesson Schedule
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
        // Validate that end date is after start date if provided
        $('form').on('submit', function(e) {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();
            
            if (endDate && startDate >= endDate) {
                e.preventDefault();
                // Use a more modern alert with Tailwind classes
                const alertDiv = $('<div class="fixed top-4 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md z-50" role="alert">' +
                    '<div class="flex items-center">' +
                    '<div class="py-1"><i class="fas fa-exclamation-circle mr-2"></i></div>' +
                    '<div>End date must be after start date</div>' +
                    '<button type="button" class="ml-auto text-red-700 hover:text-red-900" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-times"></i></button>' +
                    '</div></div>');
                
                $('body').append(alertDiv);
                
                // Auto-remove after 5 seconds
                setTimeout(function() {
                    alertDiv.fadeOut('slow', function() { $(this).remove(); });
                }, 5000);
                
                return false;
            }
        });
    });
</script>
@endsection
