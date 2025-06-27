@extends('layouts.app')

@section('title', 'Edit Centre')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Centre</h1>
            <p class="text-muted">Update information for {{ $centre->name }}</p>
        </div>
        <div>
            <a href="{{ route('centres.show', $centre->id) }}" class="btn btn-info shadow-sm mr-2">
                <i class="fas fa-eye fa-sm mr-1"></i> View Details
            </a>
            <a href="{{ route('centres.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="fas fa-arrow-left fa-sm mr-1"></i> Back to Centres
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning"><i class="fas fa-edit mr-2"></i>Edit Centre Details</h6>
                    <span class="badge badge-light"><i class="fas fa-info-circle mr-1"></i>All fields marked with * are required</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('centres.update', $centre->id) }}" method="POST" id="editCentreForm" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-lg-8 mx-auto">
                                <!-- Progress indicator -->
                                <div class="progress mb-4" style="height: 10px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: 0%" id="formProgress"></div>
                                </div>
                                
                                <!-- Form sections -->
                                <div class="card mb-4 bg-light">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="m-0 font-weight-bold"><i class="fas fa-building mr-2"></i>Basic Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name" class="font-weight-bold">Centre Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $centre->name) }}" required placeholder="Enter centre name">
                                            <small class="form-text text-muted">Enter a unique name for this centre</small>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="location" class="font-weight-bold">Location <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $centre->location) }}" required placeholder="City, State or Region">
                                            <small class="form-text text-muted">City, state or region where the centre is located</small>
                                            @error('location')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="address" class="font-weight-bold">Full Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Enter complete address">{{ old('address', $centre->address) }}</textarea>
                                            <small class="form-text text-muted">Complete street address including postal code</small>
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mb-4 bg-light">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="m-0 font-weight-bold"><i class="fas fa-phone-alt mr-2"></i>Contact Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="contact_number" class="font-weight-bold">Contact Number</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                </div>
                                                <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number', $centre->contact_number) }}" placeholder="Enter contact number">
                                            </div>
                                            <small class="form-text text-muted">Primary contact number for this centre</small>
                                            @error('contact_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="email" class="font-weight-bold">Email Address</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $centre->email) }}" placeholder="Enter email address">
                                            </div>
                                            <small class="form-text text-muted">Official email address for this centre</small>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mb-4 bg-light">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="m-0 font-weight-bold"><i class="fas fa-toggle-on mr-2"></i>Status Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-0">
                                            <div class="custom-control custom-switch custom-switch-lg">
                                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $centre->is_active) ? 'checked' : '' }}>
                                                <label class="custom-control-label font-weight-bold" for="is_active">Centre is Active</label>
                                            </div>
                                            <small class="form-text text-muted mt-2">Inactive centres won't appear in active lists and cannot be assigned to students or lessons</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('centres.show', $centre->id) }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-warning btn-lg">
                                        <i class="fas fa-save mr-2"></i>Update Centre
                                    </button>
                                </div>
                            </div>
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
        // Progress bar functionality
        function updateProgressBar() {
            let filledFields = 0;
            let totalFields = 0;
            
            // Count required fields
            $('input[required], textarea[required], select[required]').each(function() {
                totalFields++;
                if ($(this).val().trim() !== '') {
                    filledFields++;
                }
            });
            
            // Count optional fields that are filled
            $('input:not([required]), textarea:not([required]), select:not([required])').each(function() {
                if ($(this).attr('type') !== 'checkbox' && $(this).attr('type') !== 'radio') {
                    if ($(this).val().trim() !== '') {
                        filledFields++;
                        totalFields++;
                    }
                }
            });
            
            // Calculate percentage
            const percentage = totalFields > 0 ? Math.round((filledFields / totalFields) * 100) : 0;
            $('#formProgress').css('width', percentage + '%').attr('aria-valuenow', percentage);
            
            // Change color based on percentage
            if (percentage < 30) {
                $('#formProgress').removeClass('bg-success bg-warning').addClass('bg-danger');
            } else if (percentage < 70) {
                $('#formProgress').removeClass('bg-success bg-danger').addClass('bg-warning');
            } else {
                $('#formProgress').removeClass('bg-warning bg-danger').addClass('bg-success');
            }
        }
        
        // Update progress on input change
        $('input, textarea, select').on('change keyup', function() {
            updateProgressBar();
        });
        
        // Initialize progress bar
        updateProgressBar();
        
        // Enhanced client-side validation
        $('#editCentreForm').on('submit', function(e) {
            let isValid = true;
            let firstInvalidElement = null;
            
            // Validate required fields
            $('input[required], textarea[required], select[required]').each(function() {
                if ($(this).val().trim() === '') {
                    $(this).addClass('is-invalid');
                    isValid = false;
                    if (!firstInvalidElement) {
                        firstInvalidElement = $(this);
                    }
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            
            // Validate email format if provided
            const email = $('#email').val().trim();
            if (email !== '') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    $('#email').addClass('is-invalid');
                    isValid = false;
                    if (!firstInvalidElement) {
                        firstInvalidElement = $('#email');
                    }
                } else {
                    $('#email').removeClass('is-invalid');
                }
            }
            
            // Validate phone number format if provided
            const phone = $('#contact_number').val().trim();
            if (phone !== '') {
                // Allow various phone formats with optional country codes
                const phoneRegex = /^[+]?[(]?[0-9]{1,4}[)]?[-\s.]?[0-9]{1,3}[-\s.]?[0-9]{1,4}[-\s.]?[0-9]{1,4}$/;
                if (!phoneRegex.test(phone)) {
                    $('#contact_number').addClass('is-invalid');
                    isValid = false;
                    if (!firstInvalidElement) {
                        firstInvalidElement = $('#contact_number');
                    }
                } else {
                    $('#contact_number').removeClass('is-invalid');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                
                // Scroll to the first invalid element
                if (firstInvalidElement) {
                    $('html, body').animate({
                        scrollTop: firstInvalidElement.offset().top - 100
                    }, 500);
                    firstInvalidElement.focus();
                }
                
                // Show validation message
                Swal.fire({
                    title: 'Validation Error',
                    text: 'Please check the form for errors and fill in all required fields.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            } else {
                // Show loading state
                Swal.fire({
                    title: 'Updating Centre...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
        
        // Real-time validation feedback
        $('input, textarea').on('blur', function() {
            if ($(this).attr('required') && $(this).val().trim() === '') {
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
            
            // Email validation
            if ($(this).attr('type') === 'email' && $(this).val().trim() !== '') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test($(this).val().trim())) {
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            }
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
        
        // Add custom styling for large custom switches
        $('<style>.custom-switch-lg .custom-control-label { padding-left: 15px; padding-top: 5px; } .custom-switch-lg .custom-control-label::before { width: 50px; height: 25px; border-radius: 25px; } .custom-switch-lg .custom-control-label::after { width: 21px; height: 21px; border-radius: 21px; } .custom-switch-lg .custom-control-input:checked ~ .custom-control-label::after { transform: translateX(25px); }</style>').appendTo('head');
    });
</script>
@endsection
