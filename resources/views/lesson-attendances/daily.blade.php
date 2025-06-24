@extends('layouts.app')

@section('title', 'Daily Attendance')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daily Attendance</h1>
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

    <!-- Date and Filter Selection -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Select Date and Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('lesson-attendances.daily') }}" method="GET" class="form-inline">
                <div class="form-group mb-2 mr-2">
                    <label for="date" class="sr-only">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}">
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
                
                <a href="{{ route('lesson-attendances.daily') }}" class="btn btn-secondary mb-2 ml-2">
                    <i class="fas fa-sync"></i> Reset
                </a>
                
                <a href="{{ route('lesson-attendances.daily', ['date' => $selectedDate->copy()->subDay()->format('Y-m-d'), 'centre_id' => request('centre_id')]) }}" class="btn btn-info mb-2 ml-2">
                    <i class="fas fa-arrow-left"></i> Previous Day
                </a>
                
                <a href="{{ route('lesson-attendances.daily', ['date' => $selectedDate->copy()->addDay()->format('Y-m-d'), 'centre_id' => request('centre_id')]) }}" class="btn btn-info mb-2 ml-2">
                    <i class="fas fa-arrow-right"></i> Next Day
                </a>
                
                <a href="{{ route('lesson-attendances.daily', ['date' => now()->format('Y-m-d'), 'centre_id' => request('centre_id')]) }}" class="btn btn-warning mb-2 ml-2">
                    <i class="fas fa-calendar-day"></i> Today
                </a>
            </form>
        </div>
    </div>

    <!-- Daily Attendance Summary -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Lessons</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lessonSchedules->count() }}</div>
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

    <!-- Lesson Schedules for the day -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lesson Schedules for {{ $selectedDate->format('l, d M Y') }}</h6>
        </div>
        <div class="card-body">
            @if($lessonSchedules->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-4x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No lesson schedules found for this day.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="schedulesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Centre</th>
                                <th>Time</th>
                                <th>Teacher</th>
                                <th>Students</th>
                                <th>Attendance Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lessonSchedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->centre->name }}</td>
                                    <td>{{ $schedule->lessonSection->start_time }} - {{ $schedule->lessonSection->end_time }}</td>
                                    <td>{{ $schedule->teacher->user->name ?? 'N/A' }}</td>
                                    <td>{{ $schedule->students->count() }}</td>
                                    <td>
                                        @php
                                            $attendanceCount = $schedule->lessonAttendances->where('date', $selectedDate->format('Y-m-d'))->count();
                                            $studentCount = $schedule->students->count();
                                            $attendanceStatus = $studentCount > 0 ? ($attendanceCount / $studentCount) * 100 : 0;
                                        @endphp
                                        
                                        @if($attendanceStatus == 0)
                                            <span class="badge badge-danger">Not Taken</span>
                                        @elseif($attendanceStatus < 100)
                                            <span class="badge badge-warning">Partial ({{ round($attendanceStatus) }}%)</span>
                                        @else
                                            <span class="badge badge-success">Complete</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('lesson-attendances.take', ['lessonSchedule' => $schedule->id, 'date' => $selectedDate->format('Y-m-d')]) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-clipboard-check"></i> Take Attendance
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
        $('#schedulesTable').DataTable();
        
        // Auto-submit form when date or centre changes
        $('#date, #centre_id').change(function() {
            $(this).closest('form').submit();
        });
    });
</script>
@endsection
