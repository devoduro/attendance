@extends('layouts.app')

@section('title', 'Edit Lesson Schedule')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Edit Lesson Schedule</h1>
        <a href="{{ route('lesson-schedules.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Lesson Schedules
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-700">Lesson Schedule Details</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('lesson-schedules.update', $lessonSchedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="centre_id" class="block text-sm font-medium text-gray-700 mb-1">Centre <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('centre_id') border-red-500 @enderror" id="centre_id" name="centre_id" required>
                        <option value="">Select Centre</option>
                        @foreach($centres as $centre)
                            <option value="{{ $centre->id }}" {{ old('centre_id', $lessonSchedule->centre_id) == $centre->id ? 'selected' : '' }}>
                                {{ $centre->name }} ({{ $centre->location }})
                            </option>
                        @endforeach
                    </select>
                    @error('centre_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="lesson_section_id" class="block text-sm font-medium text-gray-700 mb-1">Lesson Section <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('lesson_section_id') border-red-500 @enderror" id="lesson_section_id" name="lesson_section_id" required>
                        <option value="">Select Lesson Section</option>
                        @foreach($lessonSections as $section)
                            <option value="{{ $section->id }}" {{ old('lesson_section_id', $lessonSchedule->lesson_section_id) == $section->id ? 'selected' : '' }}>
                                {{ $section->name }} ({{ $section->start_time }} - {{ $section->end_time }})
                            </option>
                        @endforeach
                    </select>
                    @error('lesson_section_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-1">Teacher <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('teacher_id') border-red-500 @enderror" id="teacher_id" name="teacher_id" required>
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $lessonSchedule->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">Subject <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subject_id') border-red-500 @enderror" id="subject_id" name="subject_id" required>
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $lessonSchedule->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">Day of Week <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('day_of_week') border-red-500 @enderror" id="day_of_week" name="day_of_week" required>
                        <option value="">Select Day</option>
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <option value="{{ $day }}" {{ old('day_of_week', $lessonSchedule->day_of_week) == $day ? 'selected' : '' }}>
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
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror" id="start_date" name="start_date" value="{{ old('start_date', $lessonSchedule->start_date->format('Y-m-d')) }}" required>
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('end_date') border-red-500 @enderror" id="end_date" name="end_date" value="{{ old('end_date', $lessonSchedule->end_date ? $lessonSchedule->end_date->format('Y-m-d') : '') }}">
                    <p class="mt-1 text-sm text-gray-500">Leave blank if the schedule is ongoing</p>
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror" id="notes" name="notes" rows="3">{{ old('notes', $lessonSchedule->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="flex items-center">
                        <div class="relative inline-block w-10 mr-2 align-middle select-none">
                            <input type="checkbox" class="absolute block w-6 h-6 bg-white border-4 rounded-full appearance-none cursor-pointer focus:outline-none transition-transform duration-200 ease-in transform translate-x-0 checked:translate-x-4 checked:bg-blue-500 checked:border-blue-500" id="is_active" name="is_active" value="1" {{ old('is_active', $lessonSchedule->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="block h-6 overflow-hidden bg-gray-300 rounded-full cursor-pointer"></label>
                        </div>
                        <span class="text-sm text-gray-700">Active</span>
                    </div>
                </div>
                
                <div class="flex justify-end mt-8">
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md shadow-sm inline-flex items-center">
                        <i class="fas fa-save mr-2"></i> Update Lesson Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validate that end date is after start date if provided
        document.querySelector('form').addEventListener('submit', function(e) {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            
            if (endDate && startDate >= endDate) {
                e.preventDefault();
                alert('End date must be after start date');
                return false;
            }
        });
    });
</script>
@endsection
