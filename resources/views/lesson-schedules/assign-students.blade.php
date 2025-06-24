@extends('layouts.app')

@section('title', 'Assign Students to Lesson Schedule')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Assign Students to Lesson Schedule</h1>
        <a href="{{ route('lesson-schedules.show', $lessonSchedule->id) }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Schedule
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Schedule Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Centre:</strong> {{ $lessonSchedule->centre->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Day:</strong> {{ $lessonSchedule->day_of_week }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Time:</strong> {{ $lessonSchedule->lessonSection->start_time }} - {{ $lessonSchedule->lessonSection->end_time }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Teacher:</strong> {{ $lessonSchedule->teacher->user->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Start Date:</strong> {{ $lessonSchedule->start_date->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>End Date:</strong> {{ $lessonSchedule->end_date ? $lessonSchedule->end_date->format('d M Y') : 'Ongoing' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Assign Students</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('lesson-schedules.assign-students.store', $lessonSchedule->id) }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="search">Search Students</label>
                            <input type="text" class="form-control" id="search" placeholder="Type to search students...">
                        </div>
                        
                        <div class="form-group">
                            <label>Filter by Centre</label>
                            <select class="form-control" id="centre-filter">
                                <option value="">All Centres</option>
                                @foreach($centres as $centre)
                                    <option value="{{ $centre->id }}" {{ $centre->id == $lessonSchedule->centre_id ? 'selected' : '' }}>
                                        {{ $centre->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered" id="studentsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="selectAll">
                                                <label class="custom-control-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Centre</th>
                                        <th>School</th>
                                        <th>Parent/Guardian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr class="student-row" data-centre="{{ $student->centre_id }}">
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input student-checkbox" 
                                                        id="student{{ $student->id }}" 
                                                        name="student_ids[]" 
                                                        value="{{ $student->id }}"
                                                        {{ in_array($student->id, $enrolledStudentIds) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="student{{ $student->id }}"></label>
                                                </div>
                                            </td>
                                            <td>{{ $student->id }}</td>
                                            <td>{{ $student->user->name }}</td>
                                            <td>{{ $student->getAge() }}</td>
                                            <td>{{ $student->centre->name ?? 'N/A' }}</td>
                                            <td>{{ $student->school_attending ?? 'N/A' }}</td>
                                            <td>{{ $student->parent_guardian_name ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Assignments
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#studentsTable').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": false
        });
        
        // Handle select all checkbox
        $('#selectAll').on('change', function() {
            $('.student-checkbox:visible').prop('checked', $(this).prop('checked'));
        });
        
        // Handle search
        $('#search').on('keyup', function() {
            table.search(this.value).draw();
        });
        
        // Handle centre filter
        $('#centre-filter').on('change', function() {
            const centreId = $(this).val();
            
            if (centreId) {
                // Show only rows with matching centre ID
                $('.student-row').hide();
                $('.student-row[data-centre="' + centreId + '"]').show();
            } else {
                // Show all rows
                $('.student-row').show();
            }
            
            // Update "Select All" checkbox state
            updateSelectAllState();
        });
        
        // Update "Select All" checkbox state based on visible checkboxes
        function updateSelectAllState() {
            const visibleCheckboxes = $('.student-checkbox:visible');
            const checkedCheckboxes = $('.student-checkbox:visible:checked');
            
            $('#selectAll').prop('checked', 
                visibleCheckboxes.length > 0 && 
                visibleCheckboxes.length === checkedCheckboxes.length
            );
        }
        
        // Initial filter by centre if default is selected
        $('#centre-filter').trigger('change');
    });
</script>
@endsection
