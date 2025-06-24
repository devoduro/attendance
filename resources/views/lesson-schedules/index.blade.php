@extends('layouts.app')

@section('title', 'Lesson Schedules Management')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lesson Schedules Management</h1>
        <a href="{{ route('lesson-schedules.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Lesson Schedule
        </a>
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
            <h6 class="m-0 font-weight-bold text-primary">Filter Lesson Schedules</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('lesson-schedules.index') }}" method="GET" class="form-inline">
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
                    <label for="day_of_week" class="sr-only">Day</label>
                    <select class="form-control" id="day_of_week" name="day_of_week">
                        <option value="">All Days</option>
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <option value="{{ $day }}" {{ request('day_of_week') == $day ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-2 mr-2">
                    <label for="teacher_id" class="sr-only">Teacher</label>
                    <select class="form-control" id="teacher_id" name="teacher_id">
                        <option value="">All Teachers</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-2 mr-2">
                    <label for="status" class="sr-only">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary mb-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                
                <a href="{{ route('lesson-schedules.index') }}" class="btn btn-secondary mb-2 ml-2">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">All Lesson Schedules</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Centre</th>
                            <th>Day</th>
                            <th>Section</th>
                            <th>Teacher</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lessonSchedules as $schedule)
                            <tr>
                                <td>{{ $schedule->id }}</td>
                                <td>{{ $schedule->centre->name }}</td>
                                <td>{{ $schedule->day_of_week }}</td>
                                <td>{{ $schedule->lessonSection->name }} ({{ $schedule->lessonSection->start_time }} - {{ $schedule->lessonSection->end_time }})</td>
                                <td>{{ $schedule->teacher->user->name ?? 'N/A' }}</td>
                                <td>{{ $schedule->start_date->format('d M Y') }}</td>
                                <td>{{ $schedule->end_date ? $schedule->end_date->format('d M Y') : 'Ongoing' }}</td>
                                <td>
                                    <span class="badge badge-{{ $schedule->is_active ? 'success' : 'danger' }}">
                                        {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('lesson-schedules.show', $schedule->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('lesson-schedules.edit', $schedule->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('lesson-schedules.assign-students', $schedule->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-user-plus"></i>
                                    </a>
                                    <a href="{{ route('lesson-attendances.take', $schedule->id) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-clipboard-check"></i>
                                    </a>
                                    <form action="{{ route('lesson-schedules.destroy', $schedule->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this lesson schedule?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
                {{ $lessonSchedules->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": false
        });
    });
</script>
@endsection
