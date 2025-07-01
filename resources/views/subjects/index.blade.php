@extends('layouts.app')

@section('title', 'Subject Management')

@section('content')
<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800">Subject Management</h1>
        <a href="{{ route('subjects.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-800 focus:outline-none focus:border-primary-800 focus:ring focus:ring-primary-200 transition ease-in-out duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Add New Subject
        </a>
    </div>

    <div class="p-6">
        <!-- Search and Filter Section -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="search" class="focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md" placeholder="Search subjects...">
                    </div>
                </div>
                <div class="w-full md:w-1/4">
                    <label for="centre-filter" class="block text-sm font-medium text-gray-700 mb-1">Centre</label>
                    <select id="centre-filter" class="focus:ring-primary-500 focus:border-primary-500 block w-full border-gray-300 rounded-md">
                        <option value="">All Centres</option>
                        @foreach($centres as $centre)
                            <option value="{{ $centre->id }}">{{ $centre->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-1/4">
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status-filter" class="focus:ring-primary-500 focus:border-primary-500 block w-full border-gray-300 rounded-md">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Subjects Table -->
        <div class="overflow-x-auto">
            <table id="subjects-table" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Centre</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($subjects as $subject)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $subject->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $subject->code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $subject->centre ? $subject->centre->name : 'Not Assigned' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $subject->status == 'active' ? 'bg-success-100 text-success-800' : 'bg-danger-100 text-danger-800' }}">
                                {{ ucfirst($subject->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('subjects.show', $subject->id) }}" class="text-primary-600 hover:text-primary-900" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('subjects.edit', $subject->id) }}" class="text-primary-600 hover:text-primary-900" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this subject?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-danger-600 hover:text-danger-900" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable with Tailwind styling
        const table = $('#subjects-table').DataTable({
            responsive: true,
            dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"flex-1"l><"flex"f>>rt<"flex flex-col md:flex-row justify-between items-center mt-4"<"flex-1"i><"flex"p>>',
            language: {
                search: "",
                searchPlaceholder: "Search...",
                lengthMenu: "Show _MENU_ entries",
            },
            // Remove default styling
            autoWidth: false,
            columnDefs: [
                { targets: -1, orderable: false } // Disable sorting on actions column
            ],
            initComplete: function() {
                // Apply Tailwind classes to DataTable elements
                $('.dataTables_length select').addClass('block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50');
                $('.dataTables_filter input').addClass('block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50');
                $('.dataTables_paginate .paginate_button').addClass('px-4 py-2 border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-primary-300 focus:shadow-outline-primary active:bg-gray-100 active:text-gray-800 transition ease-in-out duration-150');
                $('.dataTables_paginate .paginate_button.current').addClass('bg-primary-100 text-primary-800 border-primary-500').removeClass('border-gray-300 bg-white text-gray-700');
            }
        });
        
        // Custom filtering
        $('#search').on('keyup', function() {
            table.search(this.value).draw();
        });
        
        $('#centre-filter').on('change', function() {
            table.column(2).search($(this).val() ? $(this).find('option:selected').text() : '').draw();
        });
        
        $('#status-filter').on('change', function() {
            table.column(3).search($(this).val()).draw();
        });
    });
</script>
@endpush
