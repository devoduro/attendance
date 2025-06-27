@extends('layouts.app')

@section('title', 'Teachers')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="bulkActionsHandler()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Teachers</h1>
        @can('create', App\Models\Teacher::class)
        <a href="{{ route('teachers.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md">
            Add New Teacher
        </a>
        @endcan
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm mb-6 p-4">
        <form action="{{ route('teachers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                    placeholder="Search by name, email, or ID">
            </div>
            
            <div>
                <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <select name="department" id="department" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">All Departments</option>
                    @foreach(\App\Models\Department::all() as $department)
                        <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Subjects filter removed -->

            <div>
                <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                <select name="class" id="class" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <option value="">All Classes</option>
                    @foreach(\App\Models\SchoolClass::where('status', 'active')->get() as $class)
                        <option value="{{ $class->id }}" {{ request('class') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('teachers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset
                </a>
            </div>
        </form>
    </div>
    
    <!-- Bulk Actions -->
    <div class="bg-white rounded-lg shadow-sm mb-6 p-4" x-show="selectedItems.length > 0">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <span x-text="selectedItems.length"></span> teachers selected
            </div>
            <div class="flex space-x-2">
                @can('update', App\Models\Teacher::class)
                <button @click="confirmBulkAction('activate')" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-1 px-3 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Activate
                </button>
                <button @click="confirmBulkAction('deactivate')" class="bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium py-1 px-3 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Deactivate
                </button>
                @endcan
                @can('delete', App\Models\Teacher::class)
                <button @click="confirmBulkAction('delete')" class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-1 px-3 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
                @endcan
            </div>
        </div>
    </div>

    <!-- Teachers List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="min-w-full w-full divide-y divide-gray-200" style="min-width: 1000px;">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" @click="toggleSelectAll" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Department
                    </th>
                    <!-- Subjects column removed -->
                    <!-- Classes column removed -->
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                        <span class="font-bold">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($teachers as $teacher)
                <tr>
                    <td class="px-2 py-4 whitespace-nowrap">
                        <input type="checkbox" value="{{ $teacher->id }}" x-model="selectedItems" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $teacher->staff_id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $teacher->user->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $teacher->user->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $teacher->department->name ?? 'N/A' }}
                    </td>
                    <!-- Subjects count cell removed -->
                    <!-- Classes count cell removed -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        @can('update', $teacher)
                        <form action="{{ route('teachers.toggle-status', $teacher) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="focus:outline-none">
                                @if($teacher->status == 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 hover:bg-green-200">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 hover:bg-red-200">
                                        Inactive
                                    </span>
                                @endif
                            </button>
                        </form>
                        @else
                            @if($teacher->status == 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        @endcan
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-3">
                            <a href="{{ route('teachers.show', $teacher) }}" class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 px-3 py-1 rounded-md flex items-center shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span>View</span>
                            </a>
                            <a href="{{ route('teachers.edit', $teacher) }}" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded-md flex items-center shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Edit</span>
                            </a>
                            <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded-md flex items-center shadow-sm" onclick="return confirm('Are you sure you want to delete this teacher?')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span>Delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                        No teachers found. 
                        @can('create', App\Models\Teacher::class)
                        <a href="{{ route('teachers.create') }}" class="text-primary-600 hover:underline">Add a new teacher</a>
                        @endcan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $teachers->links() }}
        </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function bulkActionsHandler() {
        return {
            selectedItems: [],
            toggleSelectAll: function() {
                const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
                const isChecked = this.selectedItems.length === checkboxes.length;
                
                if (isChecked) {
                    this.selectedItems = [];
                } else {
                    this.selectedItems = Array.from(checkboxes).map(checkbox => checkbox.value);
                }
            },
            confirmBulkAction: function(action) {
                if (this.selectedItems.length === 0) {
                    alert('Please select at least one teacher');
                    return;
                }
                
                let message = '';
                if (action === 'delete') {
                    message = 'Are you sure you want to delete the selected teachers?';
                } else if (action === 'activate') {
                    message = 'Are you sure you want to activate the selected teachers?';
                } else if (action === 'deactivate') {
                    message = 'Are you sure you want to deactivate the selected teachers?';
                }
                
                if (confirm(message)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("teachers.bulk-action") }}';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    const actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    actionInput.value = action;
                    form.appendChild(actionInput);
                    
                    this.selectedItems.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'teacher_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        };
    }
</script>
@endpush
