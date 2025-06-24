/**
 * Exam Questions Management JavaScript - Debug Version
 * Handles AJAX functionality for searching, adding, and managing exam questions
 */

$(document).ready(function() {
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken || $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    console.log('Debug: Using CSRF token:', csrfToken || $('meta[name="csrf-token"]').attr('content'));

    console.log('Debug: Exam Questions JS loaded');
    console.log('Debug: questionsSearchUrl =', questionsSearchUrl);
    console.log('Debug: examId =', examId);
    console.log('Debug: subjectId =', subjectId);
    console.log('Debug: CSRF Token =', $('meta[name="csrf-token"]').attr('content'));

    // Initialize Select2 for better dropdown experience
    $('.select2').select2({
        theme: 'bootstrap4',
    });

    // Load questions when the add tab is shown
    $('#add-tab').on('shown.bs.tab', function() {
        console.log('Debug: Add tab shown, loading questions');
        loadQuestions();
    });

    // Filter button click handler
    $('#filter-btn').click(function(e) {
        e.preventDefault();
        console.log('Debug: Filter button clicked');
        loadQuestions();
    });

    // Clear filters button click handler
    $('#clear-filters').click(function(e) {
        e.preventDefault();
        console.log('Debug: Clear filters button clicked');
        $('#category, #tag, #difficulty, #type').val('').trigger('change');
        $('#search-query').val('');
        loadQuestions();
    });

    // Search input handler with debounce
    let searchTimeout;
    $('#search-query').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            console.log('Debug: Search query changed');
            loadQuestions();
        }, 500); // 500ms debounce
    });

    // Function to load questions with filters
    function loadQuestions() {
        const categoryId = $('#category').val();
        const tagId = $('#tag').val();
        const difficulty = $('#difficulty').val();
        const questionType = $('#type').val();
        const searchQuery = $('#search-query').val();
        
        console.log('Debug: Loading questions with filters:', {
            category_id: categoryId,
            tag_id: tagId,
            difficulty: difficulty,
            question_type: questionType,
            exam_id: examId,
            subject_id: subjectId,
            search_query: searchQuery
        });
        
        // Show loading state
        $('#questions-list').html(`
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading questions...</p>
                </td>
            </tr>
        `);

        // Disable filter buttons during loading
        $('#filter-btn, #clear-filters').prop('disabled', true);
        
        // Show loading indicator if it exists
        $('#loading-indicator').removeClass('d-none');
        
        $.ajax({
            url: questionsSearchUrl,
            method: 'GET',
            data: {
                category_id: categoryId,
                tag_id: tagId,
                difficulty: difficulty,
                question_type: questionType,
                exam_id: examId,
                subject_id: subjectId,
                search_query: searchQuery
            },
            cache: false,
            timeout: 30000, // 30 second timeout
            headers: {
                'X-CSRF-TOKEN': csrfToken || $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            beforeSend: function(xhr) {
                console.log('Debug: Before sending AJAX request');
                // Ensure CSRF token is set in the header
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken || $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response) {
                console.log('Debug: AJAX success response received');
                
                // Re-enable filter buttons
                $('#filter-btn, #clear-filters').prop('disabled', false);
                
                // Hide loading indicator if it exists
                $('#loading-indicator').addClass('d-none');
                
                if (response.success && response.questions) {
                    console.log('Debug: Questions received:', response.questions.length);
                    const questions = response.questions;
                    
                    if (questions.length === 0) {
                        $('#questions-list').html(`
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No questions found matching your criteria. Try adjusting your filters.
                                    </div>
                                </td>
                            </tr>
                        `);
                        return;
                    }
                    
                    let html = '';
                    
                    questions.forEach(function(question) {
                        html += `
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input question-checkbox" type="checkbox" name="question_ids[]" value="${question.id}" id="question-${question.id}">
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>#${question.id}</strong>: ${question.question_text.substring(0, 100)}${question.question_text.length > 100 ? '...' : ''}
                                    </div>
                                    <div class="mt-1">
                                        <span class="badge bg-secondary">
                                            ${question.marks} marks
                                        </span>
                                        ${question.tags ? question.tags.map(tag => `<span class="badge bg-info ms-1">${tag.name}</span>`).join('') : ''}
                                    </div>
                                </td>
                                <td>
                                    <span class="d-flex align-items-center">
                                        <i class="fas ${question.question_type === 'multiple_choice' ? 'fa-list-ul' : (question.question_type === 'true_false' ? 'fa-toggle-on' : (question.question_type === 'short_answer' ? 'fa-comment-alt' : 'fa-paragraph'))} me-2 text-primary"></i>
                                        ${question.question_type.charAt(0).toUpperCase() + question.question_type.slice(1).replace('_', ' ')}
                                    </span>
                                </td>
                                <td>${question.category ? question.category.name : 'N/A'}</td>
                                <td>
                                    <span class="badge bg-${question.difficulty === 'easy' ? 'success' : question.difficulty === 'medium' ? 'warning' : 'danger'}">
                                        ${question.difficulty.charAt(0).toUpperCase() + question.difficulty.slice(1)}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-info preview-question" data-id="${question.id}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    
                    $('#questions-list').html(html);
                    
                    // Update pagination
                    updatePagination(response.pagination);
                    
                    // Bind preview button events
                    bindPreviewButtons();
                    
                    // Initialize checkboxes
                    $('.question-checkbox').change(function() {
                        updateSelectedCount();
                        updateAddButtonState();
                    });
                } else {
                    console.error('Debug: Invalid response format:', response);
                    $('#questions-list').html(`
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Error loading questions. Invalid response format.
                                </div>
                            </td>
                        </tr>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('Debug: AJAX error:', status, error);
                console.log('Debug: Response text:', xhr.responseText);
                console.log('Debug: Status code:', xhr.status);
                
                // Re-enable filter buttons
                $('#filter-btn, #clear-filters').prop('disabled', false);
                
                // Hide loading indicator
                $('#loading-indicator').addClass('d-none');
                
                // Show error message
                $('#questions-list').html(`
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Error loading questions: ${error}. Status: ${status}. Code: ${xhr.status}
                                <br><br>
                                <strong>Try refreshing the page or check the browser console for more details.</strong>
                            </div>
                        </td>
                    </tr>
                `);
            }
        });
    }

    // Handle form submission for adding questions to the exam
    $('#add-selected-btn').click(function(e) {
        e.preventDefault();
        
        const selectedQuestions = [];
        $('.question-checkbox:checked').each(function() {
            selectedQuestions.push($(this).val());
        });
        
        console.log('Debug: Selected questions:', selectedQuestions);
        console.log('Debug: Add questions URL:', addQuestionsUrl);
        
        if (selectedQuestions.length === 0) {
            alert('Please select at least one question to add.');
            return;
        }
        
        // Get the marks value
        const marks = $('#marks-value').val();
        console.log('Debug: Marks value:', marks);
        
        // Create a hidden form with the selected questions
        const form = $('#add-questions-form');
        
        // Clear any existing hidden inputs for question_ids
        form.find('input[name="question_ids[]"]').remove();
        
        // Add hidden inputs for each selected question
        selectedQuestions.forEach(function(questionId) {
            form.append(`<input type="hidden" name="question_ids[]" value="${questionId}">`);
        });
        
        // Set the marks value
        form.find('input[name="marks"]').val(marks);
        
        // Submit the form
        console.log('Debug: Submitting form with data:', {
            question_ids: selectedQuestions,
            marks: marks,
            csrf_token: csrfToken
        });
        
        form.submit();
    });
    
    // Select all questions checkbox
    $('#select-all').change(function() {
        $('.question-checkbox').prop('checked', $(this).prop('checked'));
        updateAddButtonState();
    });
    
    // Individual question checkbox change handler
    $(document).on('change', '.question-checkbox', function() {
        updateAddButtonState();
    });
    
    // Update add button state
    function updateAddButtonState() {
        const selectedCount = $('.question-checkbox:checked').length;
        $('#add-selected-btn').prop('disabled', selectedCount === 0);
        
        // Update the button text to show count
        if (selectedCount > 0) {
            $('#add-selected-btn').html(`<i class="fas fa-plus me-1"></i> Add ${selectedCount} Question${selectedCount !== 1 ? 's' : ''}`);
        } else {
            $('#add-selected-btn').html(`<i class="fas fa-plus me-1"></i> Add Selected Questions`);
        }
    }
});

