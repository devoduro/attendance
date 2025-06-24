@extends('layouts.app')

@section('title', 'Lesson Schedule Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lesson Schedule Details</h1>
        <div>
            <a href="{{ route('lesson-schedules.edit', $lessonSchedule->id) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Schedule
            </a>
            <a href="{{ route('lesson-schedules.assign-students', $lessonSchedule->id) }}" class="btn btn-sm btn-warning shadow-sm">
                <i class="fas fa-user-plus fa-sm text-white-50"></i> Assign Students
            </a>
            <a href="{{ route('lesson-attendances.take', $lessonSchedule->id) }}" class="btn btn-sm btn-success shadow-sm">
                <i class="fas fa-clipboard-check fa-sm text-white-50"></i> Take Attendance
            </a>
            <a href="{{ route('lesson-schedules.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Schedule Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Centre</th>
                            <td>{{ $lessonSchedule->centre->name }}</td>
                        </tr>
                        <tr>
                            <th>Day</th>
                            <td>{{ $lessonSchedule->day_of_week }}</td>
                        </tr>
                        <tr>
                            <th>Lesson Section</th>
                            <td>{{ $lessonSchedule->lessonSection->name }} ({{ $lessonSchedule->lessonSection->start_time }} - {{ $lessonSchedule->lessonSection->end_time }})</td>
                        </tr>
                        <tr>
                            <th>Teacher</th>
                            <td>{{ $lessonSchedule->teacher->user->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Start Date</th>
                            <td>{{ $lessonSchedule->start_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>End Date</th>
                            <td>{{ $lessonSchedule->end_date ? $lessonSchedule->end_date->format('d M Y') : 'Ongoing' }}</td>
                        </tr>
                        <tr>
                            <th>Notes</th>
                            <td>{{ $lessonSchedule->notes ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-{{ $lessonSchedule->is_active ? 'success' : 'danger' }}">
                                    {{ $lessonSchedule->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $lessonSchedule->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $lessonSchedule->updated_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Schedule Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Enrolled Students</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $lessonSchedule->students->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Attendance Records</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $lessonSchedule->lessonAttendances->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Present Rate</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                                        @php
                                                            $totalAttendance = $lessonSchedule->lessonAttendances->count();
                                                            $presentCount = $lessonSchedule->lessonAttendances->where('status', 'present')->count();
                                                            $presentRate = $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100) : 0;
                                                        @endphp
                                                        {{ $presentRate }}%
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $presentRate }}%" aria-valuenow="{{ $presentRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Last Attendance</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                @php
                                                    $lastAttendance = $lessonSchedule->lessonAttendances->sortByDesc('date')->first();
                                                @endphp
                                                {{ $lastAttendance ? $lastAttendance->date->format('d M Y') : 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
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
    </div>

    <!-- Enrolled Students -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Enrolled Students</h6>
            <a href="{{ route('lesson-schedules.assign-students', $lessonSchedule->id) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-user-plus"></i> Manage Students
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="studentsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>School</th>
                            <th>Parent/Guardian</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lessonSchedule->students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->user->name }}</td>
                                <td>{{ $student->getAge() }}</td>
                                <td>{{ $student->school_attending ?? 'N/A' }}</td>
                                <td>{{ $student->parent_guardian_name ?? 'N/A' }}</td>
                                <td>{{ $student->parent_guardian_phone ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No students enrolled in this schedule.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Attendance Records -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Recent Attendance Records</h6>
            <a href="{{ route('lesson-attendances.take', $lessonSchedule->id) }}" class="btn btn-sm btn-success">
                <i class="fas fa-clipboard-check"></i> Take Attendance
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="attendanceTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Late</th>
                            <th>Excused</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $attendanceDates = $lessonSchedule->lessonAttendances->groupBy('date');
                            $recentDates = $attendanceDates->sortKeysDesc()->take(5);
                        @endphp
                        
                        @forelse($recentDates as $date => $attendances)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                                <td>{{ $attendances->where('status', 'present')->count() }}</td>
                                <td>{{ $attendances->where('status', 'absent')->count() }}</td>
                                <td>{{ $attendances->where('status', 'late')->count() }}</td>
                                <td>{{ $attendances->where('status', 'excused')->count() }}</td>
                                <td>{{ $attendances->count() }}</td>
                                <td>
                                    <a href="{{ route('lesson-attendances.take', ['lessonSchedule' => $lessonSchedule->id, 'date' => $date]) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No attendance records found.</td>
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
