@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">System Settings</h1>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="border-b border-gray-200">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                <li class="mr-2">
                    <a href="#general-tab" class="inline-block p-4 border-b-2 border-blue-600 rounded-t-lg active text-blue-600" aria-current="page" id="general-tab-button">
                        General Settings
                    </a>
                </li>
                <li class="mr-2">
                    <a href="#academic-tab" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="academic-tab-button">
                        Academic Settings
                    </a>
                </li>
                <li class="mr-2">
                    <a href="#sms-tab" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="sms-tab-button">
                        SMS Settings
                    </a>
                </li>
            </ul>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- General Settings Tab -->
            <div id="general-tab" class="p-6 tab-content">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">School Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- School Name -->
                    <div>
                        <label for="general[school_name]" class="block text-sm font-medium text-gray-700 mb-1">School Name</label>
                        <input type="text" name="general[school_name]" id="general[school_name]" class="form-input w-full" 
                            value="{{ $generalSettings['school_name'] ?? '' }}">
                    </div>

                    <!-- School Address -->
                    <div>
                        <label for="general[school_address]" class="block text-sm font-medium text-gray-700 mb-1">School Address</label>
                        <input type="text" name="general[school_address]" id="general[school_address]" class="form-input w-full" 
                            value="{{ $generalSettings['school_address'] ?? '' }}">
                    </div>

                    <!-- School Phone -->
                    <div>
                        <label for="general[school_phone]" class="block text-sm font-medium text-gray-700 mb-1">School Phone</label>
                        <input type="text" name="general[school_phone]" id="general[school_phone]" class="form-input w-full" 
                            value="{{ $generalSettings['school_phone'] ?? '' }}">
                    </div>

                    <!-- School Email -->
                    <div>
                        <label for="general[school_email]" class="block text-sm font-medium text-gray-700 mb-1">School Email</label>
                        <input type="email" name="general[school_email]" id="general[school_email]" class="form-input w-full" 
                            value="{{ $generalSettings['school_email'] ?? '' }}">
                    </div>

                    <!-- School Website -->
                    <div>
                        <label for="general[school_website]" class="block text-sm font-medium text-gray-700 mb-1">School Website</label>
                        <input type="url" name="general[school_website]" id="general[school_website]" class="form-input w-full" 
                            value="{{ $generalSettings['school_website'] ?? '' }}">
                    </div>

                    <!-- School Logo -->
                    <div>
                        <label for="school_logo" class="block text-sm font-medium text-gray-700 mb-1">School Logo</label>
                        <div class="flex items-center">
                            @if(!empty($generalSettings['school_logo']))
                                <div class="mr-4">
                                    <img src="{{ Storage::url($generalSettings['school_logo']) }}" alt="School Logo" class="h-16 w-auto">
                                </div>
                            @endif
                            <input type="file" name="school_logo" id="school_logo" class="form-input">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Recommended size: 200x200px. Max 2MB. JPG, PNG, GIF formats only.</p>
                    </div>
                </div>
            </div>

            <!-- Academic Settings Tab -->
            <div id="academic-tab" class="p-6 tab-content hidden">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Academic Configuration</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Current Academic Year -->
                    <div>
                        <label for="academic[current_academic_year]" class="block text-sm font-medium text-gray-700 mb-1">Current Academic Year</label>
                        <input type="text" name="academic[current_academic_year]" id="academic[current_academic_year]" class="form-input w-full" 
                            value="{{ $academicSettings['current_academic_year'] ?? '' }}" placeholder="e.g. 2024/2025">
                        <p class="text-xs text-gray-500 mt-1">
                            <a href="{{ route('settings.academic-years') }}" class="text-blue-600 hover:underline">
                                <i class="fas fa-cog mr-1"></i> Manage Academic Years
                            </a>
                        </p>
                    </div>

                    <!-- Current Term -->
                    <div>
                        <label for="academic[current_term]" class="block text-sm font-medium text-gray-700 mb-1">Current Term</label>
                        <select name="academic[current_term]" id="academic[current_term]" class="form-select w-full">
                            <option value="1" {{ ($academicSettings['current_term'] ?? '') == '1' ? 'selected' : '' }}>First Term</option>
                            <option value="2" {{ ($academicSettings['current_term'] ?? '') == '2' ? 'selected' : '' }}>Second Term</option>
                            <option value="3" {{ ($academicSettings['current_term'] ?? '') == '3' ? 'selected' : '' }}>Third Term</option>
                        </select>
                    </div>

                    <!-- Passing Grade -->
                    <div>
                        <label for="academic[passing_grade]" class="block text-sm font-medium text-gray-700 mb-1">Passing Grade</label>
                        <input type="text" name="academic[passing_grade]" id="academic[passing_grade]" class="form-input w-full" 
                            value="{{ $academicSettings['passing_grade'] ?? 'D' }}" placeholder="e.g. D">
                        <p class="text-xs text-gray-500 mt-1">
                            <a href="{{ route('settings.grade-schemes') }}" class="text-blue-600 hover:underline">
                                <i class="fas fa-cog mr-1"></i> Manage Grading System
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- SMS Settings Tab -->
            <div id="sms-tab" class="p-6 tab-content hidden">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">SMS Notification Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- SMS Enabled -->
                    <div>
                        <label for="sms[sms_enabled]" class="flex items-center">
                            <input type="checkbox" name="sms[sms_enabled]" id="sms[sms_enabled]" class="form-checkbox h-5 w-5 text-blue-600" 
                                {{ ($smsSettings['sms_enabled'] ?? 'false') == 'true' ? 'checked' : '' }} value="true">
                            <span class="ml-2 text-sm text-gray-700">Enable SMS Notifications</span>
                        </label>
                    </div>

                    <!-- SMS API Key -->
                    <div>
                        <label for="sms[sms_api_key]" class="block text-sm font-medium text-gray-700 mb-1">SMS API Key</label>
                        <input type="password" name="sms[sms_api_key]" id="sms[sms_api_key]" class="form-input w-full" 
                            value="{{ $smsSettings['sms_api_key'] ?? '' }}">
                    </div>

                    <!-- SMS Sender ID -->
                    <div>
                        <label for="sms[sms_sender_id]" class="block text-sm font-medium text-gray-700 mb-1">SMS Sender ID</label>
                        <input type="text" name="sms[sms_sender_id]" id="sms[sms_sender_id]" class="form-input w-full" 
                            value="{{ $smsSettings['sms_sender_id'] ?? '' }}" placeholder="e.g. SCHOOL">
                        <p class="text-xs text-gray-500 mt-1">Maximum 11 characters, no spaces or special characters.</p>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 text-right">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-1"></i> Save Settings
                </button>
                
                <a href="{{ route('settings.initialize') }}" class="btn-secondary ml-2" onclick="return confirm('This will reset all settings to default values. Are you sure?')">
                    <i class="fas fa-redo-alt mr-1"></i> Reset to Defaults
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching functionality
        const tabs = document.querySelectorAll('[id$="-tab-button"]');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Get the target tab content id from the href attribute
                const targetId = this.getAttribute('href');
                
                // Remove active class from all tabs
                tabs.forEach(t => {
                    t.classList.remove('border-blue-600', 'text-blue-600');
                    t.classList.add('border-transparent');
                });
                
                // Add active class to clicked tab
                this.classList.add('border-blue-600', 'text-blue-600');
                this.classList.remove('border-transparent');
                
                // Hide all tab contents
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Show the target tab content
                document.querySelector(targetId).classList.remove('hidden');
            });
        });
    });
</script>
@endpush
@endsection
