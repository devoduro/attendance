@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Subject Details</h5>
                    <div>
                        <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('subjects.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Subjects
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="30%">ID</th>
                                <td>{{ $subject->id }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $subject->name }}</td>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <td>{{ $subject->code }}</td>
                            </tr>
                            <tr>
                                <th>Centre</th>
                                <td>{{ $subject->centre ? $subject->centre->name : 'Not Assigned' }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $subject->description ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge {{ $subject->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($subject->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $subject->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $subject->updated_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Lesson Schedules associated with this subject -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Associated Lesson Schedules</h5>
                </div>
                <div class="card-body">
                    @if($subject->lessonSchedules && $subject->lessonSchedules->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Day</th>
                                        <th>Teacher</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subject->lessonSchedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->id }}</td>
                                        <td>{{ $schedule->day_of_week }}</td>
                                        <td>{{ $schedule->teacher ? $schedule->teacher->user->name : 'Not Assigned' }}</td>
                                        <td>{{ $schedule->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $schedule->end_date ? $schedule->end_date->format('Y-m-d') : 'Ongoing' }}</td>
                                        <td>
                                            <span class="badge {{ $schedule->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No lesson schedules associated with this subject.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
