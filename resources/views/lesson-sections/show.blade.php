@extends('layouts.app')

@section('title', 'Lesson Section Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Lesson Section Details</h1>
        <div>
            <a href="{{ route('lesson-sections.edit', $lessonSection->id) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Lesson Section
            </a>
            <a href="{{ route('lesson-sections.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Lesson Sections
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lesson Section Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Name</th>
                            <td>{{ $lessonSection->name }}</td>
                        </tr>
                        <tr>
                            <th>Start Time</th>
                            <td>{{ $lessonSection->start_time }}</td>
                        </tr>
                        <tr>
                            <th>End Time</th>
                            <td>{{ $lessonSection->end_time }}</td>
                        </tr>
                        <tr>
                            <th>Duration</th>
                            <td>{{ $lessonSection->getDurationInMinutes() }} minutes</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $lessonSection->description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge badge-{{ $lessonSection->is_active ? 'success' : 'danger' }}">
                                    {{ $lessonSection->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $lessonSection->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $lessonSection->updated_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lesson Section Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Lesson Schedules</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $lessonSection->lessonSchedules->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                                                Active Schedules</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $lessonSection->lessonSchedules->where('is_active', true)->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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

    <!-- Lesson Schedules for this Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lesson Schedules Using This Section</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="schedulesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Centre</th>
                            <th>Day</th>
                            <th>Teacher</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lessonSection->lessonSchedules as $schedule)
                            <tr>
                                <td>{{ $schedule->centre->name }}</td>
                                <td>{{ $schedule->day_of_week }}</td>
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No lesson schedules found for this section.</td>
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
        $('#schedulesTable').DataTable();
    });
</script>
@endsection
