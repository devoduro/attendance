@extends('layouts.app')

@section('title', 'Monthly Attendance')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Monthly Attendance</h1>
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

    <!-- Month and Filter Selection -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Select Month and Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('lesson-attendances.monthly') }}" method="GET" class="form-inline">
                <div class="form-group mb-2 mr-2">
                    <label for="month" class="sr-only">Month</label>
                    <input type="month" class="form-control" id="month" name="month" value="{{ $selectedMonth->format('Y-m') }}">
                </div>
                
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
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary mb-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                
                <a href="{{ route('lesson-attendances.monthly') }}" class="btn btn-secondary mb-2 ml-2">
                    <i class="fas fa-sync"></i> Reset
                </a>
                
                <a href="{{ route('lesson-attendances.monthly', ['month' => $selectedMonth->copy()->subMonth()->format('Y-m'), 'centre_id' => request('centre_id')]) }}" class="btn btn-info mb-2 ml-2">
                    <i class="fas fa-arrow-left"></i> Previous Month
                </a>
                
                <a href="{{ route('lesson-attendances.monthly', ['month' => $selectedMonth->copy()->addMonth()->format('Y-m'), 'centre_id' => request('centre_id')]) }}" class="btn btn-info mb-2 ml-2">
                    <i class="fas fa-arrow-right"></i> Next Month
                </a>
                
                <a href="{{ route('lesson-attendances.monthly', ['month' => now()->format('Y-m'), 'centre_id' => request('centre_id')]) }}" class="btn btn-warning mb-2 ml-2">
                    <i class="fas fa-calendar-alt"></i> Current Month
                </a>
            </form>
        </div>
    </div>

    <!-- Monthly Attendance Summary -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Lessons</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLessons }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Present Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $presentRate }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Absent Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $absentRate }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Attendance Completion</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $attendanceCompletionRate }}%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $attendanceCompletionRate }}%" aria-valuenow="{{ $attendanceCompletionRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Calendar View -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ $selectedMonth->format('F Y') }} Calendar</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-download fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="exportDropdown">
                    <div class="dropdown-header">Export Options:</div>
                    <a class="dropdown-item" href="{{ route('lesson-attendances.export', ['format' => 'csv', 'month' => $selectedMonth->format('Y-m')] + request()->except(['month'])) }}">
                        <i class="fas fa-file-csv fa-sm fa-fw mr-2 text-gray-400"></i>
                        CSV
                    </a>
                    <a class="dropdown-item" href="{{ route('lesson-attendances.export', ['format' => 'excel', 'month' => $selectedMonth->format('Y-m')] + request()->except(['month'])) }}">
                        <i class="fas fa-file-excel fa-sm fa-fw mr-2 text-gray-400"></i>
                        Excel
                    </a>
                    <a class="dropdown-item" href="{{ route('lesson-attendances.export', ['format' => 'pdf', 'month' => $selectedMonth->format('Y-m')] + request()->except(['month'])) }}">
                        <i class="fas fa-file-pdf fa-sm fa-fw mr-2 text-gray-400"></i>
                        PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered calendar-table">
                    <thead>
                        <tr>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                            <th>Sunday</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($calendarDays as $week)
                            <tr>
                                @foreach($week as $day)
                                    <td class="{{ !$day['isCurrentMonth'] ? 'bg-light' : '' }} {{ $day['isToday'] ? 'bg-primary text-white' : '' }}" style="height: 120px; width: 14.28%; vertical-align: top;">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="font-weight-bold">{{ $day['day'] }}</span>
                                            @if($day['isCurrentMonth'] && isset($day['stats']))
                                                <a href="{{ route('lesson-attendances.daily', ['date' => $day['date']->format('Y-m-d'), 'centre_id' => request('centre_id')]) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                        </div>
                                        
                                        @if($day['isCurrentMonth'] && isset($day['stats']))
                                            <div class="small">
                                                <div class="mb-1">
                                                    <span class="badge badge-success">{{ $day['stats']['present'] }}</span>
                                                    <span class="badge badge-danger">{{ $day['stats']['absent'] }}</span>
                                                    <span class="badge badge-warning">{{ $day['stats']['late'] }}</span>
                                                </div>
                                                <div>
                                                    <i class="fas fa-chalkboard-teacher text-info"></i> {{ $day['stats']['lessons'] }}
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Monthly Attendance by Centre -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Attendance by Centre</h6>
        </div>
        <div class="card-body">
            <div class="chart-bar">
                <canvas id="centreAttendanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Monthly Attendance by Day -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daily Attendance Summary</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dailySummaryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Lessons</th>
                            <th>Students</th>
                            <th>Present</th>
                            <th>Absent</th>
                            <th>Late</th>
                            <th>Present Rate</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailySummary as $date => $summary)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($date)->format('l') }}</td>
                                <td>{{ $summary['lessons'] }}</td>
                                <td>{{ $summary['students'] }}</td>
                                <td>{{ $summary['present'] }}</td>
                                <td>{{ $summary['absent'] }}</td>
                                <td>{{ $summary['late'] }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $summary['present_rate'] }}%;" aria-valuenow="{{ $summary['present_rate'] }}" aria-valuemin="0" aria-valuemax="100">{{ $summary['present_rate'] }}%</div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('lesson-attendances.daily', ['date' => $date, 'centre_id' => request('centre_id')]) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .calendar-table th {
        text-align: center;
        background-color: #f8f9fc;
    }
    
    .calendar-table td {
        border: 1px solid #e3e6f0;
        padding: 8px;
    }
</style>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Auto-submit form when month or centre changes
        $('#month, #centre_id').change(function() {
            $(this).closest('form').submit();
        });
        
        // Initialize DataTable
        $('#dailySummaryTable').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": false
        });
        
        // Centre Attendance Chart
        var centreCtx = document.getElementById("centreAttendanceChart");
        var centreChart = new Chart(centreCtx, {
            type: 'bar',
            data: {
                labels: @json($centreStats->pluck('name')),
                datasets: [{
                    label: "Present",
                    backgroundColor: "#1cc88a",
                    data: @json($centreStats->pluck('present')),
                }, {
                    label: "Absent",
                    backgroundColor: "#e74a3b",
                    data: @json($centreStats->pluck('absent')),
                }, {
                    label: "Late",
                    backgroundColor: "#f6c23e",
                    data: @json($centreStats->pluck('late')),
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
