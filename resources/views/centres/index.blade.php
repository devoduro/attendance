@extends('layouts.app')

@section('title', 'Centres Management')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-building mr-3 text-blue-600"></i> Centres Management
            </h1>
            <p class="text-gray-500 mt-1">Manage all your educational centres in one place</p>
        </div>
        <a href="{{ route('centres.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
            <i class="fas fa-plus mr-2"></i>Add New Centre
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="relative px-4 py-3 mb-6 leading-normal text-green-700 bg-green-100 rounded-lg shadow-sm" role="alert" id="success-alert">
            <span class="absolute inset-y-0 left-0 flex items-center ml-4">
                <i class="fas fa-check-circle"></i>
            </span>
            <div class="ml-6">{{ session('success') }}</div>
            <button type="button" class="absolute top-0 right-0 mt-3 mr-4 text-green-700" onclick="this.parentElement.remove()">
                <span class="text-xl">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="relative px-4 py-3 mb-6 leading-normal text-red-700 bg-red-100 rounded-lg shadow-sm" role="alert" id="error-alert">
            <span class="absolute inset-y-0 left-0 flex items-center ml-4">
                <i class="fas fa-exclamation-circle"></i>
            </span>
            <div class="ml-6">{{ session('error') }}</div>
            <button type="button" class="absolute top-0 right-0 mt-3 mr-4 text-red-700" onclick="this.parentElement.remove()">
                <span class="text-xl">&times;</span>
            </button>
        </div>
    @endif
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Centres Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Total Centres</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $centres->count() }}</div>
                    </div>
                    <div class="rounded-full p-3 bg-blue-100">
                        <i class="fas fa-building text-xl text-blue-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Centres Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1">Active Centres</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $centres->where('is_active', true)->count() }}</div>
                    </div>
                    <div class="rounded-full p-3 bg-green-100">
                        <i class="fas fa-check-circle text-xl text-green-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Students Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-cyan-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-bold text-cyan-600 uppercase tracking-wider mb-1">Total Students</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $centres->sum('student_count') }}</div>
                    </div>
                    <div class="rounded-full p-3 bg-cyan-100">
                        <i class="fas fa-users text-xl text-cyan-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Lessons Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-yellow-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-bold text-yellow-600 uppercase tracking-wider mb-1">Total Lessons</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $centres->sum('lesson_count') }}</div>
                    </div>
                    <div class="rounded-full p-3 bg-yellow-100">
                        <i class="fas fa-calendar-alt text-xl text-yellow-500"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search and Filter Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-blue-500 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 py-3 flex justify-between items-center text-white">
            <h2 class="text-lg font-bold flex items-center">
                <i class="fas fa-filter mr-2"></i>Search & Filter
            </h2>
            <button class="text-white hover:text-blue-100 focus:outline-none transition-colors duration-200" type="button" x-data="{}" @click="document.getElementById('filterCollapse').classList.toggle('hidden')" aria-expanded="true" aria-controls="filterCollapse">
                <i class="fas fa-chevron-down" id="filterCollapseIcon"></i>
            </button>
        </div>
        <div class="" id="filterCollapse">
            <div class="p-6">
                <form action="{{ route('centres.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                                <div class="flex items-center">
                                    <i class="fas fa-search text-gray-400 mr-2"></i>Search
                                </div>
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" id="search" name="search" placeholder="Centre name, location or email..." value="{{ request('search') }}">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                <div class="flex items-center">
                                    <i class="fas fa-toggle-on text-gray-400 mr-2"></i>Status
                                </div>
                            </label>
                            <select class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" id="status" name="status">
                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <i class="fas fa-search mr-2"></i> Search
                            </button>
                            <a href="{{ route('centres.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <i class="fas fa-sync-alt mr-2"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Centres List -->
    <div class="mt-4">
        @if($centres->isEmpty())
            <div class="flex items-center px-4 py-3 mb-6 leading-normal text-blue-700 bg-blue-100 rounded-lg shadow-sm">
                <i class="fas fa-info-circle mr-3"></i>
                <span>No centres found. Try adjusting your search or filter.</span>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($centres as $centre)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-200 flex flex-col h-full border border-gray-100">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between px-5 py-3 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold text-blue-700 truncate flex-1">{{ $centre->name }}</h3>
                            <span class="ml-4 px-3 py-1 rounded-full text-xs font-semibold {{ $centre->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                <i class="fas {{ $centre->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                {{ $centre->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <!-- Card Body -->
                        <div class="flex-1 p-5 flex flex-col justify-between">
                            <div>
                                <div class="mb-3 flex items-center text-gray-700">
                                    <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>
                                    <span class="font-medium">{{ $centre->location }}</span>
                                </div>
                                @if($centre->address)
                                    <p class="text-gray-400 text-sm ml-7 mb-2">{{ Str::limit($centre->address, 100) }}</p>
                                @endif
                                <div class="mb-3">
                                    @if($centre->contact_number)
                                        <div class="flex items-center text-gray-700 mb-1">
                                            <i class="fas fa-phone text-blue-500 mr-2"></i>
                                            <span>{{ $centre->contact_number }}</span>
                                        </div>
                                    @endif
                                    @if($centre->email)
                                        <div class="flex items-center text-gray-700">
                                            <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                            <span>{{ $centre->email }}</span>
                                        </div>
                                    @endif
                                    @if(!$centre->contact_number && !$centre->email)
                                        <div class="flex items-center text-gray-400">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            <span>No contact information</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mt-4">
                                <div class="bg-gray-50 rounded-lg p-2 text-center">
                                    <div class="text-xl font-bold text-blue-700">{{ $centre->student_count }}</div>
                                    <div class="text-xs text-gray-500">Students</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-2 text-center">
                                    <div class="text-xl font-bold text-blue-700">{{ $centre->lesson_count }}</div>
                                    <div class="text-xs text-gray-500">Lessons</div>
                                </div>
                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="flex items-center justify-between px-5 py-3 border-t bg-white">
                            <a href="{{ route('centres.show', $centre->id) }}" class="inline-flex items-center px-3 py-1.5 border border-blue-500 text-blue-700 bg-white rounded-md text-xs font-medium hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('centres.edit', $centre->id) }}" class="inline-flex items-center px-2 py-1 border border-yellow-400 text-yellow-700 bg-white rounded-md text-xs font-medium hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition-all">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('centres.destroy', $centre->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2 py-1 border border-red-400 text-red-700 bg-white rounded-md text-xs font-medium hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-400 transition-all" data-centre-name="{{ $centre->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
// Enhanced delete confirmation with SweetAlert2 (vanilla JS)
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const centreName = form.querySelector('button').getAttribute('data-centre-name');
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete the centre "${centreName}". This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            buttonsStyling: true,
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

// Auto-hide alerts after 5 seconds
setTimeout(() => {
    document.querySelectorAll('.alert, [role="alert"]').forEach(alert => {
        alert.classList.add('transition-opacity', 'duration-500');
        alert.style.opacity = 0;
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
</script>
@endsection
