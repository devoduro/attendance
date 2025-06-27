@extends('layouts.app')

@section('title', 'Lesson Attendances')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center mb-4 sm:mb-0">
            <i class="fas fa-clipboard-check mr-3 text-blue-600"></i> Lesson Attendances
        </h1>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('lesson-attendances.daily') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                <i class="fas fa-calendar-day mr-2"></i> Daily View
            </a>
            <a href="{{ route('lesson-attendances.weekly') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-all duration-200">
                <i class="fas fa-calendar-week mr-2"></i> Weekly View
            </a>
            <a href="{{ route('lesson-attendances.monthly') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                <i class="fas fa-calendar-alt mr-2"></i> Monthly View
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="relative px-4 py-3 mb-6 leading-normal text-green-700 bg-green-100 rounded-lg" role="alert" id="alert">
            <span class="absolute inset-y-0 left-0 flex items-center ml-4">
                <i class="fas fa-check-circle"></i>
            </span>
            <div class="ml-6">{{ session('success') }}</div>
            <button type="button" class="absolute top-0 right-0 mt-3 mr-4 text-green-700" onclick="document.getElementById('alert').remove()">
                <span class="text-xl">&times;</span>
            </button>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-blue-500 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 flex justify-between items-center text-white">
            <h2 class="text-lg font-bold flex items-center">
                <i class="fas fa-filter mr-2"></i>Filter Attendances
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('lesson-attendances.index') }}" method="GET" class="mb-0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="centre_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <div class="flex items-center">
                                <i class="fas fa-building text-gray-400 mr-2"></i>Centre
                            </div>
                        </label>
                        <select id="centre_id" name="centre_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">All Centres</option>
                            @foreach($centres as $centre)
                                <option value="{{ $centre->id }}" {{ request('centre_id') == $centre->id ? 'selected' : '' }}>
                                    {{ $centre->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <div class="flex items-center">
                                <i class="fas fa-chalkboard-teacher text-gray-400 mr-2"></i>Teacher
                            </div>
                        </label>
                        <select id="teacher_id" name="teacher_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">All Teachers</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-minus text-gray-400 mr-2"></i>Date From
                            </div>
                        </label>
                        <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                    
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-plus text-gray-400 mr-2"></i>Date To
                            </div>
                        </label>
                        <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    </div>
                
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            <div class="flex items-center">
                                <i class="fas fa-clipboard-check text-gray-400 mr-2"></i>Status
                            </div>
                        </label>
                        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">All Statuses</option>
                            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>Excused</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <div class="flex items-center">
                                <i class="fas fa-user-graduate text-gray-400 mr-2"></i>Student
                            </div>
                        </label>
                        <select id="student_id" name="student_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-end space-x-2 md:col-span-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                        <a href="{{ route('lesson-attendances.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                            <i class="fas fa-sync mr-2"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Records Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Total Records</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $attendances->total() }}</div>
                    </div>
                    <div class="rounded-full p-3 bg-blue-100">
                        <i class="fas fa-clipboard-list text-xl text-blue-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Present Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1">Present</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $presentCount }}</div>
                    </div>
                    <div class="rounded-full p-3 bg-green-100">
                        <i class="fas fa-check text-xl text-green-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Absent Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-red-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1">Absent</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $absentCount }}</div>
                    </div>
                    <div class="rounded-full p-3 bg-red-100">
                        <i class="fas fa-times text-xl text-red-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Late Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-yellow-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-bold text-yellow-600 uppercase tracking-wider mb-1">Late</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $lateCount }}</div>
                    </div>
                    <div class="rounded-full p-3 bg-yellow-100">
                        <i class="fas fa-clock text-xl text-yellow-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Records -->
    <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-purple-500 mb-6">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-4 py-3 flex justify-between items-center text-white">
            <h2 class="text-lg font-bold flex items-center">
                <i class="fas fa-clipboard-check mr-2"></i>Attendance Records
            </h2>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="text-white hover:text-gray-200 focus:outline-none">
                    <i class="fas fa-download"></i>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                    <div class="px-4 py-2 text-xs text-gray-500 border-b">Export Options</div>
                    <a href="{{ route('lesson-attendances.export', ['format' => 'csv'] + request()->all()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-file-csv mr-2 text-gray-500"></i>CSV
                    </a>
                    <a href="{{ route('lesson-attendances.export', ['format' => 'excel'] + request()->all()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-file-excel mr-2 text-gray-500"></i>Excel
                    </a>
                    <a href="{{ route('lesson-attendances.export', ['format' => 'pdf'] + request()->all()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-file-pdf mr-2 text-gray-500"></i>PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="attendanceTable">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-day mr-2 text-purple-500"></i>Date
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-user-graduate mr-2 text-purple-500"></i>Student
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-building mr-2 text-purple-500"></i>Centre
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-chalkboard-teacher mr-2 text-purple-500"></i>Teacher
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2 text-purple-500"></i>Time
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-clipboard-check mr-2 text-purple-500"></i>Status
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-sticky-note mr-2 text-purple-500"></i>Notes
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-cogs mr-2 text-purple-500"></i>Actions
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        {{ $attendance->attendance_date->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance->student->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance->lessonSchedule->centre->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance->lessonSchedule->teacher->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                        {{ $attendance->lessonSchedule->lessonSection->start_time }} - {{ $attendance->lessonSchedule->lessonSection->end_time }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ 
                                        $attendance->status == 'present' ? 'bg-green-100 text-green-800' : 
                                        ($attendance->status == 'absent' ? 'bg-red-100 text-red-800' : 
                                        ($attendance->status == 'late' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) 
                                    }}">
                                        <i class="fas {{ 
                                            $attendance->status == 'present' ? 'fa-check' : 
                                            ($attendance->status == 'absent' ? 'fa-times' : 
                                            ($attendance->status == 'late' ? 'fa-clock' : 'fa-question')) 
                                        }} mr-1"></i>
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance->notes ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('lesson-attendances.take', ['lessonSchedule' => $attendance->lesson_schedule_id, 'date' => $attendance->attendance_date->format('Y-m-d')]) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-3"></i>
                                        <p>No attendance records found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-5">
                {{ $attendances->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables with Tailwind CSS styling
        $('#attendanceTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": false,
            "language": {
                "emptyTable": "<div class='flex flex-col items-center justify-center py-10'><i class='fas fa-clipboard-list text-4xl text-gray-300 mb-3'></i><p>No attendance records found</p></div>"
            }
        });
        
        // Initialize select2 with Tailwind compatible styling
        $('#student_id, #teacher_id').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%',
            theme: 'classic'
        });
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alert = document.getElementById('alert');
            if (alert) {
                alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }
        }, 5000);
    });
</script>
@endsection
