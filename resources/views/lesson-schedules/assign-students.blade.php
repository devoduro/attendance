@extends('layouts.app')

@section('title', 'Assign Students to Lesson Schedule')

@section('content')
    <div class="flex flex-col md:flex-row items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Assign Students to Lesson Schedule</h1>
        <a href="{{ route('lesson-schedules.show', $lessonSchedule->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Schedule
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <span class="text-green-500">&times;</span>
            </button>
        </div>
    @endif

    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Schedule Information</h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm"><span class="font-medium">Centre:</span> {{ $lessonSchedule->centre->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm"><span class="font-medium">Day:</span> {{ $lessonSchedule->day_of_week }}</p>
                    </div>
                    <div>
                        <p class="text-sm"><span class="font-medium">Time:</span> {{ $lessonSchedule->lessonSection->start_time }} - {{ $lessonSchedule->lessonSection->end_time }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <p class="text-sm"><span class="font-medium">Teacher:</span> {{ $lessonSchedule->teacher->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm"><span class="font-medium">Subject:</span> {{ $lessonSchedule->subject->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm"><span class="font-medium">Start Date:</span> {{ $lessonSchedule->start_date->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <p class="text-sm"><span class="font-medium">End Date:</span> {{ $lessonSchedule->end_date ? $lessonSchedule->end_date->format('d M Y') : 'Ongoing' }}</p>
                    </div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Assign Students</h3>
            </div>
            <div class="p-4">
                <form action="{{ route('lesson-schedules.assign-students.store', $lessonSchedule->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Students</label>
                        <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="search" placeholder="Type to search students...">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="centre-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Centre</label>
                            <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="centre-filter">
                                <option value="">All Centres</option>
                                @foreach($centres as $centre)
                                    <option value="{{ $centre->id }}" {{ $centre->id == $lessonSchedule->centre_id ? 'selected' : '' }}>
                                        {{ $centre->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="subject-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Subject</label>
                            <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" id="subject-filter">
                                <option value="">All Subjects</option>
                                @foreach(\App\Models\Subject::where('status', 'active')->orderBy('name')->get() as $subject)
                                    <option value="{{ $subject->id }}" {{ $subject->id == $lessonSchedule->subject_id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="studentsTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <input type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" id="selectAll">
                                            <label class="ml-2 text-xs font-medium text-gray-500 uppercase tracking-wider" for="selectAll">Select All</label>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Centre</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent/Guardian</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $student)
                                    <tr class="student-row hover:bg-gray-50" data-centre="{{ $student->centre_id }}" data-subjects="">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <input type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 student-checkbox" 
                                                    id="student{{ $student->id }}" 
                                                    name="student_ids[]" 
                                                    value="{{ $student->id }}"
                                                    {{ in_array($student->id, $enrolledStudentIds) ? 'checked' : '' }}>
                                                <label class="sr-only" for="student{{ $student->id }}">Select student</label>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->getAge() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->centre->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->school_attending ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->parent_guardian_name ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i> Save Assignments
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable with Tailwind styling
        const table = $('#studentsTable').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": false,
            "language": {
                "paginate": {
                    "previous": "<i class='fas fa-chevron-left'></i>",
                    "next": "<i class='fas fa-chevron-right'></i>"
                }
            },
            "drawCallback": function() {
                // Apply Tailwind classes to pagination elements
                $('.dataTables_paginate > .pagination').addClass('flex justify-center mt-4');
                $('.dataTables_paginate .paginate_button').addClass('px-3 py-1 mx-1 text-sm rounded-md border border-gray-300');
                $('.dataTables_paginate .paginate_button.current').addClass('bg-blue-600 text-white border-blue-600');
                $('.dataTables_paginate .paginate_button:not(.current)').addClass('bg-white text-gray-700 hover:bg-gray-50');
            }
        });
        
        // Handle select all checkbox
        $('#selectAll').on('change', function() {
            $('.student-checkbox:visible').prop('checked', $(this).prop('checked'));
        });
        
        // Handle search
        $('#search').on('keyup', function() {
            table.search(this.value).draw();
        });
        
        // Handle centre filter
        $('#centre-filter').on('change', function() {
            filterStudents();
        });
        
        // Handle subject filter
        $('#subject-filter').on('change', function() {
            filterStudents();
        });
        
        // Combined filter function
        function filterStudents() {
            const centreId = $('#centre-filter').val();
            const subjectId = $('#subject-filter').val();
            
            // Show all rows initially
            $('.student-row').show();
            
            // Apply centre filter if selected
            if (centreId) {
                $('.student-row:not([data-centre="' + centreId + '"])').hide();
            }
            
            // Apply subject filter if selected
            if (subjectId) {
                // Disable subject filtering since we don't have subject data
                // This will be re-implemented when the subjects relationship is properly set up
                console.log('Subject filtering is currently disabled');
            }
            
            // Update "Select All" checkbox state
            updateSelectAllState();
        };
        
        // Update "Select All" checkbox state based on visible checkboxes
        function updateSelectAllState() {
            const visibleCheckboxes = $('.student-checkbox:visible');
            const checkedCheckboxes = $('.student-checkbox:visible:checked');
            
            $('#selectAll').prop('checked', 
                visibleCheckboxes.length > 0 && 
                visibleCheckboxes.length === checkedCheckboxes.length
            );
        }
        
        // Initial filter by centre if default is selected
        $('#centre-filter').trigger('change');
    });
</script>
@endsection
