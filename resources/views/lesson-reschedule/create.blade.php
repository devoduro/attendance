@extends('layouts.app')

@section('title', 'Request Lesson Reschedule')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">Request Lesson Reschedule</h1>
            <p class="mt-2 text-gray-600">Fill out this form to request rescheduling a lesson to a different section.</p>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="mb-6 bg-danger-50 border-l-4 border-danger-500 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-danger-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-danger-800">There were errors with your submission</h3>
                            <div class="mt-2 text-sm text-danger-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('lesson-reschedule.store') }}">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                        <div class="mt-1">
                            <select id="student_id" name="student_id" required 
                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md {{ $errors->has('student_id') ? 'border-danger-300 text-danger-900 placeholder-danger-300 focus:outline-none focus:ring-danger-500 focus:border-danger-500' : '' }}">
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->name }} (ID: {{ $student->student_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="current_lesson_schedule_id" class="block text-sm font-medium text-gray-700">Current Lesson</label>
                        <div class="mt-1">
                            <select id="current_lesson_schedule_id" name="current_lesson_schedule_id" required 
                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md {{ $errors->has('current_lesson_schedule_id') ? 'border-danger-300 text-danger-900 placeholder-danger-300 focus:outline-none focus:ring-danger-500 focus:border-danger-500' : '' }}">
                                <option value="">Select Current Lesson</option>
                                @foreach($lessonSchedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('current_lesson_schedule_id') == $schedule->id ? 'selected' : '' }}>
                                        {{ $schedule->subject->name }} - {{ $schedule->day_of_week }} - 
                                        {{ $schedule->start_time }} to {{ $schedule->end_time }} - 
                                        Teacher: {{ $schedule->teacher->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('current_lesson_schedule_id')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="requested_lesson_section_id" class="block text-sm font-medium text-gray-700">Requested Section</label>
                        <div class="mt-1">
                            <select id="requested_lesson_section_id" name="requested_lesson_section_id" required 
                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md {{ $errors->has('requested_lesson_section_id') ? 'border-danger-300 text-danger-900 placeholder-danger-300 focus:outline-none focus:ring-danger-500 focus:border-danger-500' : '' }}">
                                <option value="">Select Requested Section</option>
                                @foreach($lessonSections as $section)
                                    <option value="{{ $section->id }}" {{ old('requested_lesson_section_id') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }} - {{ $section->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('requested_lesson_section_id')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Reschedule</label>
                        <div class="mt-1">
                            <textarea id="reason" name="reason" rows="4" required
                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md {{ $errors->has('reason') ? 'border-danger-300 text-danger-900 placeholder-danger-300 focus:outline-none focus:ring-danger-500 focus:border-danger-500' : '' }}"
                                placeholder="Please explain why you need to reschedule this lesson">{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-5">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Submit Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
