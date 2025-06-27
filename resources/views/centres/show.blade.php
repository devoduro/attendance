@extends('layouts.app')

@section('title', 'Centre Details')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-building mr-3 text-blue-600"></i> {{ $centre->name }}
                <span class="ml-3 px-3 py-1 rounded-full text-xs font-medium {{ $centre->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $centre->is_active ? 'Active' : 'Inactive' }}
                </span>
            </h1>
            <p class="text-gray-500 mt-2 flex items-center">
                <i class="fas fa-map-marker-alt mr-2 text-gray-600"></i> {{ $centre->location }}
            </p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('centres.edit', $centre->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                <i class="fas fa-edit mr-2"></i> Edit Centre
            </a>
            <a href="{{ route('centres.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div id="successAlert" class="mb-6 rounded-md bg-green-50 p-4 border-l-4 border-green-500 relative" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" class="dismiss-alert inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <span class="sr-only">Dismiss</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="errorAlert" class="mb-6 rounded-md bg-red-50 p-4 border-l-4 border-red-500 relative" role="alert">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button type="button" class="dismiss-alert inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <span class="sr-only">Dismiss</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Centre Overview -->
    <div class="flex flex-col lg:flex-row gap-6 mb-6">
        <!-- Centre Information Card -->
        <div class="lg:w-2/3">
            <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-blue-500">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 flex justify-between items-center text-white">
                    <h2 class="text-lg font-bold flex items-center"><i class="fas fa-info-circle mr-2"></i>Centre Information</h2>
                    <span class="bg-white text-blue-800 text-xs px-2 py-1 rounded-full font-medium">ID: {{ $centre->id }}</span>
                </div>
                <div class="p-0">
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                            <div>
                                <div class="flex items-start mb-4">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-700">Name</h3>
                                        <p class="text-gray-800">{{ $centre->name }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start mb-4">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-700">Location</h3>
                                        <p class="text-gray-800">{{ $centre->location }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start mb-4">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-map"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-700">Address</h3>
                                        <p class="text-gray-800">{{ $centre->address ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex items-start mb-4">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-700">Contact Number</h3>
                                        <p class="text-gray-800">{{ $centre->contact_number ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start mb-4">
                                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-700">Email</h3>
                                        <p class="text-gray-800">{{ $centre->email ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start mb-4">
                                    <div class="w-10 h-10 rounded-full {{ $centre->is_active ? 'bg-green-500' : 'bg-red-500' }} text-white flex items-center justify-center mr-3 flex-shrink-0">
                                        <i class="fas fa-{{ $centre->is_active ? 'check' : 'times' }}"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-700">Status</h3>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $centre->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $centre->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i> Created: {{ $centre->created_at->format('d M Y, h:i A') }} | 
                                Last Updated: {{ $centre->updated_at->format('d M Y, h:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Centre Statistics Card -->
        <div class="lg:w-1/3">
            <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-green-500">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 py-3 text-white">
                    <h2 class="text-lg font-bold flex items-center"><i class="fas fa-chart-pie mr-2"></i>Centre Statistics</h2>
                </div>
                <div class="p-5">
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg shadow-sm p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-3xl font-bold text-blue-600">{{ $centre->students->count() }}</h3>
                                    <p class="text-gray-500 text-sm">Students</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-blue-500 text-white flex items-center justify-center">
                                    <i class="fas fa-users fa-lg"></i>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 mt-4 pt-4">
                                <a href="#" class="w-full inline-flex justify-center items-center px-3 py-2 border border-blue-500 text-blue-600 text-sm rounded-md hover:bg-blue-50 transition-all duration-200">
                                    <i class="fas fa-search mr-2"></i> View All Students
                                </a>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg shadow-sm p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-3xl font-bold text-green-600">{{ $centre->lessonSchedules->count() }}</h3>
                                    <p class="text-gray-500 text-sm">Lesson Schedules</p>
                                </div>
                                <div class="w-12 h-12 rounded-full bg-green-500 text-white flex items-center justify-center">
                                    <i class="fas fa-calendar-alt fa-lg"></i>
                                </div>
                            </div>
                            <div class="border-t border-gray-200 mt-4 pt-4">
                                <a href="#schedulesSection" class="w-full inline-flex justify-center items-center px-3 py-2 border border-green-500 text-green-600 text-sm rounded-md hover:bg-green-50 transition-all duration-200">
                                    <i class="fas fa-calendar-week mr-2"></i> View All Schedules
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lesson Schedules for this Centre -->
    <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-blue-600 mb-6" id="schedulesSection">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3 flex justify-between items-center text-white">
            <h2 class="text-lg font-bold flex items-center"><i class="fas fa-calendar-alt mr-2"></i>Lesson Schedules</h2>
            <span class="bg-white text-blue-800 text-xs px-2 py-1 rounded-full font-medium">
                {{ $centre->lessonSchedules->count() }} {{ Str::plural('Schedule', $centre->lessonSchedules->count()) }}
            </span>
        </div>
        <div class="p-5">
            @if($centre->lessonSchedules->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="schedulesTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-day mr-2 text-blue-500"></i> Day
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2 text-blue-500"></i> Section
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-tie mr-2 text-blue-500"></i> Teacher
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-plus mr-2 text-blue-500"></i> Start Date
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-minus mr-2 text-blue-500"></i> End Date
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <i class="fas fa-toggle-on mr-2 text-blue-500"></i> Status
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center justify-center">
                                        <i class="fas fa-cogs mr-2 text-blue-500"></i> Actions
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($centre->lessonSchedules as $schedule)
                                <tr class="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-medium text-gray-900">{{ $schedule->day_of_week }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                                                {{ $schedule->lessonSection->start_time }} - {{ $schedule->lessonSection->end_time }}
                                            </span>
                                            <span class="text-gray-900">{{ $schedule->lessonSection->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($schedule->teacher && $schedule->teacher->user)
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center mr-2 flex-shrink-0">
                                                    {{ substr($schedule->teacher->user->name, 0, 1) }}
                                                </div>
                                                <span class="text-gray-900">{{ $schedule->teacher->user->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-500 flex items-center">
                                                <i class="fas fa-user-slash mr-2"></i> Not Assigned
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800 inline-flex items-center">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ $schedule->start_date->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($schedule->end_date)
                                            <span class="px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800 inline-flex items-center">
                                                <i class="far fa-calendar-alt mr-1"></i> {{ $schedule->end_date->format('d M Y') }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-md text-xs font-medium bg-green-100 text-green-800 inline-flex items-center">
                                                <i class="fas fa-infinity mr-1"></i> Ongoing
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} inline-flex items-center">
                                            <i class="fas fa-{{ $schedule->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                            {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('lesson-schedules.show', $schedule->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                            <i class="fas fa-eye mr-1"></i> Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mb-4">
                        <i class="fas fa-calendar-times text-5xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-500 mb-1">No lesson schedules found for this centre</h3>
                    <p class="text-gray-400 mb-6">Schedules will appear here once they are created</p>
                    <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i> Create New Schedule
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables with Tailwind styling
        $('#schedulesTable').DataTable({
            responsive: true,
            language: {
                search: "<i class='fas fa-search'></i> _INPUT_",
                searchPlaceholder: "Search schedules...",
                zeroRecords: "<div class='text-center py-4'><i class='fas fa-folder-open text-gray-400 text-4xl mb-3'></i><p class='text-gray-500'>No matching schedules found</p></div>",
                info: "Showing _START_ to _END_ of _TOTAL_ schedules",
                lengthMenu: "Show _MENU_ schedules",
                paginate: {
                    first: "<i class='fas fa-angle-double-left'></i>",
                    last: "<i class='fas fa-angle-double-right'></i>",
                    next: "<i class='fas fa-angle-right'></i>",
                    previous: "<i class='fas fa-angle-left'></i>"
                }
            },
            dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"flex-none mb-2 md:mb-0"l><"flex-grow mx-2"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"flex-none mb-2 md:mb-0"i><"flex-none"p>>',
            pagingType: "simple_numbers",
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            pageLength: 10,
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [6] }
            ],
            // Apply Tailwind classes to DataTables elements
            initComplete: function() {
                // Style the search input
                $('.dataTables_filter input').addClass('rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm');
                
                // Style the length select
                $('.dataTables_length select').addClass('rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm');
                
                // Style pagination buttons
                $('.dataTables_paginate .paginate_button').addClass('px-3 py-1 border border-gray-300 text-sm');
                $('.dataTables_paginate .paginate_button.current').addClass('bg-blue-500 text-white border-blue-500');
            }
        });
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.dismiss-alert').click();
        }, 5000);
        
        // Alert dismiss functionality
        $('.dismiss-alert').on('click', function() {
            $(this).closest('[role="alert"]').fadeOut(300, function() {
                $(this).remove();
            });
        });
        
        // Smooth scroll to sections
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            var target = $(this.hash);
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 20
                }, 500);
            }
        });
    });
</script>
@endsection
