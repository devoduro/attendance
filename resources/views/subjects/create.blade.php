@extends('layouts.app')

@section('title', 'Add New Subject')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">Add New Subject</h1>
                <a href="{{ route('subjects.index') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Subjects
                </a>
            </div>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="mb-6 bg-danger-50 border-l-4 border-danger-500 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-danger-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-danger-800">There were errors with your submission</h3>
                            <div class="mt-2 text-sm text-danger-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('subjects.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Subject Name</label>
                        <div class="mt-1">
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md {{ $errors->has('name') ? 'border-danger-300 text-danger-900 placeholder-danger-300 focus:outline-none focus:ring-danger-500 focus:border-danger-500' : '' }}"
                                placeholder="Enter subject name">
                            @error('name')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">Subject Code</label>
                        <div class="mt-1">
                            <input type="text" id="code" name="code" value="{{ old('code') }}" required 
                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md {{ $errors->has('code') ? 'border-danger-300 text-danger-900 placeholder-danger-300 focus:outline-none focus:ring-danger-500 focus:border-danger-500' : '' }}"
                                placeholder="Enter subject code (e.g. MATH101)">
                            @error('code')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="centre_id" class="block text-sm font-medium text-gray-700">Centre</label>
                        <div class="mt-1">
                            <select id="centre_id" name="centre_id" required 
                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md {{ $errors->has('centre_id') ? 'border-danger-300 text-danger-900 placeholder-danger-300 focus:outline-none focus:ring-danger-500 focus:border-danger-500' : '' }}">
                                <option value="">Select Centre</option>
                                @foreach($centres as $centre)
                                    <option value="{{ $centre->id }}" {{ old('centre_id') == $centre->id ? 'selected' : '' }}>{{ $centre->name }}</option>
                                @endforeach
                            </select>
                            @error('centre_id')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="3" 
                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md {{ $errors->has('description') ? 'border-danger-300 text-danger-900 placeholder-danger-300 focus:outline-none focus:ring-danger-500 focus:border-danger-500' : '' }}"
                                placeholder="Enter subject description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-1">
                            <select id="status" name="status" required 
                                class="shadow-sm focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md {{ $errors->has('status') ? 'border-danger-300 text-danger-900 placeholder-danger-300 focus:outline-none focus:ring-danger-500 focus:border-danger-500' : '' }}">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-5">
                        <a href="{{ route('subjects.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Subject
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
