@extends('layouts.app')

@section('title', 'Daily Attendance')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center mb-4 sm:mb-0">
            <i class="fas fa-calendar-day mr-3 text-blue-600"></i> Daily Attendance
        </h1>
        <div>
            <a href="{{ route('lesson-attendances.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                <i class="fas fa-list mr-2"></i> All Attendances
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

    <!-- Date and Filter Selection -->
    <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-blue-500 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 flex justify-between items-center text-white">
            <h2 class="text-lg font-bold flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i>Select Date and Filters
            </h2>
        </div>
        <div class="p-6">
            <form action="{{ route('lesson-attendances.daily') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="flex-grow-0">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-gray-400 mr-2"></i>Date
                        </div>
                    </label>
                    <input type="date" id="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
                
                <div class="flex-grow-0 min-w-[200px]">
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
                
                <div class="flex items-end space-x-2 mt-6">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    
                    <a href="{{ route('lesson-attendances.daily') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        <i class="fas fa-sync mr-2"></i> Reset
                    </a>
                </div>
            </form>
            
            <div class="flex flex-wrap gap-2 mt-4 border-t pt-4 border-gray-200">
                <a href="{{ route('lesson-attendances.daily', ['date' => $selectedDate->copy()->subDay()->format('Y-m-d'), 'centre_id' => request('centre_id')]) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Previous Day
                </a>
                
                <a href="{{ route('lesson-attendances.daily', ['date' => $selectedDate->copy()->addDay()->format('Y-m-d'), 'centre_id' => request('centre_id')]) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-all duration-200">
                    <i class="fas fa-arrow-right mr-2"></i> Next Day
                </a>
                
                <a href="{{ route('lesson-attendances.daily', ['date' => now()->format('Y-m-d'), 'centre_id' => request('centre_id')]) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200">
                    <i class="fas fa-calendar-day mr-2"></i> Today
                </a>
            </div>
        </div>
    </div>

    <!-- Daily Attendance Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Lessons Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Total Lessons</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $lessonSchedules->count() }}</div>
                    </div>
                    <div class="rounded-full p-3 bg-blue-100">
                        <i class="fas fa-calendar text-xl text-blue-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Students Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1">Total Students</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $totalStudents }}</div>
                    </div>
                    <div class="rounded-full p-3 bg-green-100">
                        <i class="fas fa-users text-xl text-green-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Taken Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-cyan-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-bold text-cyan-600 uppercase tracking-wider mb-1">Attendance Taken</div>
                        <div class="flex items-center">
                            <div class="text-2xl font-bold text-gray-800 mr-2">{{ $attendanceTakenPercentage }}%</div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-cyan-500 h-2 rounded-full" style="width: {{ $attendanceTakenPercentage }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-full p-3 bg-cyan-100">
                        <i class="fas fa-clipboard-list text-xl text-cyan-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Present Rate Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-yellow-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-bold text-yellow-600 uppercase tracking-wider mb-1">Present Rate</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $presentRate }}%</div>
                    </div>
                    <div class="rounded-full p-3 bg-yellow-100">
                        <i class="fas fa-check-circle text-xl text-yellow-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lesson Schedules for the day -->
    <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-blue-500 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 flex justify-between items-center text-white">
            <h2 class="text-lg font-bold flex items-center">
                <i class="fas fa-calendar-day mr-2"></i>Lesson Schedules for {{ $selectedDate->format('l, d M Y') }}
            </h2>
        </div>
        <div class="p-6">
            @if($lessonSchedules->isEmpty())
                <div class="flex flex-col items-center justify-center py-10">
                    <i class="fas fa-calendar-times text-5xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 text-lg">No lesson schedules found for this day.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="schedulesTable">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-building mr-1 text-gray-400"></i> Centre
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1 text-gray-400"></i> Time
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-chalkboard-teacher mr-1 text-gray-400"></i> Teacher
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-friends mr-1 text-gray-400"></i> Students
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-clipboard-list mr-1 text-gray-400"></i> Status
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-cogs mr-1 text-gray-400"></i> Actions
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($lessonSchedules as $schedule)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $schedule->centre->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $schedule->lessonSection->start_time }} - {{ $schedule->lessonSection->end_time }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $schedule->teacher->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $schedule->students->count() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @php
                                            $attendanceCount = $schedule->lessonAttendances->where('date', $selectedDate->format('Y-m-d'))->count();
                                            $studentCount = $schedule->students->count();
                                            $attendanceStatus = $studentCount > 0 ? ($attendanceCount / $studentCount) * 100 : 0;
                                        @endphp
                                        
                                        @if($attendanceStatus == 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i> Not Taken
                                            </span>
                                        @elseif($attendanceStatus < 100)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-exclamation-circle mr-1"></i> Partial ({{ round($attendanceStatus) }}%)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Complete
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('lesson-attendances.take', ['lessonSchedule' => $schedule->id, 'date' => $selectedDate->format('Y-m-d')]) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                            <i class="fas fa-clipboard-check mr-1"></i> Take Attendance
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables with Tailwind CSS styling
        $('#schedulesTable').DataTable({
            "language": {
                "emptyTable": "<div class='flex flex-col items-center justify-center py-10'><i class='fas fa-calendar-times text-4xl text-gray-300 mb-3'></i><p>No lesson schedules found</p></div>"
            }
        });
        
        // Auto-submit form when date or centre changes
        $('#date, #centre_id').change(function() {
            $(this).closest('form').submit();
        });
        
        // Initialize select2 with Tailwind compatible styling
        $('#centre_id').select2({
            placeholder: "Select a centre",
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
