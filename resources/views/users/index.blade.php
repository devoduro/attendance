@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="{ bulkActionSelected: false }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">User Management</h1>
        <div class="flex space-x-2">
            @can('create', App\Models\User::class)
            <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New User
            </a>
            @endcan
        </div>
    </div>

    <!-- Filter and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('users.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end space-y-4 md:space-y-0 md:space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name or email" class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="w-full md:w-1/4">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Filter by Role</label>
                <select name="role" id="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </span>
                </button>
                @if(request('search') || request('role') || request('status'))
                    <a href="{{ route('users.index') }}" class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Reset
                        </span>
                    </a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- Bulk Actions -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form id="bulk-action-form" action="{{ route('users.bulk-action') }}" method="POST">
            @csrf
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div class="flex items-center">
                    <select name="bulk_action" id="bulk-action" class="form-select rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" x-on:change="bulkActionSelected = $event.target.value !== ''">
                        <option value="">-- Select Bulk Action --</option>
                        <option value="activate">Activate Selected</option>
                        <option value="deactivate">Deactivate Selected</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="button" 
                            x-bind:disabled="!bulkActionSelected" 
                            x-bind:class="{'opacity-50 cursor-not-allowed': !bulkActionSelected}"
                            class="ml-2 px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out"
                            onclick="confirmBulkAction()">
                        Apply
                    </button>
                </div>
                <div class="text-sm text-gray-500">
                    <span id="selected-count">0</span> users selected
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3">
                            <div class="flex items-center">
                                <input id="select-all" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" onclick="toggleAllCheckboxes()">
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if(auth()->id() !== $user->id)
                                        <input type="checkbox" name="selected_users[]" value="{{ $user->id }}" class="user-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" onchange="updateSelectedCount()">
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($user->profile_photo)
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <span class="text-gray-500 font-medium">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($user->roles as $role)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($user->status ?? 'active') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                    <button @click="open = !open" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                        Actions
                                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                        <div class="py-1" role="none">
                                            <a href="{{ route('users.show', $user) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                                <svg class="mr-3 h-5 w-5 text-blue-600 group-hover:text-blue-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View Details
                                            </a>
                                        </div>
                                        @can('update', $user)
                                        <div class="py-1" role="none">
                                            <a href="{{ route('users.edit', $user) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                                <svg class="mr-3 h-5 w-5 text-yellow-600 group-hover:text-yellow-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit User
                                            </a>
                                        </div>
                                        @endcan
                                        @can('delete', $user)
                                        @if(auth()->id() !== $user->id)
                                        <div class="py-1" role="none">
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" id="delete-form-{{ $user->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete({{ $user->id }})" class="group flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                                    <svg class="mr-3 h-5 w-5 text-red-600 group-hover:text-red-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete User
                                                </button>
                                            </form>
                                        </div>
                                        @endif
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
    </div>

@push('scripts')
<script>
    function toggleAllCheckboxes() {
        const selectAllCheckbox = document.getElementById('select-all');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        
        updateSelectedCount();
    }
    
    function updateSelectedCount() {
        const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
        const countElement = document.getElementById('selected-count');
        
        if (countElement) {
            countElement.textContent = selectedCheckboxes.length;
        }
        
        // Update bulk action button state
        const bulkActionSelected = selectedCheckboxes.length > 0;
        window.Alpine.store('bulkActionSelected', bulkActionSelected);
    }
    
    function confirmBulkAction() {
        const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            alert('Please select at least one user');
            return false;
        }
        
        const bulkAction = document.getElementById('bulk-action').value;
        let confirmMessage = '';
        
        switch (bulkAction) {
            case 'activate':
                confirmMessage = 'Are you sure you want to activate the selected users?';
                break;
            case 'deactivate':
                confirmMessage = 'Are you sure you want to deactivate the selected users?';
                break;
            case 'delete':
                confirmMessage = 'Are you sure you want to delete the selected users? This action cannot be undone.';
                break;
            default:
                alert('Please select an action');
                return false;
        }
        
        if (confirm(confirmMessage)) {
            // Add selected checkboxes to the form
            const form = document.getElementById('bulk-action-form');
            selectedCheckboxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_users[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });
            
            form.submit();
        }
    }
    
    function confirmDelete(userId) {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            document.getElementById('delete-form-' + userId).submit();
        }
    }
</script>
@endpush

@endsection
