@extends('layouts.app')

@section('title', 'Academic Years')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Academic Years</h1>
        <a href="{{ route('settings.academic-years.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-1"></i> Add New Academic Year
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($academicYears as $academicYear)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $academicYear->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $academicYear->start_date->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $academicYear->end_date->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $academicYear->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($academicYear->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 
                                   'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst($academicYear->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($academicYear->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('settings.academic-years.edit', $academicYear) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('settings.academic-years.destroy', $academicYear) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this academic year?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                            No academic years found. <a href="{{ route('settings.academic-years.create') }}" class="text-indigo-600 hover:text-indigo-900">Create one now</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
