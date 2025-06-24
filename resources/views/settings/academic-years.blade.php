@extends('layouts.app')

@section('title', 'Academic Years')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Academic Years</h1>
        <div>
            <a href="{{ route('settings.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Settings
            </a>
        </div>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add New Academic Year -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Add New Academic Year</h2>
            
            <form action="{{ route('settings.store-academic-year') }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Academic Year <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" class="form-input w-full @error('name') border-red-500 @enderror" 
                            value="{{ old('name') }}" placeholder="e.g. 2024/2025" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="start_date" class="form-input w-full @error('start_date') border-red-500 @enderror" 
                            value="{{ old('start_date') }}" required>
                        @error('start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" id="end_date" class="form-input w-full @error('end_date') border-red-500 @enderror" 
                            value="{{ old('end_date') }}" required>
                        @error('end_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label for="status" class="flex items-center">
                            <input type="checkbox" name="status" id="status" class="form-checkbox h-5 w-5 text-blue-600" 
                                {{ old('status') ? 'checked' : '' }} value="active">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                    
                    <!-- Set as Current -->
                    <div>
                        <label for="set_as_current" class="flex items-center">
                            <input type="checkbox" name="set_as_current" id="set_as_current" class="form-checkbox h-5 w-5 text-blue-600" 
                                {{ old('set_as_current') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Set as Current Academic Year</span>
                        </label>
                    </div>
                    
                    <div>
                        <button type="submit" class="btn-primary w-full">
                            <i class="fas fa-plus mr-1"></i> Add Academic Year
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Academic Years List -->
        <div class="bg-white rounded-lg shadow-sm p-6 lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Academic Years</h2>
            
            @if($academicYears->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($academicYears as $year)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $year->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($year->start_date)->format('M d, Y') }} - 
                                            {{ \Carbon\Carbon::parse($year->end_date)->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($year->status == 'active')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($year->name == $currentAcademicYear)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Current
                                            </span>
                                        @else
                                            <form action="{{ route('settings.update') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="academic[current_academic_year]" value="{{ $year->name }}">
                                                <button type="submit" class="text-xs text-blue-600 hover:text-blue-900">
                                                    Set as Current
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="text-red-600 hover:text-red-900" 
                                           onclick="return confirm('Are you sure you want to delete this academic year?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-gray-500">No academic years found. Add your first academic year.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
