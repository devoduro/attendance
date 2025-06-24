@extends('layouts.app')

@section('title', 'Attendance Reports')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Attendance Reports</h1>
        <div>
            <a href="{{ route('lesson-attendances.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-list fa-sm text-white-50"></i> All Attendances
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

    <!-- Report Types -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Daily Reports</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">View attendance for a specific day</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('lesson-attendances.daily') }}" class="btn btn-primary btn-sm btn-block">
                            <i class="fas fa-arrow-right"></i> View Daily Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Weekly Reports</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">View attendance for a week</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('lesson-attendances.weekly') }}" class="btn btn-success btn-sm btn-block">
                            <i class="fas fa-arrow-right"></i> View Weekly Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Monthly Reports</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">View attendance for a month</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('lesson-attendances.monthly') }}" class="btn btn-info btn-sm btn-block">
                            <i class="fas fa-arrow-right"></i> View Monthly Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Student Reports</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">View attendance by student</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('students.index', ['report' => 'attendance']) }}" class="btn btn-warning btn-sm btn-block">
                            <i class="fas fa-arrow-right"></i> View Student Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Teacher Reports</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">View attendance by teacher</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('teachers.index', ['report' => 'attendance']) }}" class="btn btn-danger btn-sm btn-block">
                            <i class="fas fa-arrow-right"></i> View Teacher Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Centre Reports</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">View attendance by centre</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('centres.index', ['report' => 'attendance']) }}" class="btn btn-secondary btn-sm btn-block">
                            <i class="fas fa-arrow-right"></i> View Centre Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Report Generator -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Custom Report Generator</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('lesson-attendances.custom-report') }}" method="GET">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="date_from">Date Range</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') ?? now()->subDays(30)->format('Y-m-d') }}">
                            <div class="input-group-append input-group-prepend">
                                <span class="input-group-text">to</span>
                            </div>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') ?? now()->format('Y-m-d') }}">
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="centre_id">Centre</label>
                        <select class="form-control select2" id="centre_id" name="centre_id">
                            <option value="">All Centres</option>
                            @foreach($centres as $centre)
                                <option value="{{ $centre->id }}" {{ request('centre_id') == $centre->id ? 'selected' : '' }}>
                                    {{ $centre->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="teacher_id">Teacher</label>
                        <select class="form-control select2" id="teacher_id" name="teacher_id">
                            <option value="">All Teachers</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="student_id">Student</label>
                        <select class="form-control select2" id="student_id" name="student_id">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>Excused</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="group_by">Group By</label>
                        <select class="form-control" id="group_by" name="group_by">
                            <option value="date" {{ request('group_by') == 'date' ? 'selected' : '' }}>Date</option>
                            <option value="student" {{ request('group_by') == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="teacher" {{ request('group_by') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="centre" {{ request('group_by') == 'centre' ? 'selected' : '' }}>Centre</option>
                            <option value="status" {{ request('group_by') == 'status' ? 'selected' : '' }}>Status</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="chart_type">Chart Type</label>
                        <select class="form-control" id="chart_type" name="chart_type">
                            <option value="bar" {{ request('chart_type') == 'bar' ? 'selected' : '' }}>Bar Chart</option>
                            <option value="line" {{ request('chart_type') == 'line' ? 'selected' : '' }}>Line Chart</option>
                            <option value="pie" {{ request('chart_type') == 'pie' ? 'selected' : '' }}>Pie Chart</option>
                            <option value="doughnut" {{ request('chart_type') == 'doughnut' ? 'selected' : '' }}>Doughnut Chart</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="export_format">Export Format</label>
                        <select class="form-control" id="export_format" name="export_format">
                            <option value="">View in Browser</option>
                            <option value="pdf" {{ request('export_format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                            <option value="excel" {{ request('export_format') == 'excel' ? 'selected' : '' }}>Excel</option>
                            <option value="csv" {{ request('export_format') == 'csv' ? 'selected' : '' }}>CSV</option>
                        </select>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-chart-bar"></i> Generate Report
                    </button>
                    <a href="{{ route('lesson-attendances.reports') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-sync"></i> Reset Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if(isset($reportData))
    <!-- Report Results -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Report Results</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-download fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="exportDropdown">
                    <div class="dropdown-header">Export Options:</div>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export_format' => 'csv']) }}">
                        <i class="fas fa-file-csv fa-sm fa-fw mr-2 text-gray-400"></i>
                        CSV
                    </a>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export_format' => 'excel']) }}">
                        <i class="fas fa-file-excel fa-sm fa-fw mr-2 text-gray-400"></i>
                        Excel
                    </a>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export_format' => 'pdf']) }}">
                        <i class="fas fa-file-pdf fa-sm fa-fw mr-2 text-gray-400"></i>
                        PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="chart-container" style="position: relative; height:400px;">
                <canvas id="reportChart"></canvas>
            </div>
            
            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="reportTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            @foreach($reportData['headers'] as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['rows'] as $row)
                            <tr>
                                @foreach($row as $cell)
                                    <td>{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true
        });
        
        $('#reportTable').DataTable();
        
        @if(isset($reportData))
        // Report Chart
        var ctx = document.getElementById("reportChart");
        var myChart = new Chart(ctx, {
            type: '{{ request('chart_type', 'bar') }}',
            data: {
                labels: @json($reportData['chart']['labels']),
                datasets: @json($reportData['chart']['datasets'])
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                scales: {
                    @if(request('chart_type', 'bar') != 'pie' && request('chart_type', 'bar') != 'doughnut')
                    xAxes: [{
                        gridLines: {
                            display: true
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: true
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                    @endif
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        });
        @endif
    });
</script>
@endsection
