@extends('layouts.app')

@section('title', 'Lesson Section Details')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center mb-4 sm:mb-0">
            <i class="fas fa-clock mr-3 text-blue-600"></i> Lesson Section Details
        </h1>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('lesson-sections.edit', $lessonSection->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200">
                <i class="fas fa-edit mr-2"></i> Edit Lesson Section
            </a>
            <a href="{{ route('lesson-sections.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Back to Lesson Sections
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-blue-500 mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 flex justify-between items-center text-white">
                    <h2 class="text-lg font-bold flex items-center"><i class="fas fa-info-circle mr-2"></i>Lesson Section Information</h2>
                </div>
                <div class="p-5">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                                        <div class="flex items-center">
                                            <i class="fas fa-signature text-blue-500 mr-2"></i> Name
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $lessonSection->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-hourglass-start text-blue-500 mr-2"></i> Start Time
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="far fa-clock mr-1"></i> {{ $lessonSection->start_time }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-hourglass-end text-blue-500 mr-2"></i> End Time
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="far fa-clock mr-1"></i> {{ $lessonSection->end_time }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-stopwatch text-blue-500 mr-2"></i> Duration
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-stopwatch mr-1"></i> {{ $lessonSection->getDurationInMinutes() }} minutes
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-align-left text-blue-500 mr-2"></i> Description
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $lessonSection->description ?? 'N/A' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-toggle-on text-blue-500 mr-2"></i> Status
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lessonSection->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} inline-flex items-center">
                                            <i class="fas fa-{{ $lessonSection->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                            {{ $lessonSection->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-sync-alt text-blue-500 mr-2"></i> Weekly Repeat
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ isset($lessonSection->repeat_weekly) && $lessonSection->repeat_weekly ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} inline-flex items-center">
                                            <i class="fas fa-{{ isset($lessonSection->repeat_weekly) && $lessonSection->repeat_weekly ? 'sync-alt' : 'times' }} mr-1"></i>
                                            {{ isset($lessonSection->repeat_weekly) && $lessonSection->repeat_weekly ? 'Repeats Weekly' : 'No Repeat' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-plus text-blue-500 mr-2"></i> Created At
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $lessonSection->created_at->format('d M Y, h:i A') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-check text-blue-500 mr-2"></i> Updated At
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $lessonSection->updated_at->format('d M Y, h:i A') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-green-500 mb-6">
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 py-3 flex justify-between items-center text-white">
                    <h2 class="text-lg font-bold flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>Lesson Section Statistics
                    </h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-blue-500">
                            <div class="p-5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-bold text-blue-600 uppercase mb-1">Lesson Schedules</p>
                                        <p class="text-2xl font-bold text-gray-800">
                                            {{ $lessonSection->lessonSchedules->count() }}
                                        </p>
                                    </div>
                                    <div class="bg-blue-100 p-3 rounded-full">
                                        <i class="fas fa-calendar fa-2x text-blue-500"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-green-500">
                            <div class="p-5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-bold text-green-600 uppercase mb-1">Active Schedules</p>
                                        <p class="text-2xl font-bold text-gray-800">
                                            {{ $lessonSection->lessonSchedules->where('is_active', true)->count() }}
                                        </p>
                                    </div>
                                    <div class="bg-green-100 p-3 rounded-full">
                                        <i class="fas fa-check-circle fa-2x text-green-500"></i>
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
    <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-purple-500 mb-6">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-4 py-3 flex justify-between items-center text-white">
            <h2 class="text-lg font-bold flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i>Lesson Schedules Using This Section
            </h2>
        </div>
        <div class="p-5">
            <div class="overflow-x-auto">
                @if($lessonSection->lessonSchedules->count() > 0)
                <table class="min-w-full divide-y divide-gray-200" id="schedulesTable">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-building text-purple-500 mr-2"></i> Centre
                                </div>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-day text-purple-500 mr-2"></i> Day
                                </div>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-chalkboard-teacher text-purple-500 mr-2"></i> Teacher
                                </div>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-plus text-purple-500 mr-2"></i> Start Date
                                </div>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-minus text-purple-500 mr-2"></i> End Date
                                </div>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-toggle-on text-purple-500 mr-2"></i> Status
                                </div>
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-cogs text-purple-500 mr-2"></i> Actions
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($lessonSection->lessonSchedules as $schedule)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <i class="fas fa-building text-gray-400 mr-2"></i>
                                        {{ $schedule->centre->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $schedule->day_of_week }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <i class="fas fa-user text-gray-400 mr-2"></i>
                                        {{ $schedule->teacher->user->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="far fa-calendar mr-1"></i>
                                        {{ $schedule->start_date->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($schedule->end_date)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="far fa-calendar mr-1"></i>
                                            {{ $schedule->end_date->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-infinity mr-1"></i>
                                            Ongoing
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} inline-flex items-center">
                                        <i class="fas fa-{{ $schedule->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                        {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('lesson-schedules.show', $schedule->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 p-2 rounded-full transition-colors duration-200">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                    <i class="fas fa-calendar-times text-5xl mb-4 text-purple-300"></i>
                    <p class="text-xl font-medium mb-2">No lesson schedules found</p>
                    <p class="text-gray-400 mb-6">There are no lesson schedules using this section yet.</p>
                </div>
                @endif
            </div>
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
            dom: '<"flex flex-col md:flex-row md:items-center justify-between mb-4"<"flex-1"f><"flex-shrink-0"l>>t<"flex flex-col md:flex-row justify-between mt-4"<"flex-1"i><"flex-shrink-0"p>>',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search schedules...",
                lengthMenu: "_MENU_ per page",
                info: "Showing _START_ to _END_ of _TOTAL_ schedules",
                infoEmpty: "Showing 0 to 0 of 0 schedules",
                infoFiltered: "(filtered from _MAX_ total schedules)"
            },
            // Apply Tailwind classes to DataTables elements
            initComplete: function() {
                // Style the search input
                $('.dataTables_filter input').addClass('mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50');
                
                // Style the length select
                $('.dataTables_length select').addClass('mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50');
                
                // Add wrapper for search input with icon
                $('.dataTables_filter').addClass('relative');
                $('.dataTables_filter input').before('<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fas fa-search text-gray-400"></i></div>');
                $('.dataTables_filter input').addClass('pl-10');
            }
        });
        
        // Add auto-dismiss functionality for alerts
        setTimeout(function() {
            $(".alert").fadeOut('slow');
        }, 5000);
    });
</script>
@endsection
