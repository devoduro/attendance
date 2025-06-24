@extends('layouts.app')

@section('title', 'Weekly Attendance')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Weekly Attendance</h1>
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

    <!-- Week and Filter Selection -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Select Week and Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('lesson-attendances.weekly') }}" method="GET" class="form-inline">
                <div class="form-group mb-2 mr-2">
                    <label for="week_start" class="sr-only">Week Starting</label>
                    <input type="date" class="form-control" id="week_start" name="week_start" value="{{ $weekStart->format('Y-m-d') }}">
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
                
                <button type="submit" class="btn btn-primary mb-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                
                <a href="{{ route('lesson-attendances.weekly') }}" class="btn btn-secondary mb-2 ml-2">
                    <i class="fas fa-sync"></i> Reset
                </a>
                
                <a href="{{ route('lesson-attendances.weekly', ['week_start' => $weekStart->copy()->subWeek()->format('Y-m-d'), 'centre_id' => request('centre_id')]) }}" class="btn btn-info mb-2 ml-2">
                    <i class="fas fa-arrow-left"></i> Previous Week
                </a>
                
                <a href="{{ route('lesson-attendances.weekly', ['week_start' => $weekStart->copy()->addWeek()->format('Y-m-d'), 'centre_id' => request('centre_id')]) }}" class="btn btn-info mb-2 ml-2">
                    <i class="fas fa-arrow-right"></i> Next Week
                </a>
                
                <a href="{{ route('lesson-attendances.weekly', ['week_start' => now()->startOfWeek()->format('Y-m-d'), 'centre_id' => request('centre_id')]) }}" class="btn btn-warning mb-2 ml-2">
                    <i class="fas fa-calendar-week"></i> Current Week
                </a>
            </form>
        </div>
    </div>

    <!-- Weekly Attendance Summary -->
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
                                Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalStudents }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Attendance Taken</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $attendanceTakenPercentage }}%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $attendanceTakenPercentage }}%" aria-valuenow="{{ $attendanceTakenPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
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
    </div>

    <!-- Weekly Attendance Chart -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Weekly Attendance Chart</h6>
        </div>
        <div class="card-body">
            <div class="chart-bar">
                <canvas id="weeklyAttendanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Weekly Attendance Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Weekly Attendance: {{ $weekStart->format('d M Y') }} - {{ $weekEnd->format('d M Y') }}</h6>
        </div>
        <div class="card-body">
            @if($weeklyData->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-4x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No attendance data found for this week.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="weeklyTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Date</th>
                                <th>Lessons</th>
                                <th>Students</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Late</th>
                                <th>Excused</th>
                                <th>Present Rate</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($weeklyData as $day => $data)
                                <tr>
                                    <td>{{ $day }}</td>
                                    <td>{{ $data['date']->format('d M Y') }}</td>
                                    <td>{{ $data['lessons'] }}</td>
                                    <td>{{ $data['students'] }}</td>
                                    <td>{{ $data['present'] }}</td>
                                    <td>{{ $data['absent'] }}</td>
                                    <td>{{ $data['late'] }}</td>
                                    <td>{{ $data['excused'] }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $data['present_rate'] }}%;" aria-valuenow="{{ $data['present_rate'] }}" aria-valuemin="0" aria-valuemax="100">{{ $data['present_rate'] }}%</div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('lesson-attendances.daily', ['date' => $data['date']->format('Y-m-d'), 'centre_id' => request('centre_id')]) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> View Day
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
        $('#weeklyTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false
        });
        
        // Auto-submit form when week_start or centre changes
        $('#week_start, #centre_id').change(function() {
            $(this).closest('form').submit();
        });
        
        // Weekly Attendance Chart
        var ctx = document.getElementById("weeklyAttendanceChart");
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($weeklyData->keys()),
                datasets: [{
                    label: "Present",
                    backgroundColor: "#1cc88a",
                    data: @json($weeklyData->pluck('present')),
                }, {
                    label: "Absent",
                    backgroundColor: "#e74a3b",
                    data: @json($weeklyData->pluck('absent')),
                }, {
                    label: "Late",
                    backgroundColor: "#f6c23e",
                    data: @json($weeklyData->pluck('late')),
                }, {
                    label: "Excused",
                    backgroundColor: "#858796",
                    data: @json($weeklyData->pluck('excused')),
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
