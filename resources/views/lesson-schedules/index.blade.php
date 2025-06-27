@extends('layouts.app')

@section('title', 'Lesson Schedules Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-calendar-alt text-blue-500"></i>
            Lesson Schedules Management
        </h1>
        <a href="{{ route('lesson-schedules.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded shadow hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i> Add New Lesson Schedule
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 px-4 py-3 rounded bg-green-100 text-green-800 flex items-center justify-between">
            <span><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-green-600 hover:text-green-900 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-blue-700 flex items-center gap-2">
                <i class="fas fa-filter"></i> Filter Lesson Schedules
            </h2>
        </div>
        <div class="px-6 py-4">
            <form action="{{ route('lesson-schedules.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap gap-4 items-end">
                {{-- Centre and Teacher filters commented out --}}
                <div>
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">Day</label>
                    <select class="block w-40 px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500" id="day_of_week" name="day_of_week">
                        <option value="">All Days</option>
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                            <option value="{{ $day }}" {{ request('day_of_week') == $day ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select class="block w-40 px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex gap-2 mt-2 sm:mt-0">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <a href="{{ route('lesson-schedules.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                        <i class="fas fa-sync mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-blue-700 flex items-center gap-2">
                <i class="fas fa-table"></i> All Lesson Schedules
            </h2>
            <h6 class="m-0 font-weight-bold text-primary">All Lesson Schedules</h6>
        </div>
        <div class="px-6 py-4 overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-blue-50 text-blue-900">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider border-b">ID</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider border-b">Centre</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider border-b">Day</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider border-b">Section</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider border-b">Teacher</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider border-b">Start Date</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider border-b">End Date</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider border-b">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider border-b">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($lessonSchedules as $schedule)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-4 py-2 whitespace-nowrap">{{ $schedule->id }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $schedule->centre->name ?? '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $schedule->day_of_week }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $schedule->lessonSection->name ?? '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $schedule->teacher->user->name ?? '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $schedule->start_date }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $schedule->end_date ?? '-' }}</td>
                            <td class="px-4 py-2">
                                @if($schedule->is_active)
                                    <span class="inline-block px-2 py-1 text-xs font-bold rounded bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="inline-block px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-700">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap flex gap-1">
                                <a href="{{ route('lesson-schedules.show', $schedule->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded bg-blue-100 text-blue-700 hover:bg-blue-200 transition" title="View"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('lesson-schedules.edit', $schedule->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded bg-yellow-100 text-yellow-700 hover:bg-yellow-200 transition" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('lesson-schedules.assign-students', $schedule->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded bg-orange-100 text-orange-700 hover:bg-orange-200 transition" title="Assign Students"><i class="fas fa-user-plus"></i></a>
                                <a href="{{ route('lesson-attendances.take', $schedule->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded bg-green-100 text-green-700 hover:bg-green-200 transition" title="Take Attendance"><i class="fas fa-clipboard-check"></i></a>
                                <form action="{{ route('lesson-schedules.destroy', $schedule->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded bg-red-100 text-red-700 hover:bg-red-200 transition" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-gray-500">No lesson schedules found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{-- Pagination links removed because $lessonSchedules is not paginated --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "searching": false
        });
    });
</script>
@endsection
