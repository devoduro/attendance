@extends('layouts.app')

@section('title', 'Edit Lesson Schedule')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Lesson Schedule</h1>
        <a href="{{ route('lesson-schedules.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Lesson Schedules
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lesson Schedule Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('lesson-schedules.update', $lessonSchedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group row">
                    <label for="centre_id" class="col-sm-2 col-form-label">Centre <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control @error('centre_id') is-invalid @enderror" id="centre_id" name="centre_id" required>
                            <option value="">Select Centre</option>
                            @foreach($centres as $centre)
                                <option value="{{ $centre->id }}" {{ old('centre_id', $lessonSchedule->centre_id) == $centre->id ? 'selected' : '' }}>
                                    {{ $centre->name }} ({{ $centre->location }})
                                </option>
                            @endforeach
                        </select>
                        @error('centre_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="lesson_section_id" class="col-sm-2 col-form-label">Lesson Section <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control @error('lesson_section_id') is-invalid @enderror" id="lesson_section_id" name="lesson_section_id" required>
                            <option value="">Select Lesson Section</option>
                            @foreach($lessonSections as $section)
                                <option value="{{ $section->id }}" {{ old('lesson_section_id', $lessonSchedule->lesson_section_id) == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }} ({{ $section->start_time }} - {{ $section->end_time }})
                                </option>
                            @endforeach
                        </select>
                        @error('lesson_section_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="teacher_id" class="col-sm-2 col-form-label">Teacher <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control @error('teacher_id') is-invalid @enderror" id="teacher_id" name="teacher_id" required>
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $lessonSchedule->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="day_of_week" class="col-sm-2 col-form-label">Day of Week <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <select class="form-control @error('day_of_week') is-invalid @enderror" id="day_of_week" name="day_of_week" required>
                            <option value="">Select Day</option>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                <option value="{{ $day }}" {{ old('day_of_week', $lessonSchedule->day_of_week) == $day ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                        @error('day_of_week')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="start_date" class="col-sm-2 col-form-label">Start Date <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $lessonSchedule->start_date->format('Y-m-d')) }}" required>
                        @error('start_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="end_date" class="col-sm-2 col-form-label">End Date</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $lessonSchedule->end_date ? $lessonSchedule->end_date->format('Y-m-d') : '') }}">
                        <small class="form-text text-muted">Leave blank if the schedule is ongoing</small>
                        @error('end_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="notes" class="col-sm-2 col-form-label">Notes</label>
                    <div class="col-sm-10">
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $lessonSchedule->notes) }}</textarea>
                        @error('notes')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="is_active" class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-10">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $lessonSchedule->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Lesson Schedule
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Validate that end date is after start date if provided
        $('form').on('submit', function(e) {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();
            
            if (endDate && startDate >= endDate) {
                e.preventDefault();
                alert('End date must be after start date');
                return false;
            }
        });
    });
</script>
@endsection
