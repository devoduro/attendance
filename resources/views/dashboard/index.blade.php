@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Stats Cards -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-primary-100 text-primary-800">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-gray-600 text-sm font-medium">Total Students</h2>
                <p class="text-3xl font-bold text-gray-900">{{ $totalStudents ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('students.index') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">View all students →</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-800">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-gray-600 text-sm font-medium">Total Classes</h2>
                <p class="text-3xl font-bold text-gray-900">{{ $totalClasses ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('classes.index') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">View all classes →</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-800">
                <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <div class="ml-4">
                <h2 class="text-gray-600 text-sm font-medium">Active Exams</h2>
                <p class="text-3xl font-bold text-gray-900">{{ $activeExams ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('exams.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">View all exams →</a>
        </div>
    </div>
</div>

<!-- Role-specific Dashboard Content -->
@role('admin')
<div class="mt-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">System Overview</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
            <div class="space-y-4">
                @forelse($recentActivity ?? [] as $activity)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-800">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">{{ $activity->description }}</p>
                        <p class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500">No recent activity found.</p>
                @endforelse
            </div>
        </div>

        <!-- System Health -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">System Health</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Database</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Healthy</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Storage</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">{{ $storageUsage ?? '10%' }} used</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-700">Last Backup</span>
                    <span class="text-sm text-gray-500">{{ $lastBackup ?? 'Never' }}</span>
                </div>
                <div class="mt-4">
                    <a href="{{ route('settings.backup') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Create Backup
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endrole

@role('teacher')
<div class="mt-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Teacher Dashboard</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- My Classes -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">My Classes</h3>
            <div class="space-y-4">
                @forelse($teacherClasses ?? [] as $class)
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $class->name }}</p>
                        <p class="text-xs text-gray-500">{{ $class->students_count }} students</p>
                    </div>
                    <a href="{{ route('classes.show', $class) }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">View</a>
                </div>
                @empty
                <p class="text-gray-500">No classes assigned yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Exams -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Exams</h3>
            <div class="space-y-4">
                @forelse($recentExams ?? [] as $exam)
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $exam->title }}</p>
                        <p class="text-xs text-gray-500">{{ $exam->subject->name }} • {{ $exam->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $exam->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($exam->status) }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500">No exams created yet.</p>
                @endforelse
            </div>
            <div class="mt-4">
                <a href="{{ route('exams.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Create New Exam
                </a>
            </div>
        </div>
    </div>
    
    <!-- My Students Section -->
    <div class="mt-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">My Students</h3>
                <p class="text-sm text-gray-500">Students assigned to your classes</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollment Code</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($teacherStudents ?? [] as $student)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $student->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $student->class->name ?? 'Not Assigned' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $student->enrollment_code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('students.show', $student) }}" class="text-primary-600 hover:text-primary-900 mr-3">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                No students found in your assigned classes.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                <a href="{{ route('students.index') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">View all my students →</a>
            </div>
        </div>
    </div>
</div>
@endrole

@role('student')
<div class="mt-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Student Dashboard</h2>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Active Exams -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Active Exams</h3>
            </div>
            <div class="p-6">
                @forelse($studentActiveExams ?? [] as $exam)
                <div class="mb-6 last:mb-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-base font-medium text-gray-900">{{ $exam->title }}</h4>
                            <p class="text-sm text-gray-500">{{ $exam->subject->name }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ $exam->duration }} minutes • Ends {{ $exam->end_time ? $exam->end_time->format('M d, Y, h:i A') : 'No end date' }}</span>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('student.exams.show', $exam) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Start Exam
                        </a>
                    </div>
                </div>
                @empty
                <p class="text-gray-500">No active exams available.</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Results -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Results</h3>
            </div>
            <div class="p-6">
                @forelse($studentRecentResults ?? [] as $result)
                <div class="mb-4 last:mb-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">{{ $result->exam->title }}</h4>
                            <p class="text-xs text-gray-500">{{ $result->completed_at->format('M d, Y') }}</p>
                        </div>
                        <span class="text-sm font-medium {{ $result->score >= $result->exam->passing_score ? 'text-green-600' : 'text-red-600' }}">
                            {{ $result->score }}%
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500">No exam results yet.</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Classmates Section -->
    <div class="mt-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">My Classmates</h3>
                <p class="text-sm text-gray-500">Students in {{ $currentClass->name ?? 'your class' }}</p>
            </div>
            <div class="p-6">
                @if(isset($classmates) && count($classmates) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($classmates as $classmate)
                            <div class="flex items-center p-3 border rounded-lg hover:bg-gray-50">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500 font-medium">{{ substr($classmate->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $classmate->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $classmate->admission_number }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($classmatesCount > 10)
                        <div class="mt-4 text-center">
                            <a href="{{ route('students.index') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">View all {{ $classmatesCount }} classmates →</a>
                        </div>
                    @endif
                @else
                    <p class="text-gray-500">No other students in your class.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endrole
@endsection
