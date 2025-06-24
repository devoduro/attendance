@extends('layouts.app')

@section('title', 'Student Attendance Report')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Attendance Report</h1>
        <div>
            <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Student
            </a>
            <div class="btn-group ml-2">
                <button type="button" class="btn btn-sm btn-primary shadow-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-download fa-sm text-white-50"></i> Export
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('students.attendance.export', ['student' => $student->id, 'format' => 'pdf']) }}">PDF</a>
                    <a class="dropdown-item" href="{{ route('students.attendance.export', ['student' => $student->id, 'format' => 'excel']) }}">Excel</a>
                    <a class="dropdown-item" href="{{ route('students.attendance.export', ['student' => $student->id, 'format' => 'csv']) }}">CSV</a>
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

    <!-- Student Information -->
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Student Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img class="img-profile rounded-circle" width="100" height="100" src="{{ $student->user->profile_photo_url ?? asset('img/undraw_profile.svg') }}">
                    </div>
                    <h4 class="text-center mb-3">{{ $student->user->name }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $student->id }}</td>
                            </tr>
                            <tr>
                                <th>Age</th>
                                <td>{{ $student->getAge() }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth</th>
                                <td>{{ $student->date_of_birth->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <th>School</th>
                                <td>{{ $student->school_attending ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Parent/Guardian</th>
                                <td>{{ $student->parent_guardian_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Contact</th>
                                <td>{{ $student->parent_guardian_phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $student->parent_guardian_email ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Attendance Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Classes</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $attendanceSummary['total'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Present</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $attendanceSummary['present'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Absent</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $attendanceSummary['absent'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-times fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Late</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $attendanceSummary['late'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-pie pt-4">
                                <canvas id="attendancePieChart"></canvas>
                            </div>
                            <div class="mt-4 text-center small">
                                <span class="mr-2">
                                    <i class="fas fa-circle text-success"></i> Present
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-danger"></i> Absent
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-warning"></i> Late
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-secondary"></i> Excused
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-bar">
                                <canvas id="attendanceTrendChart"></canvas>
                            </div>
                        </div>
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
            <form action="{{ route('students.attendance', $student->id) }}" method="GET" class="form-inline">
                <div class="form-group mb-2 mr-2">
                    <label for="lesson_schedule_id" class="sr-only">Lesson Schedule</label>
                    <select class="form-control" id="lesson_schedule_id" name="lesson_schedule_id">
                        <option value="">All Schedules</option>
                        @foreach($lessonSchedules as $schedule)
                            <option value="{{ $schedule->id }}" {{ request('lesson_schedule_id') == $schedule->id ? 'selected' : '' }}>
                                {{ $schedule->centre->name }} - {{ $schedule->day_of_week }} ({{ $schedule->lessonSection->start_time }})
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
                
                <div class="form-group mb-2 mr-2">
                    <label for="status" class="sr-only">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                        <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>Excused</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary mb-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                
                <a href="{{ route('students.attendance', $student->id) }}" class="btn btn-secondary mb-2 ml-2">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Attendance Records -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Attendance Records</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="attendanceTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Centre</th>
                            <th>Time</th>
                            <th>Teacher</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceRecords as $record)
                            <tr>
                                <td>{{ $record->date->format('d M Y') }}</td>
                                <td>{{ $record->date->format('l') }}</td>
                                <td>{{ $record->lessonSchedule->centre->name }}</td>
                                <td>{{ $record->lessonSchedule->lessonSection->start_time }} - {{ $record->lessonSchedule->lessonSection->end_time }}</td>
                                <td>{{ $record->lessonSchedule->teacher->user->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge-{{ 
                                        $record->status == 'present' ? 'success' : 
                                        ($record->status == 'absent' ? 'danger' : 
                                        ($record->status == 'late' ? 'warning' : 'secondary')) 
                                    }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                                <td>{{ $record->notes ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $attendanceRecords->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#attendanceTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": true
        });
        
        // Attendance Pie Chart
        var pieCtx = document.getElementById("attendancePieChart");
        var myPieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ["Present", "Absent", "Late", "Excused"],
                datasets: [{
                    data: [
                        {{ $attendanceSummary['present'] }}, 
                        {{ $attendanceSummary['absent'] }}, 
                        {{ $attendanceSummary['late'] }}, 
                        {{ $attendanceSummary['excused'] }}
                    ],
                    backgroundColor: ['#1cc88a', '#e74a3b', '#f6c23e', '#858796'],
                    hoverBackgroundColor: ['#17a673', '#c93a2c', '#dda20a', '#6e707e'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
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
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
        
        // Attendance Trend Chart
        var trendCtx = document.getElementById("attendanceTrendChart");
        var myTrendChart = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: @json($trendData['labels']),
                datasets: [{
                    label: "Attendance",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: @json($trendData['data']),
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
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return value + '%';
                            }
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
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + tooltipItem.yLabel + '%';
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
