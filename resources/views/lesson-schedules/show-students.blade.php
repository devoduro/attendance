@extends('layouts.app')

@section('title', 'Lesson Schedule Students')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Students in Lesson Schedule</h1>
        <div>
            <a href="{{ route('lesson-schedules.assign-students', $lessonSchedule->id) }}" class="btn btn-sm btn-warning shadow-sm">
                <i class="fas fa-user-plus fa-sm text-white-50"></i> Manage Students
            </a>
            <a href="{{ route('lesson-attendances.take', $lessonSchedule->id) }}" class="btn btn-sm btn-success shadow-sm">
                <i class="fas fa-clipboard-check fa-sm text-white-50"></i> Take Attendance
            </a>
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
                    <p><strong>Start Date:</strong> {{ $lessonSchedule->start_date->format('d M Y') }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>End Date:</strong> {{ $lessonSchedule->end_date ? $lessonSchedule->end_date->format('d M Y') : 'Ongoing' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Enrolled Students ({{ $lessonSchedule->students->count() }})</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-download fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="exportDropdown">
                    <div class="dropdown-header">Export Options:</div>
                    <a class="dropdown-item" href="{{ route('lesson-schedules.export-students', ['lessonSchedule' => $lessonSchedule->id, 'format' => 'csv']) }}">
                        <i class="fas fa-file-csv fa-sm fa-fw mr-2 text-gray-400"></i>
                        CSV
                    </a>
                    <a class="dropdown-item" href="{{ route('lesson-schedules.export-students', ['lessonSchedule' => $lessonSchedule->id, 'format' => 'excel']) }}">
                        <i class="fas fa-file-excel fa-sm fa-fw mr-2 text-gray-400"></i>
                        Excel
                    </a>
                    <a class="dropdown-item" href="{{ route('lesson-schedules.export-students', ['lessonSchedule' => $lessonSchedule->id, 'format' => 'pdf']) }}">
                        <i class="fas fa-file-pdf fa-sm fa-fw mr-2 text-gray-400"></i>
                        PDF
                    </a>
                </div>
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
                                <th>Attendance Stats</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lessonSchedule->students as $student)
                                @php
                                    $attendanceStats = $student->lessonAttendances()
                                        ->where('lesson_schedule_id', $lessonSchedule->id)
                                        ->selectRaw('COUNT(*) as total')
                                        ->selectRaw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present')
                                        ->selectRaw('SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent')
                                        ->selectRaw('SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late')
                                        ->first();
                                    
                                    $presentRate = $attendanceStats->total > 0 
                                        ? round(($attendanceStats->present / $attendanceStats->total) * 100) 
                                        : 0;
                                @endphp
                                <tr>
                                    <td>{{ $student->id }}</td>
                                    <td>{{ $student->user->name }}</td>
                                    <td>{{ $student->getAge() }}</td>
                                    <td>{{ $student->school_attending ?? 'N/A' }}</td>
                                    <td>{{ $student->parent_guardian_name ?? 'N/A' }}</td>
                                    <td>{{ $student->parent_guardian_phone ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-2 small">
                                                <span class="badge badge-success">P: {{ $attendanceStats->present }}</span>
                                                <span class="badge badge-danger">A: {{ $attendanceStats->absent }}</span>
                                                <span class="badge badge-warning">L: {{ $attendanceStats->late }}</span>
                                            </div>
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $presentRate }}%;" aria-valuenow="{{ $presentRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="ml-2 small">
                                                {{ $presentRate }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('students.attendance', ['student' => $student->id, 'lessonSchedule' => $lessonSchedule->id]) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-clipboard-list"></i>
                                        </a>
                                        <form action="{{ route('lesson-schedules.remove-student', ['lessonSchedule' => $lessonSchedule->id, 'student' => $student->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this student from the schedule?')">
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Student Attendance Summary -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Attendance Summary</h6>
        </div>
        <div class="card-body">
            <div class="chart-bar">
                <canvas id="studentAttendanceChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#studentsTable').DataTable();
        
        // Student Attendance Chart
        var ctx = document.getElementById("studentAttendanceChart");
        var myBarChart = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                labels: @json($lessonSchedule->students->pluck('user.name')),
                datasets: [{
                    label: "Present",
                    backgroundColor: "#1cc88a",
                    data: @json($attendanceData['present']),
                }, {
                    label: "Absent",
                    backgroundColor: "#e74a3b",
                    data: @json($attendanceData['absent']),
                }, {
                    label: "Late",
                    backgroundColor: "#f6c23e",
                    data: @json($attendanceData['late']),
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        stacked: true,
                        gridLines: {
                            display: false,
                            drawBorder: false
                        }
                    }],
                    yAxes: [{
                        stacked: true,
                        ticks: {
                            beginAtZero: true
                        }
                    }],
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: true
                }
            }
        });
    });
</script>
@endsection
