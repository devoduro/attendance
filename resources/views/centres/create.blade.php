@extends('layouts.app')

@section('title', 'Add New Centre')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-plus-circle mr-3 text-blue-600"></i> Add New Centre
            </h1>
            <p class="text-gray-500 mt-1">Create a new educational centre in the system</p>
        </div>
        <a href="{{ route('centres.index') }}" class="inline-flex items-center px-4 py-2 border border-blue-500 text-blue-700 bg-white rounded-md text-sm font-medium shadow-sm hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Back to Centres
        </a>
    </div>

    <!-- Alerts -->
    @if(session('error'))
        <div class="relative px-4 py-3 mb-6 leading-normal text-red-700 bg-red-100 rounded-lg shadow-sm" role="alert">
            <span class="absolute inset-y-0 left-0 flex items-center ml-4">
                <i class="fas fa-exclamation-circle"></i>
            </span>
            <div class="ml-6">{{ session('error') }}</div>
            <button type="button" class="absolute top-0 right-0 mt-3 mr-4 text-red-700" onclick="this.parentElement.remove()">
                <span class="text-xl">&times;</span>
            </button>
        </div>
    @endif

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow border-l-4 border-blue-500 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-6 py-4 border-b bg-blue-50">
                <h2 class="text-lg font-bold text-blue-700 flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> New Centre Details
                </h2>
                <span class="ml-0 sm:ml-4 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 flex items-center">
                    <i class="fas fa-info-circle mr-1"></i> All fields marked with <span class="text-red-600 font-bold mx-1">*</span> are required
                </span>
            </div>
            <div class="px-6 py-8">
                <form action="{{ route('centres.store') }}" method="POST" id="centreForm" novalidate>
                    @csrf
                    <!-- Progress indicator -->
                    <div class="w-full bg-gray-200 rounded-full h-3 mb-8">
                        <div id="formProgress" class="bg-blue-500 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>

                    <!-- Form sections -->
                    <div class="mb-8 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="flex items-center px-4 py-3 border-b border-blue-100 bg-blue-100 rounded-t-lg">
                            <i class="fas fa-building mr-2 text-blue-500"></i>
                            <span class="font-semibold text-blue-700">Basic Information</span>
                        </div>
                        <div class="p-6">
                            <div class="mb-6">
                                <label for="name" class="block font-bold text-gray-700 mb-1">Centre Name <span class="text-red-600">*</span></label>
                                <input type="text" class="block w-full px-4 py-2 border border-gray-300 rounded-md text-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Enter centre name">
                                <small class="text-gray-400">Enter a unique name for this centre</small>
                                @error('name')
                                    <span class="text-red-600 text-sm mt-1 block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-6">
                                <label for="location" class="block font-bold text-gray-700 mb-1">Location <span class="text-red-600">*</span></label>
                                <input type="text" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location') border-red-500 @enderror" id="location" name="location" value="{{ old('location') }}" required placeholder="City, State or Region">
                                <small class="text-gray-400">City, state or region where the centre is located</small>
                                @error('location')
                                    <span class="text-red-600 text-sm mt-1 block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-6">
                                <label for="address" class="block font-bold text-gray-700 mb-1">Full Address</label>
                                <textarea class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror" id="address" name="address" rows="3" placeholder="Enter complete address">{{ old('address') }}</textarea>
                                <small class="text-gray-400">Complete street address including postal code</small>
                                @error('address')
                                    <span class="text-red-600 text-sm mt-1 block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 bg-cyan-50 rounded-lg border border-cyan-100">
                        <div class="flex items-center px-4 py-3 border-b border-cyan-100 bg-cyan-100 rounded-t-lg">
                            <i class="fas fa-phone-alt mr-2 text-cyan-500"></i>
                            <span class="font-semibold text-cyan-700">Contact Information</span>
                        </div>
                        <div class="p-6">
                            <div class="mb-6">
                                <label for="contact_number" class="block font-bold text-gray-700 mb-1">Contact Number</label>
                                <div class="relative mt-1">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="text" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('contact_number') border-red-500 @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" placeholder="Enter contact number">
                                </div>
                                <small class="text-gray-400">Primary contact number for this centre</small>
                                @error('contact_number')
                                    <span class="text-red-600 text-sm mt-1 block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-6">
                                <label for="email" class="block font-bold text-gray-700 mb-1">Email Address</label>
                                <div class="relative mt-1">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 @error('email') border-red-500 @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email address">
                                </div>
                                <small class="text-gray-400">Official email address for this centre</small>
                                @error('email')
                                    <span class="text-red-600 text-sm mt-1 block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 bg-green-50 rounded-lg border border-green-100">
                        <div class="flex items-center px-4 py-3 border-b border-green-100 bg-green-100 rounded-t-lg">
                            <i class="fas fa-toggle-on mr-2 text-green-500"></i>
                            <span class="font-semibold text-green-700">Status Settings</span>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center">
                                <input type="checkbox" class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded mr-3" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                <label for="is_active" class="font-bold text-gray-700 select-none">Centre is Active</label>
                            </div>
                            <small class="text-gray-400 mt-2 block">Inactive centres won't appear in active lists and cannot be assigned to students or lessons</small>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:justify-between gap-4 mt-8">
                        <a href="{{ route('centres.index') }}" class="inline-flex items-center px-6 py-2 border border-gray-300 text-gray-700 bg-white rounded-md text-base font-medium shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            <i class="fas fa-arrow-left mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                            <i class="fas fa-save mr-2"></i>Create Centre
                        </button>
                    </div>
                </form>
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
        $('#centreForm').on('submit', function(e) {
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
                    title: 'Creating Centre...',
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
