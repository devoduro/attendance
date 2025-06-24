/**
 * Exam Questions Management JavaScript
 * Handles AJAX functionality for searching, adding, and managing exam questions
 */

$(document).ready(function() {
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize Select2 for better dropdown experience
    $('.select2').select2({
        theme: 'bootstrap4',
    });

    // Load questions when the add tab is shown
    $('#add-tab').on('shown.bs.tab', function() {
        loadQuestions();
    });

    // Filter button click handler
    $('#filter-btn').click(function(e) {
        e.preventDefault();
        loadQuestions();
    });

    // Clear filters button click handler
    $('#clear-filters').click(function(e) {
        e.preventDefault();
        $('#category, #tag, #difficulty, #type').val('').trigger('change');
        $('#search-query').val('');
        loadQuestions();
    });

    // Search input handler with debounce
    let searchTimeout;
    $('#search-query').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            loadQuestions();
        }, 500); // 500ms debounce
    });

    // Select all questions checkbox
    $('#select-all-questions').change(function() {
        $('.question-checkbox').prop('checked', $(this).prop('checked'));
        updateSelectedCount();
        updateAddButtonState();
    });

    // Individual question checkbox change handler
    $(document).on('change', '.question-checkbox', function() {
        updateSelectedCount();
        updateAddButtonState();
    });

    // Update selected count
    function updateSelectedCount() {
        const count = $('.question-checkbox:checked').length;
        $('#selected-count').text(count);
    }

    // Update add button state
    function updateAddButtonState() {
        const selectedCount = $('.question-checkbox:checked').length;
        $('#add-questions-btn').prop('disabled', selectedCount === 0);
    }

    // Load questions via AJAX
    function loadQuestions() {
        const categoryId = $('#category').val();
        const tagId = $('#tag').val();
        const difficulty = $('#difficulty').val();
        const questionType = $('#type').val();
        const searchQuery = $('#search-query').val();
        
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
        
        console.log('Sending AJAX request to:', questionsSearchUrl);
        console.log('With exam ID:', examId);
        console.log('With subject ID:', subjectId);
        console.log('With filters:', {
            category_id: categoryId,
            tag_id: tagId,
            difficulty: difficulty,
            question_type: questionType,
            exam_id: examId,
            subject_id: subjectId,
            search_query: searchQuery
        });
        
        // Check if CSRF token is properly set
        console.log('CSRF token:', $('meta[name="csrf-token"]').attr('content'));
        
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
            success: function(response) {
                console.log('AJAX success response:', response);
                
                // Re-enable filter buttons
                $('#filter-btn, #clear-filters').prop('disabled', false);
                
                // Hide loading indicator if it exists
                $('#loading-indicator').addClass('d-none');
                
                if (response.success && response.questions) {
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
                                    <div class="question-text">
                                        ${question.question_text}
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge bg-${question.difficulty === 'easy' ? 'success' : question.difficulty === 'medium' ? 'warning' : 'danger'}">
                                            ${question.difficulty.charAt(0).toUpperCase() + question.difficulty.slice(1)}
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
                    
                    // Update pagination if available
                    if (response.pagination) {
                        updatePagination(response.pagination);
                    }
                    
                    // Enable select all checkbox
                    $('#select-all-questions').prop('disabled', false);
                    
                    // Update counts
                    updateSelectedCount();
                    updateAddButtonState();
                    
                    // Attach event handler to preview buttons
                    $('.preview-question').click(function() {
                        const questionId = $(this).data('id');
                        previewQuestion(questionId);
                    });
                } else {
                    $('#questions-list').html(`
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Error loading questions. Please try again.
                                </div>
                            </td>
                        </tr>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', xhr.responseText);
                console.error('Status:', status);
                console.error('Error:', error);
                
                // Re-enable filter buttons
                $('#filter-btn, #clear-filters').prop('disabled', false);
                
                // Hide loading indicator if it exists
                $('#loading-indicator').addClass('d-none');
                
                $('#questions-list').html(`
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Error loading questions: ${error}. Please try again.
                            </div>
                        </td>
                    </tr>
                `);
            }
        });
    }

    // Update pagination UI
    function updatePagination(pagination) {
        if (!pagination) return;
        
        const totalPages = pagination.last_page;
        const currentPage = pagination.current_page;
        
        let paginationHtml = '';
        
        // Previous button
        paginationHtml += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        `;
        
        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            paginationHtml += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }
        
        // Next button
        paginationHtml += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        `;
        
        $('#questions-pagination').html(paginationHtml);
        
        // Attach click handlers to pagination links
        $('.page-link').click(function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            loadQuestionsPage(page);
        });
    }

    // Load a specific page of questions
    function loadQuestionsPage(page) {
        const categoryId = $('#category').val();
        const tagId = $('#tag').val();
        const difficulty = $('#difficulty').val();
        const questionType = $('#type').val();
        const searchQuery = $('#search-query').val();
        
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
                search_query: searchQuery,
                page: page
            },
            success: function(response) {
                // Use the same handler as loadQuestions
                if (response.success && response.questions) {
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
                                    <div class="question-text">
                                        ${question.question_text}
                                    </div>
                                    <div class="mt-2">
                                        <span class="badge bg-${question.difficulty === 'easy' ? 'success' : question.difficulty === 'medium' ? 'warning' : 'danger'}">
                                            ${question.difficulty.charAt(0).toUpperCase() + question.difficulty.slice(1)}
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
                    
                    // Update pagination if available
                    if (response.pagination) {
                        updatePagination(response.pagination);
                    }
                    
                    // Enable select all checkbox
                    $('#select-all-questions').prop('disabled', false);
                    
                    // Update counts
                    updateSelectedCount();
                    updateAddButtonState();
                    
                    // Attach event handler to preview buttons
                    $('.preview-question').click(function() {
                        const questionId = $(this).data('id');
                        previewQuestion(questionId);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', xhr.responseText);
                $('#questions-list').html(`
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Error loading questions: ${error}. Please try again.
                            </div>
                        </td>
                    </tr>
                `);
            }
        });
    }

    // Preview a question
    function previewQuestion(questionId) {
        // Implement question preview functionality
        alert('Question preview functionality will be implemented here for question ID: ' + questionId);
    }
    
    // Add selected questions to exam
    $('#add-questions-btn').click(function() {
        const selectedQuestions = [];
        $('.question-checkbox:checked').each(function() {
            selectedQuestions.push($(this).val());
        });
        
        if (selectedQuestions.length === 0) {
            alert('Please select at least one question to add.');
            return;
        }
        
        // Show loading state
        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');
        
        $.ajax({
            url: addQuestionsUrl,
            method: 'POST',
            data: {
                question_ids: selectedQuestions,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    alert('Questions added successfully!');
                    
                    // Refresh the current questions tab
                    // This would typically reload the current questions list
                    $('#current-tab').tab('show');
                    
                    // Reset checkboxes
                    $('.question-checkbox').prop('checked', false);
                    $('#select-all-questions').prop('checked', false);
                    updateSelectedCount();
                    updateAddButtonState();
                } else {
                    alert('Error adding questions: ' + response.message);
                }
                
                // Reset button state
                $('#add-questions-btn').prop('disabled', false).html('Add Selected Questions');
            },
            error: function(xhr, status, error) {
                console.error('Error adding questions:', xhr.responseText);
                alert('Error adding questions. Please try again.');
                
                // Reset button state
                $('#add-questions-btn').prop('disabled', false).html('Add Selected Questions');
            }
        });
    });
});
