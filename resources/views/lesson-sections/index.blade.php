@extends('layouts.app')

@section('title', 'Lesson Sections Management')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center mb-4 sm:mb-0">
            <i class="fas fa-clock mr-3 text-blue-600"></i> Lesson Sections Management
        </h1>
        <a href="{{ route('lesson-sections.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
            <i class="fas fa-plus mr-2"></i> Add New Lesson Section
        </a>
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

    <!-- Lesson Sections Table Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-blue-500 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 flex justify-between items-center text-white">
            <h2 class="text-lg font-bold flex items-center"><i class="fas fa-list mr-2"></i>All Lesson Sections</h2>
        </div>
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="dataTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-hashtag mr-2 text-blue-500"></i> ID
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-signature mr-2 text-blue-500"></i> Name
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-hourglass-start mr-2 text-blue-500"></i> Start Time
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-hourglass-end mr-2 text-blue-500"></i> End Time
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <i class="fas fa-stopwatch mr-2 text-blue-500"></i> Duration
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
                        @forelse($lessonSections as $section)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $section->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $section->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="far fa-clock mr-1"></i> {{ $section->start_time }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="far fa-clock mr-1"></i> {{ $section->end_time }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-stopwatch mr-1"></i> {{ $section->getDurationInMinutes() }} minutes
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $section->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} inline-flex items-center">
                                        <i class="fas fa-{{ $section->is_active ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                        {{ $section->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('lesson-sections.show', $section->id) }}" class="inline-flex items-center p-1.5 border border-transparent rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('lesson-sections.edit', $section->id) }}" class="inline-flex items-center p-1.5 border border-transparent rounded-md shadow-sm text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('lesson-sections.destroy', $section->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center p-1.5 border border-transparent rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200" onclick="return confirm('Are you sure you want to delete this lesson section?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-clock text-5xl text-gray-300 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-500 mb-1">No lesson sections found</h3>
                                        <p class="text-gray-400 mb-6">Lesson sections will appear here once they are created</p>
                                        <a href="{{ route('lesson-sections.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                            <i class="fas fa-plus mr-2"></i> Create New Section
                                        </a>
                                    </div>
                                </td>
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
        // Initialize DataTables with Tailwind styling
        $('#dataTable').DataTable({
            responsive: true,
            language: {
                search: "<i class='fas fa-search'></i> _INPUT_",
                searchPlaceholder: "Search sections...",
                zeroRecords: "<div class='text-center py-4'><i class='fas fa-folder-open text-gray-400 text-4xl mb-3'></i><p class='text-gray-500'>No matching sections found</p></div>",
                info: "Showing _START_ to _END_ of _TOTAL_ sections",
                lengthMenu: "Show _MENU_ sections",
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
    });
</script>
@endsection
