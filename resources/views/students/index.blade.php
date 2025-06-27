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
                <div class="relative">
                    <input type="text" id="quick-search" name="query" placeholder="Search by name, ID, phone or email" 
                        class="form-input rounded-md flex-1 w-full" value="{{ isset($query) ? $query : '' }}">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <div id="search-results" class="absolute z-10 w-full mt-1 bg-white rounded-md shadow-lg hidden"></div>
                </div>
                <form id="search-form" action="{{ route('students.index') }}" method="GET" class="hidden">
                    <input type="hidden" id="search-query" name="query" value="{{ isset($query) ? $query : '' }}">
                </form>
            </div>

            <!-- Program filter removed as it's no longer supported by the controller -->

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

    <!-- Bulk Actions -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
        <form id="bulk-actions-form" action="{{ route('students.bulk-actions') }}" method="POST">
            @csrf
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex-grow md:flex-grow-0">
                    <select name="bulk_action" class="form-select w-full" required>
                        <option value="">-- Select Bulk Action --</option>
                        <option value="activate">Activate Selected</option>
                        <option value="deactivate">Deactivate Selected</option>
                        <option value="export">Export Selected</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                </div>
                <button type="submit" class="btn-secondary" onclick="return confirmBulkAction()">
                    Apply
                </button>
                <div class="ml-auto">
                    <span class="text-sm text-gray-600" id="selected-count">0 students selected</span>
                </div>
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($students->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <input type="checkbox" id="select-all" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                                    <label for="select-all" class="sr-only">Select All</label>
                                </div>
                            </th>
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
                            <tr class="student-row hover:bg-gray-50 cursor-pointer" data-student-id="{{ $student->id }}">
                                <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="selected_students[]" value="{{ $student->id }}" class="student-checkbox form-checkbox h-4 w-4 text-blue-600 rounded">
                                    </div>
                                </td>
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
                                    <div class="text-sm text-gray-900">N/A</div>
                                    <div class="text-xs text-gray-500">Program info removed</div>
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
                                    <!-- Results link removed as route 'students.results' is not defined -->
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

<!-- Student Quick View Modal -->
<div id="student-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="border-b px-6 py-4 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900" id="modal-title">Student Details</h3>
            <button type="button" id="close-modal" class="text-gray-400 hover:text-gray-500">
                <span class="sr-only">Close</span>
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6" id="modal-content">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Student Profile Section -->
                <div class="md:w-1/3">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-32 h-32 mb-4">
                            <img id="student-photo" class="w-32 h-32 rounded-full object-cover" src="" alt="Student Photo">
                        </div>
                        <h4 id="student-name" class="text-xl font-bold"></h4>
                        <p id="student-id" class="text-gray-500 mb-2"></p>
                        <div id="student-status" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"></div>
                    </div>
                    <div class="mt-6">
                        <a id="view-profile-link" href="#" class="btn-primary w-full text-center mb-2">
                            <i class="fas fa-user mr-1"></i> View Full Profile
                        </a>
                        <a id="edit-profile-link" href="#" class="btn-secondary w-full text-center">
                            <i class="fas fa-edit mr-1"></i> Edit Profile
                        </a>
                    </div>
                </div>
                
                <!-- Student Details Section -->
                <div class="md:w-2/3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h5 class="font-bold mb-2">Academic Information</h5>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-gray-500">Class:</span>
                                    <span id="student-class" class="font-medium"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Admission Date:</span>
                                    <span id="student-admission-date" class="font-medium"></span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h5 class="font-bold mb-2">Contact Information</h5>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-gray-500">Email:</span>
                                    <span id="student-email" class="font-medium"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Phone:</span>
                                    <span id="student-phone" class="font-medium"></span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Guardian:</span>
                                    <span id="student-guardian" class="font-medium"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h5 class="font-bold mb-2">Recent Attendance</h5>
                        <div id="attendance-chart" class="h-24 flex items-center justify-center bg-gray-50 rounded">
                            <span class="text-gray-400">Loading attendance data...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');
        const selectedCountElement = document.getElementById('selected-count');
        const bulkActionsForm = document.getElementById('bulk-actions-form');
        const quickSearchInput = document.getElementById('quick-search');
        const searchResultsContainer = document.getElementById('search-results');
        const searchForm = document.getElementById('search-form');
        const searchQueryInput = document.getElementById('search-query');
        
        // Handle "Select All" checkbox
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            
            updateSelectedCount();
        });
        
        // Handle individual checkboxes
        studentCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedCount();
                
                // Update "Select All" checkbox state
                const allChecked = Array.from(studentCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(studentCheckboxes).some(cb => cb.checked);
                
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            });
        });
        
        // Update the selected count display
        function updateSelectedCount() {
            const selectedCount = Array.from(studentCheckboxes).filter(cb => cb.checked).length;
            selectedCountElement.textContent = `${selectedCount} student${selectedCount !== 1 ? 's' : ''} selected`;
        }
        
        // Quick Search functionality
        let searchTimeout;
        
        quickSearchInput.addEventListener('input', function() {
            const query = this.value.trim();
            searchQueryInput.value = query;
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                searchResultsContainer.classList.add('hidden');
                searchResultsContainer.innerHTML = '';
                return;
            }
            
            // Set a timeout to avoid making too many requests while typing
            searchTimeout = setTimeout(() => {
                // Make AJAX request to search for students
                fetch(`/api/students/search?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            // Display search results
                            searchResultsContainer.innerHTML = '';
                            searchResultsContainer.classList.remove('hidden');
                            
                            data.forEach(student => {
                                const resultItem = document.createElement('div');
                                resultItem.className = 'p-2 hover:bg-gray-100 cursor-pointer flex items-center';
                                resultItem.innerHTML = `
                                    <div class="flex-shrink-0 h-8 w-8 mr-2">
                                        <img class="h-8 w-8 rounded-full" src="${student.profile_photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(student.name)}&color=7F9CF5&background=EBF4FF`}" alt="${student.name}">
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">${student.name}</div>
                                        <div class="text-xs text-gray-500">${student.enrollment_code || ''}</div>
                                    </div>
                                `;
                                
                                resultItem.addEventListener('click', () => {
                                    window.location.href = `/students/${student.id}`;
                                });
                                
                                searchResultsContainer.appendChild(resultItem);
                            });
                            
                            // Add "View all results" link
                            const viewAllItem = document.createElement('div');
                            viewAllItem.className = 'p-2 text-center text-blue-600 hover:bg-gray-100 cursor-pointer border-t';
                            viewAllItem.textContent = 'View all results';
                            viewAllItem.addEventListener('click', () => {
                                searchForm.submit();
                            });
                            
                            searchResultsContainer.appendChild(viewAllItem);
                        } else {
                            searchResultsContainer.innerHTML = '<div class="p-2 text-center text-gray-500">No results found</div>';
                            searchResultsContainer.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error searching students:', error);
                    });
            }, 300);
        });
        
        // Hide search results when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchResultsContainer.contains(event.target) && event.target !== quickSearchInput) {
                searchResultsContainer.classList.add('hidden');
            }
        });
        
        // Submit search form on Enter key
        quickSearchInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchForm.submit();
            }
        });
        
        // Student Modal Functionality
        const studentModal = document.getElementById('student-modal');
        const closeModalBtn = document.getElementById('close-modal');
        const studentRows = document.querySelectorAll('.student-row');
        
        // Close modal when clicking the close button
        closeModalBtn.addEventListener('click', function() {
            studentModal.classList.add('hidden');
        });
        
        // Close modal when clicking outside the modal content
        studentModal.addEventListener('click', function(event) {
            if (event.target === studentModal) {
                studentModal.classList.add('hidden');
            }
        });
        
        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !studentModal.classList.contains('hidden')) {
                studentModal.classList.add('hidden');
            }
        });
        
        // Open modal when clicking on a student row
        studentRows.forEach(row => {
            row.addEventListener('click', function() {
                const studentId = this.getAttribute('data-student-id');
                openStudentModal(studentId);
            });
        });
        
        // Function to open student modal with data
        function openStudentModal(studentId) {
            // Show loading state
            document.getElementById('modal-content').innerHTML = `
                <div class="flex justify-center items-center h-64">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                </div>
            `;
            
            // Show the modal
            studentModal.classList.remove('hidden');
            
            // Fetch student data
            fetch(`/api/students/${studentId}`)
                .then(response => response.json())
                .then(student => {
                    // Update modal title
                    document.getElementById('modal-title').textContent = `Student: ${student.user.name}`;
                    
                    // Update modal content
                    document.getElementById('modal-content').innerHTML = `
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Student Profile Section -->
                            <div class="md:w-1/3">
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-32 h-32 mb-4">
                                        <img id="student-photo" class="w-32 h-32 rounded-full object-cover" 
                                            src="${student.user.profile_photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(student.user.name)}&color=7F9CF5&background=EBF4FF`}" 
                                            alt="${student.user.name}">
                                    </div>
                                    <h4 id="student-name" class="text-xl font-bold">${student.user.name}</h4>
                                    <p id="student-id" class="text-gray-500 mb-2">${student.enrollment_code}</p>
                                    <div id="student-status" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${student.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                        ${student.status === 'active' ? 'Active' : 'Inactive'}
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <a href="/students/${student.id}" class="btn-primary w-full text-center mb-2">
                                        <i class="fas fa-user mr-1"></i> View Full Profile
                                    </a>
                                    <a href="/students/${student.id}/edit" class="btn-secondary w-full text-center">
                                        <i class="fas fa-edit mr-1"></i> Edit Profile
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Student Details Section -->
                            <div class="md:w-2/3">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h5 class="font-bold mb-2">Academic Information</h5>
                                        <div class="space-y-2">
                                            <div>
                                                <span class="text-gray-500">Class:</span>
                                                <span class="font-medium">${student.class ? student.class.name : 'Not Assigned'}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">House:</span>
                                                <span class="font-medium">${student.house ? student.house.name : 'Not Assigned'}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Admission Date:</span>
                                                <span class="font-medium">${student.admission_date || 'Not Set'}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h5 class="font-bold mb-2">Contact Information</h5>
                                        <div class="space-y-2">
                                            <div>
                                                <span class="text-gray-500">Email:</span>
                                                <span class="font-medium">${student.email || 'N/A'}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Phone:</span>
                                                <span class="font-medium">${student.mobile_phone || 'N/A'}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Guardian:</span>
                                                <span class="font-medium">${student.guardians_name || 'N/A'}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    <h5 class="font-bold mb-2">Recent Attendance</h5>
                                    <div class="h-24 flex items-center justify-center bg-gray-50 rounded">
                                        <span class="text-gray-400">Attendance data will be displayed here</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error fetching student details:', error);
                    document.getElementById('modal-content').innerHTML = `
                        <div class="text-center py-8 text-red-500">
                            <i class="fas fa-exclamation-circle text-4xl mb-4"></i>
                            <p>Error loading student details. Please try again.</p>
                        </div>
                    `;
                });
        }
        
        // Confirm bulk actions
        window.confirmBulkAction = function() {
            const selectedCount = Array.from(studentCheckboxes).filter(cb => cb.checked).length;
            const action = bulkActionsForm.querySelector('select[name="bulk_action"]').value;
            
            if (selectedCount === 0) {
                alert('Please select at least one student.');
                return false;
            }
            
            if (action === 'delete') {
                return confirm(`Are you sure you want to delete ${selectedCount} selected student${selectedCount !== 1 ? 's' : ''}? This action cannot be undone.`);
            } else if (action === 'deactivate') {
                return confirm(`Are you sure you want to deactivate ${selectedCount} selected student${selectedCount !== 1 ? 's' : ''}?`);
            }
            
            return true;
        };
    });
</script>
@endpush

@endsection
