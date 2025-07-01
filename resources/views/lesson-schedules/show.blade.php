@extends('layouts.app')

@section('title', 'Lesson Schedule Details')

@section('content')
<div class="w-full">
    <div class="flex flex-col sm:flex-row items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4 sm:mb-0">Lesson Schedule Details</h1>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('lesson-schedules.edit', $lessonSchedule->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-edit mr-2"></i> Edit Schedule
            </a>
            <a href="{{ route('lesson-schedules.assign-students', $lessonSchedule->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                <i class="fas fa-user-plus mr-2"></i> Assign Students
            </a>
            <a href="{{ route('lesson-attendances.take', $lessonSchedule->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-clipboard-check mr-2"></i> Take Attendance
            </a>
            <a href="{{ route('lesson-schedules.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Schedule Information</h3>
                </div>
                <div class="p-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Centre</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $lessonSchedule->centre->name }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $lessonSchedule->day_of_week }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lesson Section</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $lessonSchedule->lessonSection->name }} ({{ $lessonSchedule->lessonSection->start_time }} - {{ $lessonSchedule->lessonSection->end_time }})</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $lessonSchedule->teacher->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $lessonSchedule->subject->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $lessonSchedule->start_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $lessonSchedule->end_date ? $lessonSchedule->end_date->format('d M Y') : 'Ongoing' }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $lessonSchedule->notes ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $lessonSchedule->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $lessonSchedule->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $lessonSchedule->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated At</th>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $lessonSchedule->updated_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Schedule Statistics</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <div class="bg-white rounded-lg border-l-4 border-blue-500 shadow-sm p-4 h-full">
                                <div class="flex items-center">
                                    <div class="flex-grow">
                                        <div class="text-xs font-bold text-blue-600 uppercase mb-1">
                                            Enrolled Students</div>
                                        <div class="text-2xl font-bold text-gray-800">
                                            {{ $lessonSchedule->students->count() }}
                                        </div>
                                    </div>
                                    <div>
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="bg-white rounded-lg border-l-4 border-green-500 shadow-sm p-4 h-full">
                                <div class="flex items-center">
                                    <div class="flex-grow">
                                        <div class="text-xs font-bold text-green-600 uppercase mb-1">
                                            Attendance Records</div>
                                        <div class="text-2xl font-bold text-gray-800">
                                            {{ $lessonSchedule->lessonAttendances->count() }}
                                        </div>
                                    </div>
                                    <div>
                                        <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="bg-white rounded-lg border-l-4 border-blue-400 shadow-sm p-4 h-full">
                                <div class="flex items-center">
                                    <div class="flex-grow">
                                        <div class="text-xs font-bold text-blue-400 uppercase mb-1">
                                            Present Rate</div>
                                        <div class="flex items-center">
                                            <div class="text-2xl font-bold text-gray-800 mr-3">
                                                @php
                                                    $totalAttendance = $lessonSchedule->lessonAttendances->count();
                                                    $presentCount = $lessonSchedule->lessonAttendances->where('status', 'present')->count();
                                                    $presentRate = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100) : 0;
                                                @endphp
                                                {{ $presentRate }}%
                                            </div>
                                            <div class="flex-grow">
                                                <div class="overflow-hidden h-2 text-xs flex rounded bg-blue-200">
                                                    <div class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500" style="width: {{ $presentRate }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="bg-white rounded-lg border-l-4 border-yellow-500 shadow-sm p-4 h-full">
                                <div class="flex items-center">
                                    <div class="flex-grow">
                                        <div class="text-xs font-bold text-yellow-600 uppercase mb-1">
                                            Last Attendance</div>
                                        <div class="text-2xl font-bold text-gray-800">
                                            @php
                                                $lastAttendance = $lessonSchedule->lessonAttendances->sortByDesc('date')->first();
                                            @endphp
                                            {{ $lastAttendance ? $lastAttendance->date->format('d M Y') : 'N/A' }}
                                        </div>
                                    </div>
                                    <div>
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Students -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex flex-row items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Enrolled Students</h3>
            <a href="{{ route('lesson-schedules.assign-students', $lessonSchedule->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-user-plus mr-2"></i> Manage Students
            </a>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="studentsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent/Guardian</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($lessonSchedule->students as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->getAge() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->school_attending ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->parent_guardian_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->parent_guardian_phone ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('students.show', $student->id) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-sm text-center text-gray-500">No students enrolled in this schedule.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Attendance Records -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex flex-row items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">Recent Attendance Records</h3>
            <a href="{{ route('lesson-attendances.take', $lessonSchedule->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-clipboard-check mr-2"></i> Take Attendance
            </a>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="attendanceTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Excused</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $attendanceDates = $lessonSchedule->lessonAttendances->groupBy('date');
                            $recentDates = $attendanceDates->sortKeysDesc()->take(5);
                        @endphp
                        
                        @forelse($recentDates as $date => $attendances)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendances->where('status', 'present')->count() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendances->where('status', 'absent')->count() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendances->where('status', 'late')->count() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendances->where('status', 'excused')->count() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendances->count() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('lesson-attendances.take', ['lessonSchedule' => $lessonSchedule->id, 'date' => $date]) }}" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-sm text-center text-gray-500">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#studentsTable').DataTable();
        $('#attendanceTable').DataTable();
    });
</script>
@endsection
