@extends('layouts.app')

@section('title', 'Take Attendance')

@section('content')
    <div class="flex flex-col md:flex-row items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Take Attendance</h1>
        <a href="{{ route('lesson-schedules.show', $lessonSchedule->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Schedule
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Schedule Information -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Schedule Information</h2>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm"><span class="font-semibold">Centre:</span> {{ $lessonSchedule->centre->name }}</p>
                </div>
                <div>
                    <p class="text-sm"><span class="font-semibold">Day:</span> {{ $lessonSchedule->day_of_week }}</p>
                </div>
                <div>
                    <p class="text-sm"><span class="font-semibold">Time:</span> {{ $lessonSchedule->lessonSection->start_time }} - {{ $lessonSchedule->lessonSection->end_time }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <p class="text-sm"><span class="font-semibold">Teacher:</span> {{ $lessonSchedule->teacher->user->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm"><span class="font-semibold">Subject:</span> {{ $lessonSchedule->subject->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm"><span class="font-semibold">Students:</span> {{ $lessonSchedule->students->count() }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <p class="text-sm"><span class="font-semibold">Date:</span> {{ $attendanceDate->format('d M Y') }}</p>
                </div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    <!-- Date Selection -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Select Date</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('lesson-attendances.take', $lessonSchedule->id) }}" method="GET" class="form-inline">
                <div class="form-group mb-2">
                    <label for="date" class="sr-only">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ $attendanceDate->format('Y-m-d') }}">
                </div>
                
                <button type="submit" class="btn btn-primary mb-2 ml-2">
                    <i class="fas fa-calendar-day"></i> Change Date
                </button>
                
                <a href="{{ route('lesson-attendances.take', ['lessonSchedule' => $lessonSchedule->id, 'date' => now()->format('Y-m-d')]) }}" class="btn btn-warning mb-2 ml-2">
                    <i class="fas fa-calendar-day"></i> Today
                </a>
            </form>
        </div>
    </div>

    <!-- Attendance Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex flex-col md:flex-row items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900 mb-2 md:mb-0">Attendance for {{ $attendanceDate->format('l, d M Y') }}</h2>
            <div class="flex space-x-2">
                <button type="button" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mark-all" data-status="present">
                    <i class="fas fa-check mr-1"></i> Mark All Present
                </button>
                <button type="button" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mark-all" data-status="absent">
                    <i class="fas fa-times mr-1"></i> Mark All Absent
                </button>
            </div>
        </div>
        <div class="p-4">
            @if($lessonSchedule->students->isEmpty())
                <div class="text-center py-8">
                    <i class="fas fa-users-slash text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 mb-4">No students enrolled in this schedule.</p>
                    <a href="{{ route('lesson-schedules.assign-students', $lessonSchedule->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-user-plus mr-2"></i> Assign Students
                    </a>
                </div>
            @else
                <form action="{{ route('lesson-attendances.store', $lessonSchedule->id) }}" method="POST" id="attendanceForm" class="space-y-4">
                    @csrf
                    <input type="hidden" name="attendance_date" value="{{ $attendanceDate->format('Y-m-d') }}">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="attendanceTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Attendance</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($lessonSchedule->students as $student)
                                    @php
                                        $attendance = $attendances[$student->id] ?? null;
                                        $lastAttendance = $student->lessonAttendances()
                                            ->where('lesson_schedule_id', $lessonSchedule->id)
                                            ->where('attendance_date', '<', $attendanceDate->format('Y-m-d'))
                                            ->orderBy('attendance_date', 'desc')
                                            ->first();
                                    @endphp
                                    <tr class="hover:bg-gray-100">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $student->user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $student->getAge() }} years old</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                                            <div class="flex items-center">
                                                <!-- Toggle Switch -->
                                                <div class="relative inline-block w-16 mr-2 align-middle select-none transition duration-200 ease-in">
                                                    <input type="checkbox" name="toggle-status[{{ $student->id }}]" id="toggle-{{ $student->id }}" 
                                                        class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                                        data-student-id="{{ $student->id }}"
                                                        {{ $attendance && $attendance->status == 'present' ? 'checked' : '' }}>
                                                    <label for="toggle-{{ $student->id }}" 
                                                        class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                                    <input type="hidden" name="status[{{ $student->id }}]" id="status-input-{{ $student->id }}" 
                                                        value="{{ $attendance ? $attendance->status : 'absent' }}">
                                                </div>
                                                <!-- Status Label -->
                                                <span id="status-label-{{ $student->id }}" 
                                                    class="text-sm font-medium {{ $attendance && $attendance->status == 'present' ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $attendance && $attendance->status == 'present' ? 'Present' : 'Absent' }}
                                                </span>
                                                <!-- Check-in Time -->
                                                <span id="check-in-time-{{ $student->id }}" class="text-xs text-gray-500 ml-2">
                                                    {{ $attendance && $attendance->check_in_time ? '(' . $attendance->check_in_time->format('H:i') . ')' : '' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" name="notes[{{ $student->id }}]" value="{{ $attendance ? $attendance->notes : '' }}" placeholder="Optional notes">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($lastAttendance)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lastAttendance->status == 'present' ? 'bg-green-100 text-green-800' : ($lastAttendance->status == 'late' ? 'bg-yellow-100 text-yellow-800' : ($lastAttendance->status == 'excused' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                                    {{ ucfirst($lastAttendance->status) }}
                                                </span>
                                                <div class="text-xs text-gray-500 mt-1">{{ $lastAttendance->attendance_date->format('d M Y') }}</div>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">No record</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Save Attendance
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    /* Toggle Switch Styles */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #68D391;
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #68D391;
    }
    .toggle-checkbox {
        right: 10px;
        top: 0;
        z-index: 1;
        border-color: #f56565;
        transition: all 0.3s;
    }
    .toggle-label {
        transition: background-color 0.3s;
    }
</style>
<script>
    $(document).ready(function() {
        // Auto-submit form when date changes
        $('#date').change(function() {
            $(this).closest('form').submit();
        });
        
        // Handle toggle switches for attendance
        $('.toggle-checkbox').change(function() {
            const studentId = $(this).data('student-id');
            const isPresent = $(this).prop('checked');
            const status = isPresent ? 'present' : 'absent';
            const statusLabel = isPresent ? 'Present' : 'Absent';
            const statusClass = isPresent ? 'text-green-600' : 'text-red-600';
            
            // Update hidden input
            $(`#status-input-${studentId}`).val(status);
            
            // Update status label
            $(`#status-label-${studentId}`)
                .text(statusLabel)
                .removeClass('text-green-600 text-red-600')
                .addClass(statusClass);
            
            // Send AJAX request to update attendance
            $.ajax({
                url: `{{ route('lesson-attendances.update-status', $lessonSchedule->id) }}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    student_id: studentId,
                    status: status,
                    attendance_date: '{{ $attendanceDate->format("Y-m-d") }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Update check-in time display if present
                        if (isPresent && response.check_in_time) {
                            $(`#check-in-time-${studentId}`).text(`(${response.check_in_time})`);
                        } else {
                            $(`#check-in-time-${studentId}`).text('');
                        }
                        
                        // Show notification
                        Swal.fire({
                            title: 'Success!',
                            text: `Attendance ${statusLabel} recorded for ${response.student_name}. ${response.notification_sent ? 'Email notification sent.' : ''}`,
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error updating attendance:', xhr);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to update attendance. Please try again.',
                        icon: 'error',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            });
        });
        
        // Mark all students with the same status
        $('.mark-all').click(function() {
            const status = $(this).data('status');
            $(`input[value="${status}"]`).prop('checked', true).closest('label').addClass('active');
            $(`input[value!="${status}"]`).prop('checked', false).closest('label').removeClass('active');
        });
        
        // Initialize DataTable
        $('#attendanceTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": true
        });
    });
</script>
@endsection
