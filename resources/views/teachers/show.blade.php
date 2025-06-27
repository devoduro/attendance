@extends('layouts.app')

@section('title', 'Teacher Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Teacher Details</h1>
        <div>
            @can('update', $teacher)
            <a href="{{ route('teachers.edit', $teacher) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md mr-2">
                Edit Teacher
            </a>
            @endcan
            <a href="{{ route('teachers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md">
                Back to Teachers
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="md:flex">
            <!-- Teacher Profile -->
            <div class="md:w-1/3 p-6 bg-gray-50 border-r border-gray-200">
                <div class="flex flex-col items-center text-center">
                    <div class="w-32 h-32 rounded-full bg-primary-100 flex items-center justify-center mb-4">
                        @if($teacher->profile_image)
                            <img src="{{ asset('storage/' . $teacher->profile_image) }}" alt="{{ $teacher->user->name }}" class="w-32 h-32 rounded-full object-cover">
                        @else
                            <span class="text-primary-800 font-bold text-4xl">{{ substr($teacher->user->name ?? 'T', 0, 1) }}</span>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $teacher->user->name }}</h2>
                    <p class="text-gray-500 mb-2">Teacher ID: {{ $teacher->teacher_id }}</p>
                    <p class="text-gray-500 mb-4">{{ $teacher->department->name ?? 'No Department' }}</p>
                    
                    <div class="w-full mt-4">
                        <div class="flex items-center py-2 border-t border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="text-gray-600">{{ $teacher->user->email }}</span>
                        </div>
                        @if($teacher->phone)
                        <div class="flex items-center py-2 border-t border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="text-gray-600">{{ $teacher->phone }}</span>
                        </div>
                        @endif
                        @if($teacher->qualification)
                        <div class="flex items-center py-2 border-t border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                            </svg>
                            <span class="text-gray-600">{{ $teacher->qualification }}</span>
                        </div>
                        @endif
                        <div class="flex items-center py-2 border-t border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-gray-600">Joined: {{ $teacher->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Teacher Details -->
            <div class="md:w-2/3 p-6">
                @if($teacher->bio)
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">About</h3>
                    <p class="text-gray-600">{{ $teacher->bio }}</p>
                </div>
                <hr class="my-6">
                @endif
                
                <!-- Subjects section removed -->
                
                <!-- Classes section removed -->
                
                <!-- Recent Activity -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Recent Activity</h3>
                    <div class="space-y-4">
                        @if(isset($recentExams) && $recentExams->count() > 0)
                            @foreach($recentExams as $exam)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Created exam: {{ $exam->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $exam->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500">No recent activity found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
