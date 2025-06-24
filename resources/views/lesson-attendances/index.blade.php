@extends('layouts.app')

@section('title', 'Lesson Attendances')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lesson Attendances</h1>
        <div>
            <a href="{{ route('lesson-attendances.daily') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-calendar-day fa-sm text-white-50"></i> Daily View
            </a>
            <a href="{{ route('lesson-attendances.weekly') }}" class="btn btn-sm btn-info shadow-sm">
                <i class="fas fa-calendar-week fa-sm text-white-50"></i> Weekly View
            </a>
            <a href="{{ route('lesson-attendances.monthly') }}" class="btn btn-sm btn-success shadow-sm">
                <i class="fas fa-calendar-alt fa-sm text-white-50"></i> Monthly View
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

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Attendances</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('lesson-attendances.index') }}" method="GET" class="mb-0">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="centre_id">Centre</label>
                        <select class="form-control" id="centre_id" name="centre_id">
                            <option value="">All Centres</option>
                            @foreach($centres as $centre)
                                <option value="{{ $centre->id }}" {{ request('centre_id') == $centre->id ? 'selected' : '' }}>
                                    {{ $centre->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="teacher_id">Teacher</label>
                        <select class="form-control" id="teacher_id" name="teacher_id">
                            <option value="">All Teachers</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="date_from">Date From</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="date_to">Date To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>Excused</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label for="student_id">Student</label>
                        <select class="form-control" id="student_id" name="student_id">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('lesson-attendances.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Attendance Summary -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Records</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $attendances->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                                Present</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $presentCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
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
                                Absent</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $absentCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
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
                                Late</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lateCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Records -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Attendance Records</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-download fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="exportDropdown">
                    <div class="dropdown-header">Export Options:</div>
                    <a class="dropdown-item" href="{{ route('lesson-attendances.export', ['format' => 'csv'] + request()->all()) }}">
                        <i class="fas fa-file-csv fa-sm fa-fw mr-2 text-gray-400"></i>
                        CSV
                    </a>
                    <a class="dropdown-item" href="{{ route('lesson-attendances.export', ['format' => 'excel'] + request()->all()) }}">
                        <i class="fas fa-file-excel fa-sm fa-fw mr-2 text-gray-400"></i>
                        Excel
                    </a>
                    <a class="dropdown-item" href="{{ route('lesson-attendances.export', ['format' => 'pdf'] + request()->all()) }}">
                        <i class="fas fa-file-pdf fa-sm fa-fw mr-2 text-gray-400"></i>
                        PDF
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="attendanceTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Centre</th>
                            <th>Teacher</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->date->format('d M Y') }}</td>
                                <td>{{ $attendance->student->user->name }}</td>
                                <td>{{ $attendance->lessonSchedule->centre->name }}</td>
                                <td>{{ $attendance->lessonSchedule->teacher->user->name ?? 'N/A' }}</td>
                                <td>{{ $attendance->lessonSchedule->lessonSection->start_time }} - {{ $attendance->lessonSchedule->lessonSection->end_time }}</td>
                                <td>
                                    <span class="badge badge-{{ 
                                        $attendance->status == 'present' ? 'success' : 
                                        ($attendance->status == 'absent' ? 'danger' : 
                                        ($attendance->status == 'late' ? 'warning' : 'secondary')) 
                                    }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                                <td>{{ $attendance->notes ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('lesson-attendances.take', ['lessonSchedule' => $attendance->lesson_schedule_id, 'date' => $attendance->date->format('Y-m-d')]) }}" class="btn btn-primary btn-sm">
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
            
            <div class="mt-3">
                {{ $attendances->appends(request()->query())->links() }}
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
            "searching": false
        });
        
        // Initialize select2 for better dropdown experience
        $('#student_id, #teacher_id').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });
</script>
@endsection
