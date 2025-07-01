@extends('layouts.app')

@section('title', 'Teacher Attendance Report')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Teacher Attendance Report</h1>
        <div>
            <a href="{{ route('teachers.show', $teacher->id) }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Teacher
            </a>
            <div class="btn-group ml-2">
                <button type="button" class="btn btn-sm btn-primary shadow-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-download fa-sm text-white-50"></i> Export
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('teachers.attendance.export', ['teacher' => $teacher->id, 'format' => 'pdf']) }}">PDF</a>
                    <a class="dropdown-item" href="{{ route('teachers.attendance.export', ['teacher' => $teacher->id, 'format' => 'excel']) }}">Excel</a>
                    <a class="dropdown-item" href="{{ route('teachers.attendance.export', ['teacher' => $teacher->id, 'format' => 'csv']) }}">CSV</a>
                </div>
            </div>
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

    <!-- Teacher Information -->
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Teacher Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img class="img-profile rounded-circle" width="100" height="100" src="{{ $teacher->user->profile_photo_url ?? asset('img/undraw_profile.svg') }}">
                    </div>
                    <h4 class="text-center mb-3">{{ $teacher->user->name }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $teacher->id }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $teacher->user->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $teacher->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Qualification</th>
                                <td>{{ $teacher->qualification ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Joined Date</th>
                                <td>{{ $teacher->created_at->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge badge-{{ $teacher->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($teacher->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lesson Schedule Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Classes</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $scheduleSummary['total'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Students Taught</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $scheduleSummary['students'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Attendance Rate</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $scheduleSummary['attendance_rate'] }}%</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $scheduleSummary['attendance_rate'] }}%" aria-valuenow="{{ $scheduleSummary['attendance_rate'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
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

                    <div class="chart-bar pt-4">
                        <canvas id="teacherClassesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Options</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('teachers.attendance', $teacher->id) }}" method="GET" class="form-inline">
                <div class="form-group mb-2 mr-2">
                    <label for="centre_id" class="sr-only">Centre</label>
                    <select class="form-control" id="centre_id" name="centre_id">
                        <option value="">All Centres</option>
                        @foreach($centres as $centre)
                            <option value="{{ $centre->id }}" {{ request('centre_id') == $centre->id ? 'selected' : '' }}>
                                {{ $centre->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-2 mr-2">
                    <label for="subject_id" class="sr-only">Subject</label>
                    <select class="form-control" id="subject_id" name="subject_id">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $id => $name)
                            <option value="{{ $id }}" {{ request('subject_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-2 mr-2">
                    <label for="date_from" class="sr-only">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}" placeholder="Date From">
                </div>
                
                <div class="form-group mb-2 mr-2">
                    <label for="date_to" class="sr-only">Date To</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}" placeholder="Date To">
                </div>
                
                <button type="submit" class="btn btn-primary mb-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                
                <a href="{{ route('teachers.attendance', $teacher->id) }}" class="btn btn-secondary mb-2 ml-2">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Lesson Schedules -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lesson Schedules</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="schedulesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Centre</th>
                            <th>Subject</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Students</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lessonSchedules as $schedule)
                            <tr>
                                <td>{{ $schedule->centre->name }}</td>
                                <td>{{ $schedule->subject->name ?? 'N/A' }}</td>
                                <td>{{ $schedule->day_of_week }}</td>
                                <td>{{ $schedule->lessonSection->start_time }} - {{ $schedule->lessonSection->end_time }}</td>
                                <td>{{ $schedule->students_count }}</td>
                                <td>{{ $schedule->start_date->format('d M Y') }}</td>
                                <td>{{ $schedule->end_date ? $schedule->end_date->format('d M Y') : 'Ongoing' }}</td>
                                <td>
                                    <span class="badge badge-{{ $schedule->status == 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($schedule->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('lesson-schedules.show', $schedule->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('lesson-attendances.take', $schedule->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-clipboard-check"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No lesson schedules found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $lessonSchedules->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Recent Attendance Records -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Attendance Records</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="attendanceTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Centre</th>
                            <th>Subject</th>
                            <th>Time</th>
                            <th>Students Present</th>
                            <th>Students Absent</th>
                            <th>Present Rate</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceRecords as $date => $records)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                                <td>{{ $records['centre'] }}</td>
                                <td>{{ $records['subject'] ?? 'N/A' }}</td>
                                <td>{{ $records['time'] }}</td>
                                <td>{{ $records['present'] }}</td>
                                <td>{{ $records['absent'] }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $records['present_rate'] }}%;" aria-valuenow="{{ $records['present_rate'] }}" aria-valuemin="0" aria-valuemax="100">{{ $records['present_rate'] }}%</div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('lesson-attendances.show', $records['id']) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('lesson-attendances.edit', $records['id']) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No attendance records found.</td>
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
        $('#schedulesTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false
        });
        
        $('#attendanceTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false
        });
        
        // Teacher Classes Chart
        var ctx = document.getElementById("teacherClassesChart");
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: "Classes",
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#2e59d9",
                    borderColor: "#4e73df",
                    data: @json($chartData['data']),
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
                        time: {
                            unit: 'day'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        },
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5,
                            padding: 10,
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
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
            }
        });
    });
</script>
@endsection
