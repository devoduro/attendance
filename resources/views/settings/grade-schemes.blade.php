@extends('layouts.app')

@section('title', 'Grade Schemes')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Grade Schemes</h1>
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

    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Grading System</h2>
        
        <form action="{{ route('settings.update-grade-schemes') }}" method="POST" id="gradeForm">
            @csrf
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 mb-4">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Score</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Score</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pass/Fail</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="gradesTable">
                        @if(count($grades) > 0)
                            @foreach($grades as $index => $grade)
                                <tr class="grade-row">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="text" name="grades[{{ $index }}][grade]" class="form-input w-20" 
                                            value="{{ $grade['grade'] }}" maxlength="2" required>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" name="grades[{{ $index }}][min_score]" class="form-input w-24" 
                                            value="{{ $grade['min_score'] }}" min="0" max="100" required>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="number" name="grades[{{ $index }}][max_score]" class="form-input w-24" 
                                            value="{{ $grade['max_score'] }}" min="0" max="100" required>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <input type="radio" name="passing_grade" value="{{ $grade['grade'] }}" 
                                                {{ $grade['grade'] == $passingGrade ? 'checked' : '' }} 
                                                class="form-radio h-4 w-4 text-indigo-600">
                                            <span class="ml-2 text-sm text-gray-700">
                                                {{ $grade['is_passing'] ? 'Pass' : 'Fail' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button type="button" class="text-red-600 hover:text-red-900 remove-grade">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="grade-row">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="text" name="grades[0][grade]" class="form-input w-20" value="A" maxlength="2" required>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number" name="grades[0][min_score]" class="form-input w-24" value="80" min="0" max="100" required>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number" name="grades[0][max_score]" class="form-input w-24" value="100" min="0" max="100" required>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <input type="radio" name="passing_grade" value="A" checked class="form-radio h-4 w-4 text-indigo-600">
                                        <span class="ml-2 text-sm text-gray-700">Pass</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button type="button" class="text-red-600 hover:text-red-900 remove-grade">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <div class="flex justify-between items-center mt-4">
                <button type="button" id="addGrade" class="btn-secondary">
                    <i class="fas fa-plus mr-1"></i> Add Grade
                </button>
                
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-1"></i> Save Grading System
                </button>
            </div>
        </form>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Grading System Guide</h2>
        
        <div class="prose max-w-none">
            <p>The grading system defines how student scores are converted to letter grades. Here are some guidelines:</p>
            
            <ul class="list-disc pl-5 space-y-2 mt-2">
                <li>Each grade must have a unique range of scores.</li>
                <li>Ranges should not overlap.</li>
                <li>The passing grade determines which grades are considered passing.</li>
                <li>Grades are typically ordered from highest to lowest (e.g., A, B, C, D, F).</li>
                <li>The minimum score for the lowest grade is typically 0.</li>
                <li>The maximum score for the highest grade is typically 100.</li>
            </ul>
            
            <p class="mt-4">Example grading system:</p>
            
            <table class="min-w-full divide-y divide-gray-200 mt-2">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Grade</th>
                        <th class="px-4 py-2 text-left">Range</th>
                        <th class="px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-2">A</td>
                        <td class="px-4 py-2">80-100</td>
                        <td class="px-4 py-2">Pass</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">B</td>
                        <td class="px-4 py-2">70-79</td>
                        <td class="px-4 py-2">Pass</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">C</td>
                        <td class="px-4 py-2">60-69</td>
                        <td class="px-4 py-2">Pass</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">D</td>
                        <td class="px-4 py-2">50-59</td>
                        <td class="px-4 py-2">Pass</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">F</td>
                        <td class="px-4 py-2">0-49</td>
                        <td class="px-4 py-2">Fail</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add new grade row
        document.getElementById('addGrade').addEventListener('click', function() {
            const gradesTable = document.getElementById('gradesTable');
            const rows = gradesTable.querySelectorAll('.grade-row');
            const newIndex = rows.length;
            
            const newRow = document.createElement('tr');
            newRow.className = 'grade-row';
            
            newRow.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="text" name="grades[${newIndex}][grade]" class="form-input w-20" maxlength="2" required>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" name="grades[${newIndex}][min_score]" class="form-input w-24" min="0" max="100" required>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" name="grades[${newIndex}][max_score]" class="form-input w-24" min="0" max="100" required>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <input type="radio" name="passing_grade" class="form-radio h-4 w-4 text-indigo-600">
                        <span class="ml-2 text-sm text-gray-700">Select as passing</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button type="button" class="text-red-600 hover:text-red-900 remove-grade">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            gradesTable.appendChild(newRow);
            
            // Update the passing_grade value for the new radio button
            const newRadio = newRow.querySelector('input[type="radio"]');
            const gradeInput = newRow.querySelector('input[name^="grades"][name$="[grade]"]');
            
            gradeInput.addEventListener('input', function() {
                newRadio.value = this.value;
            });
            
            // Add remove event listener
            addRemoveEventListener(newRow.querySelector('.remove-grade'));
        });
        
        // Remove grade row
        function addRemoveEventListener(button) {
            button.addEventListener('click', function() {
                const row = this.closest('.grade-row');
                
                // Don't remove if it's the last row
                const rows = document.querySelectorAll('.grade-row');
                if (rows.length > 1) {
                    row.remove();
                    reindexRows();
                } else {
                    alert('You must have at least one grade in the system.');
                }
            });
        }
        
        // Reindex rows after removal
        function reindexRows() {
            const rows = document.querySelectorAll('.grade-row');
            rows.forEach((row, index) => {
                const gradeInput = row.querySelector('input[name^="grades"][name$="[grade]"]');
                const minInput = row.querySelector('input[name^="grades"][name$="[min_score]"]');
                const maxInput = row.querySelector('input[name^="grades"][name$="[max_score]"]');
                
                gradeInput.name = `grades[${index}][grade]`;
                minInput.name = `grades[${index}][min_score]`;
                maxInput.name = `grades[${index}][max_score]`;
            });
        }
        
        // Add remove event listeners to initial rows
        document.querySelectorAll('.remove-grade').forEach(button => {
            addRemoveEventListener(button);
        });
        
        // Form validation
        document.getElementById('gradeForm').addEventListener('submit', function(e) {
            const rows = document.querySelectorAll('.grade-row');
            const grades = [];
            let hasPassingGrade = false;
            
            rows.forEach(row => {
                const grade = row.querySelector('input[name^="grades"][name$="[grade]"]').value;
                const min = parseInt(row.querySelector('input[name^="grades"][name$="[min_score]"]').value);
                const max = parseInt(row.querySelector('input[name^="grades"][name$="[max_score]"]').value);
                const isPassingGrade = row.querySelector('input[type="radio"]').checked;
                
                if (isPassingGrade) {
                    hasPassingGrade = true;
                }
                
                grades.push({ grade, min, max });
                
                // Check min is less than or equal to max
                if (min > max) {
                    alert(`For grade ${grade}, minimum score (${min}) cannot be greater than maximum score (${max}).`);
                    e.preventDefault();
                    return;
                }
            });
            
            // Check for passing grade selection
            if (!hasPassingGrade) {
                alert('You must select at least one grade as the passing grade.');
                e.preventDefault();
                return;
            }
            
            // Check for overlapping ranges
            for (let i = 0; i < grades.length; i++) {
                for (let j = i + 1; j < grades.length; j++) {
                    if ((grades[i].min <= grades[j].max && grades[i].max >= grades[j].min) ||
                        (grades[j].min <= grades[i].max && grades[j].max >= grades[i].min)) {
                        alert(`Grade ranges for ${grades[i].grade} and ${grades[j].grade} overlap. Please adjust the ranges.`);
                        e.preventDefault();
                        return;
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
