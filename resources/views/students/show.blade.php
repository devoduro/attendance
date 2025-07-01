@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Student Details</h1>
        <div>
            <a href="{{ route('students.edit', $student->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded mr-2">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <a href="{{ route('students.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Personal Information</h2>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Name:</span>
                        <span>{{ $student->user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Email:</span>
                        <span>{{ $student->email ?? $student->user->email ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Date of Birth:</span>
                        <span>{{ $student->date_of_birth ? $student->date_of_birth->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Age:</span>
                        <span>{{ $student->getAge() ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Enrollment Information</h2>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Code:</span>
                        <span>{{ $student->enrollment_code ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $student->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($student->status ?? 'N/A') }}
                        </span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Centre:</span>
                        <span>{{ $student->centre->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Class:</span>
                        <span>{{ $student->class->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Admission Date:</span>
                        <span>{{ $student->admission_date ? $student->admission_date->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">School Information</h2>
        <div class="space-y-3">
            <div class="flex">
                <span class="font-medium w-32 text-gray-600">School:</span>
                <span>{{ $student->school_attending ?? 'N/A' }}</span>
            </div>
            <div class="flex">
                <span class="font-medium w-32 text-gray-600">Medical:</span>
                <span>{{ $student->medical_condition ?? 'None' }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Parent/Guardian Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium mb-2 text-gray-700">Primary Contact</h3>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Name:</span>
                        <span>{{ $student->parent_name ?? $student->guardians_name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Address:</span>
                        <span>{{ $student->parent_address ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Post Code:</span>
                        <span>{{ $student->parent_post_code ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Phone 1:</span>
                        <span>{{ $student->parent_contact_number1 ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Phone 2:</span>
                        <span>{{ $student->parent_contact_number2 ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Email:</span>
                        <span>{{ $student->parent_email ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium mb-2 text-gray-700">Secondary Contact</h3>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Name:</span>
                        <span>{{ $student->second_parent_name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Address:</span>
                        <span>{{ $student->second_parent_address ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Post Code:</span>
                        <span>{{ $student->second_parent_post_code ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Phone 1:</span>
                        <span>{{ $student->second_parent_contact_number1 ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Phone 2:</span>
                        <span>{{ $student->second_parent_contact_number2 ?? 'N/A' }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-medium w-32 text-gray-600">Email:</span>
                        <span>{{ $student->second_parent_email ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex">
                <span class="font-medium w-32 text-gray-600">Other Children:</span>
                <span>{{ $student->other_children_in_family ?? 'None' }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Lesson Schedules</h2>
        @if($student->lessonSchedules && $student->lessonSchedules->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Centre</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->lessonSchedules as $schedule)
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $schedule->day_of_week }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">
                                    {{ $schedule->lessonSection ? $schedule->lessonSection->start_time . ' - ' . $schedule->lessonSection->end_time : 'N/A' }}
                                </td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $schedule->subject->name ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $schedule->centre->name ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 italic">No lesson schedules found for this student.</p>
        @endif
    </div>
</div>
@endsection
