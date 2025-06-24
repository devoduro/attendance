@extends('layouts.app')

@section('title', 'Students')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Students</h1>
        <div class="mt-4 md:mt-0 flex flex-wrap gap-2">
            <a href="{{ route('students.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-1"></i> Add Student
            </a>
            <a href="{{ route('students.import.form') }}" class="btn-secondary">
                <i class="fas fa-file-import mr-1"></i> Import Students
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

    <!-- Import Errors -->
    @if(session('import_errors'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            <p class="font-bold">Import Errors:</p>
            <ul class="list-disc ml-5 mt-2">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Form -->
            <div>
                <form action="{{ route('students.index') }}" method="GET" class="flex">
                    <input type="text" name="query" placeholder="Search by name, ID, phone or email" 
                        class="form-input rounded-r-none flex-1" value="{{ isset($query) ? $query : '' }}">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Filter by Program -->
            <div>
                <form action="{{ route('students.index') }}" method="GET">
                    <div class="flex">
                        <select name="program_id" class="form-select rounded-r-none flex-1">
                            <option value="">Filter by Program</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" {{ isset($selectedProgram) && $selectedProgram->id == $program->id ? 'selected' : '' }}>
                                    {{ $program->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Filter by Class -->
            <div>
                <form action="{{ route('students.index') }}" method="GET">
                    <div class="flex">
                        <select name="class_id" class="form-select rounded-r-none flex-1">
                            <option value="">Filter by Class</option>
                            @isset($classes)
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ isset($selectedClass) && $selectedClass->id == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Filters -->
        @if(isset($query) || isset($selectedProgram) || isset($selectedClass))
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="text-sm text-gray-600">Active filters:</span>
                
                @isset($query)
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded flex items-center">
                        Search: {{ $query }}
                        <a href="{{ route('students.index') }}" class="ml-1 text-blue-600 hover:text-blue-900">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endisset
                
                @isset($selectedProgram)
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded flex items-center">
                        Program: {{ $selectedProgram->name }}
                        <a href="{{ route('students.index') }}" class="ml-1 text-blue-600 hover:text-blue-900">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endisset
                
                @isset($selectedClass)
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded flex items-center">
                        Class: {{ $selectedClass->name }}
                        <a href="{{ route('students.index') }}" class="ml-1 text-blue-600 hover:text-blue-900">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endisset
            </div>
        @endif
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($students->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($students as $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $student->enrollment_code }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="{{ $student->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($student->user->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ $student->user->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $student->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $student->class->name ?? 'Not Assigned' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $student->program->name ?? 'Not Assigned' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $student->mobile_phone ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student->status == 'active')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('students.results', $student) }}" class="text-green-600 hover:text-green-900 mr-3">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $students->links() }}
            </div>
        @else
            <div class="text-center py-10">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No students found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(isset($query) || isset($selectedProgram) || isset($selectedClass))
                        No students match your current filters.
                    @else
                        Get started by adding a new student.
                    @endif
                </p>
                <div class="mt-6">
                    <a href="{{ route('students.create') }}" class="btn-primary">
                        <i class="fas fa-plus mr-1"></i> Add Student
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
