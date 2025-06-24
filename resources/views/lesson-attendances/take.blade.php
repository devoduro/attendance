@extends('layouts.app')

@section('title', 'Take Attendance')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Take Attendance</h1>
        <div>
            <a href="{{ route('lesson-schedules.show', $lessonSchedule->id) }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Schedule
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Schedule Information -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Schedule Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Centre:</strong> {{ $lessonSchedule->centre->name }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Day:</strong> {{ $lessonSchedule->day_of_week }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Time:</strong> {{ $lessonSchedule->lessonSection->start_time }} - {{ $lessonSchedule->lessonSection->end_time }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Teacher:</strong> {{ $lessonSchedule->teacher->user->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Students:</strong> {{ $lessonSchedule->students->count() }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Date:</strong> {{ $attendanceDate->format('d M Y') }}</p>
                </div>
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

    <!-- Take Attendance Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Attendance for {{ $attendanceDate->format('l, d M Y') }}</h6>
            <div>
                <button type="button" class="btn btn-success btn-sm mark-all" data-status="present">
                    <i class="fas fa-check"></i> Mark All Present
                </button>
                <button type="button" class="btn btn-danger btn-sm mark-all" data-status="absent">
                    <i class="fas fa-times"></i> Mark All Absent
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($lessonSchedule->students->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-users-slash fa-4x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No students enrolled in this schedule.</p>
                    <a href="{{ route('lesson-schedules.assign-students', $lessonSchedule->id) }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Assign Students
                    </a>
                </div>
            @else
                <form action="{{ route('lesson-attendances.store', $lessonSchedule->id) }}" method="POST" id="attendanceForm">
                    @csrf
                    <input type="hidden" name="date" value="{{ $attendanceDate->format('Y-m-d') }}">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="attendanceTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Last Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lessonSchedule->students as $student)
                                    @php
                                        $attendance = $attendances->where('student_id', $student->id)->first();
                                        $lastAttendance = $student->lessonAttendances()
                                            ->where('lesson_schedule_id', $lessonSchedule->id)
                                            ->where('date', '<', $attendanceDate->format('Y-m-d'))
                                            ->orderBy('date', 'desc')
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                                            {{ $student->user->name }}
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-outline-success {{ $attendance && $attendance->status == 'present' ? 'active' : '' }}">
                                                    <input type="radio" name="status[{{ $student->id }}]" value="present" {{ $attendance && $attendance->status == 'present' ? 'checked' : '' }}> 
                                                    <i class="fas fa-check"></i> Present
                                                </label>
                                                <label class="btn btn-outline-danger {{ $attendance && $attendance->status == 'absent' ? 'active' : '' }}">
                                                    <input type="radio" name="status[{{ $student->id }}]" value="absent" {{ $attendance && $attendance->status == 'absent' ? 'checked' : '' }}> 
                                                    <i class="fas fa-times"></i> Absent
                                                </label>
                                                <label class="btn btn-outline-warning {{ $attendance && $attendance->status == 'late' ? 'active' : '' }}">
                                                    <input type="radio" name="status[{{ $student->id }}]" value="late" {{ $attendance && $attendance->status == 'late' ? 'checked' : '' }}> 
                                                    <i class="fas fa-clock"></i> Late
                                                </label>
                                                <label class="btn btn-outline-secondary {{ $attendance && $attendance->status == 'excused' ? 'active' : '' }}">
                                                    <input type="radio" name="status[{{ $student->id }}]" value="excused" {{ $attendance && $attendance->status == 'excused' ? 'checked' : '' }}> 
                                                    <i class="fas fa-user-clock"></i> Excused
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="notes[{{ $student->id }}]" value="{{ $attendance ? $attendance->notes : '' }}" placeholder="Optional notes">
                                        </td>
                                        <td>
                                            @if($lastAttendance)
                                                <span class="badge badge-{{ $lastAttendance->status == 'present' ? 'success' : ($lastAttendance->status == 'late' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($lastAttendance->status) }}
                                                </span>
                                                <small class="text-muted">{{ $lastAttendance->date->format('d M Y') }}</small>
                                            @else
                                                <span class="badge badge-secondary">No record</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Attendance
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto-submit form when date changes
        $('#date').change(function() {
            $(this).closest('form').submit();
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
