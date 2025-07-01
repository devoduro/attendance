@extends('layouts.app')

@section('title', 'Manage Lesson Reschedule Requests')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Lesson Reschedule Requests</h1>
        </div>

        <div class="p-6">
            @if (session('success'))
                <div class="mb-6 bg-success-50 border-l-4 border-success-500 p-4 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-success-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-success-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Lesson</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested Section</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested By</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($requests as $request)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $request->student->user->name }}</div>
                                    <div class="text-sm text-gray-500">ID: {{ $request->student->student_id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $request->currentLessonSchedule->subject->name }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $request->currentLessonSchedule->day_of_week }} - 
                                        {{ $request->currentLessonSchedule->start_time }} to {{ $request->currentLessonSchedule->end_time }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $request->requestedLessonSection->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $request->requestedLessonSection->description }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">{{ $request->reason }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($request->status === 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif ($request->status === 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if ($request->status === 'pending')
                                        <button type="button" class="text-primary-600 hover:text-primary-900 mr-3" 
                                            onclick="openModal('approve-modal-{{ $request->id }}')">
                                            Approve
                                        </button>
                                        <button type="button" class="text-danger-600 hover:text-danger-900"
                                            onclick="openModal('reject-modal-{{ $request->id }}')">
                                            Reject
                                        </button>
                                        
                                        <!-- Approve Modal -->
                                        <div id="approve-modal-{{ $request->id }}" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                    <form action="{{ route('lesson-reschedule.update-status', $request->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="approved">
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <div class="sm:flex sm:items-start">
                                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-success-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                    <svg class="h-6 w-6 text-success-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                </div>
                                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                                        Approve Reschedule Request
                                                                    </h3>
                                                                    <div class="mt-2">
                                                                        <p class="text-sm text-gray-500">
                                                                            Are you sure you want to approve this reschedule request? This action will allow the student to attend the requested section.
                                                                        </p>
                                                                        <div class="mt-4">
                                                                            <label for="admin_notes_approve_{{ $request->id }}" class="block text-sm font-medium text-gray-700">Admin Notes (Optional)</label>
                                                                            <textarea id="admin_notes_approve_{{ $request->id }}" name="admin_notes" rows="3" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Add any notes or comments about this approval"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-success-600 text-base font-medium text-white hover:bg-success-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-success-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                                Approve
                                                            </button>
                                                            <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal('approve-modal-{{ $request->id }}')">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Reject Modal -->
                                        <div id="reject-modal-{{ $request->id }}" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                    <form action="{{ route('lesson-reschedule.update-status', $request->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <div class="sm:flex sm:items-start">
                                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-danger-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                    <svg class="h-6 w-6 text-danger-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </div>
                                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                                        Reject Reschedule Request
                                                                    </h3>
                                                                    <div class="mt-2">
                                                                        <p class="text-sm text-gray-500">
                                                                            Are you sure you want to reject this reschedule request? Please provide a reason for rejection.
                                                                        </p>
                                                                        <div class="mt-4">
                                                                            <label for="admin_notes_reject_{{ $request->id }}" class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
                                                                            <textarea id="admin_notes_reject_{{ $request->id }}" name="admin_notes" rows="3" class="shadow-sm focus:ring-primary-500 focus:border-primary-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Explain why this request is being rejected" required></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-danger-600 text-base font-medium text-white hover:bg-danger-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-danger-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                                Reject
                                                            </button>
                                                            <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal('reject-modal-{{ $request->id }}')">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-500">{{ ucfirst($request->status) }}</span>
                                        @if ($request->admin_notes)
                                            <button type="button" class="ml-2 text-primary-600 hover:text-primary-900"
                                                onclick="openModal('notes-modal-{{ $request->id }}')">
                                                View Notes
                                            </button>
                                            
                                            <!-- Notes Modal -->
                                            <div id="notes-modal-{{ $request->id }}" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                            <div class="sm:flex sm:items-start">
                                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                                        Admin Notes
                                                                    </h3>
                                                                    <div class="mt-2">
                                                                        <p class="text-sm text-gray-500">
                                                                            {{ $request->admin_notes }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                            <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal('notes-modal-{{ $request->id }}')">
                                                                Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No reschedule requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endsection
