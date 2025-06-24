@extends('layouts.app')

@section('title', 'Edit Academic Year')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Academic Year</h1>
        <a href="{{ route('settings.academic-years') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back to Academic Years
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6">
        <form action="{{ route('settings.academic-years.update', $academicYear) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Academic Year Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $academicYear->name) }}" 
                    class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                    placeholder="e.g., 2025/2026" required>
                <p class="text-xs text-gray-500 mt-1">Format: YYYY/YYYY (e.g., 2025/2026)</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $academicYear->start_date->format('Y-m-d')) }}" 
                        class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                        required>
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $academicYear->end_date->format('Y-m-d')) }}" 
                        class="form-input w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                        required>
                </div>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" 
                    class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                    required>
                    <option value="active" {{ old('status', $academicYear->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $academicYear->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="completed" {{ old('status', $academicYear->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3" 
                    class="form-textarea w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                    placeholder="Optional description of this academic year">{{ old('description', $academicYear->description) }}</textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-1"></i> Update Academic Year
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
