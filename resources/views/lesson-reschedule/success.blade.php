@extends('layouts.app')

@section('title', 'Reschedule Request Submitted')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">Request Submitted Successfully</h1>
        </div>

        <div class="p-6">
            <div class="bg-success-50 border-l-4 border-success-500 p-4 rounded-md mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-success-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-success-800">Request Submitted</h3>
                        <div class="mt-2 text-sm text-success-700">
                            <p>Your lesson reschedule request has been submitted successfully. The administration will review your request and get back to you soon.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <p class="text-gray-600">What happens next?</p>
                <ol class="list-decimal pl-5 space-y-2 text-gray-600">
                    <li>Your request will be reviewed by administrators or teachers.</li>
                    <li>You will be notified when your request is approved or rejected.</li>
                    <li>If approved, your lesson will be rescheduled to the requested section.</li>
                </ol>

                <div class="mt-8 flex items-center justify-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Return to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
