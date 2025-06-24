@extends('layouts.app')

@section('title', 'Import Students')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Import Students</h1>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('students.index') }}" class="btn-outline">
                <i class="fas fa-arrow-left mr-1"></i> Back to Students
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('students.import.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Excel File -->
                    <div class="md:col-span-2">
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Excel File <span class="text-red-500">*</span></label>
                        <div class="flex items-center">
                            <input type="file" name="file" id="file" class="form-input block w-full rounded-md" required accept=".csv, .xls, .xlsx">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Upload Excel file (.xlsx, .xls) or CSV file (.csv)</p>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Class -->
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700">Assign to Class <span class="text-red-500">*</span></label>
                        <select name="class_id" id="class_id" class="mt-1 form-select block w-full rounded-md" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Program -->
                    <div>
                        <label for="program_id" class="block text-sm font-medium text-gray-700">Assign to Program <span class="text-red-500">*</span></label>
                        <select name="program_id" id="program_id" class="mt-1 form-select block w-full rounded-md" required>
                            <option value="">Select Program</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </select>
                        @error('program_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Template Format</h3>
                    <p class="text-sm text-gray-600 mb-2">Your Excel file should have the following columns:</p>
                    
                    <div class="bg-gray-50 p-4 rounded-md overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name*</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email*</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollment Code*</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Birth</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile Phone</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guardian Name</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guardian Phone</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-3 py-2 text-sm text-gray-500">John Doe</td>
                                    <td class="px-3 py-2 text-sm text-gray-500">john@example.com</td>
                                    <td class="px-3 py-2 text-sm text-gray-500">STU001</td>
                                    <td class="px-3 py-2 text-sm text-gray-500">2005-01-15</td>
                                    <td class="px-3 py-2 text-sm text-gray-500">male</td>
                                    <td class="px-3 py-2 text-sm text-gray-500">1234567890</td>
                                    <td class="px-3 py-2 text-sm text-gray-500">123 Main St</td>
                                    <td class="px-3 py-2 text-sm text-gray-500">Jane Doe</td>
                                    <td class="px-3 py-2 text-sm text-gray-500">0987654321</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('students.import.template') }}" class="text-primary-600 hover:text-primary-800 font-medium">
                            <i class="fas fa-download mr-1"></i> Download Template
                        </a>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-file-import mr-1"></i> Import Students
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
