<aside class="hidden md:flex md:flex-col md:w-64 bg-white border-r border-gray-200 overflow-y-auto">
    <div class="flex flex-col flex-grow pt-5 pb-4">
        <div class="flex-grow flex flex-col">
            <nav class="flex-1 px-2 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                
                @role('student')
                <!-- Student-specific Navigation -->
                <a href="{{ route('student.program.show') }}" class="{{ request()->routeIs('student.program.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('student.program.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    My Program
                </a>
                @endrole

                @role('admin')
                <!-- User Management (Admin Only) -->
                <div x-data="{ open: {{ request()->routeIs('users.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Users
                        <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{'rotate-90': open, 'rotate-0': !open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-1 space-y-1 pl-7">
                        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            All Users
                        </a>
                        <a href="{{ route('users.create') }}" class="{{ request()->routeIs('users.create') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            Add New User
                        </a>
                    </div>
                </div>
                @endrole

                <!-- Class Management section removed as it's not needed in this project -->

                <!-- Subject Management -->
                <div x-data="{ open: {{ request()->routeIs('subjects.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Subjects
                        <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{'rotate-90': open, 'rotate-0': !open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-1 space-y-1 pl-7">
                        <a href="{{ route('subjects.index') }}" class="{{ request()->routeIs('subjects.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            All Subjects
                        </a>
                        <a href="{{ route('subjects.create') }}" class="{{ request()->routeIs('subjects.create') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            Add New Subject
                        </a>
                    </div>
                </div>

                <!-- Teacher Management -->
                <div x-data="{ open: {{ request()->routeIs('teachers.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Teachers
                        <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{'rotate-90': open, 'rotate-0': !open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-1 space-y-1 pl-7">
                        <a href="{{ route('teachers.index') }}" class="{{ request()->routeIs('teachers.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            All Teachers
                        </a>
                        <a href="{{ route('teachers.create') }}" class="{{ request()->routeIs('teachers.create') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            Add New Teacher
                        </a>
                    </div>
                </div>
                
                <!-- Student Management -->
                <div x-data="{ open: {{ request()->routeIs('students.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Students
                        <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{'rotate-90': open, 'rotate-0': !open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-1 space-y-1 pl-7">
                        <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            All Students
                        </a>
                        <a href="{{ route('students.create') }}" class="{{ request()->routeIs('students.create') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            Add New Student
                        </a>
                    </div>
                </div>

                <!-- Centre Management -->
                <div x-data="{ open: {{ request()->routeIs('centres.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Centres
                        <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{'rotate-90': open, 'rotate-0': !open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-1 space-y-1 pl-7">
                        <a href="{{ route('centres.index') }}" class="{{ request()->routeIs('centres.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            All Centres
                        </a>
                        <a href="{{ route('centres.create') }}" class="{{ request()->routeIs('centres.create') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            Add New Centre
                        </a>
                    </div>
                </div>

                <!-- Lesson Management -->
                <div x-data="{ open: {{ request()->routeIs('lesson-sections.*') || request()->routeIs('lesson-schedules.*') || request()->routeIs('lesson-attendances.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Sections
                        <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{'rotate-90': open, 'rotate-0': !open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-1 space-y-1 pl-7">
                        <a href="{{ route('lesson-sections.index') }}" class="{{ request()->routeIs('lesson-sections.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            Lesson Sections
                        </a>
                        <a href="{{ route('lesson-attendances.index') }}" class="{{ request()->routeIs('lesson-attendances.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            Lesson Attendance
                        </a>
                    </div>
                </div>

                <!-- Schedule Management -->
                <div x-data="{ open: {{ request()->routeIs('lesson-schedules.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Schedule Management
                        <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{'rotate-90': open, 'rotate-0': !open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="open" class="mt-1 space-y-1 pl-7">
                        <a href="{{ route('lesson-schedules.index') }}" class="{{ request()->routeIs('lesson-schedules.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            All Schedules
                        </a>
                        <a href="{{ route('lesson-schedules.create') }}" class="{{ request()->routeIs('lesson-schedules.create') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            Create Schedule
                        </a>
                    </div>
                </div>

                <!-- Lesson Reschedule Request - Available to all users -->
                <a href="{{ route('lesson-reschedule.create') }}" class="{{ request()->routeIs('lesson-reschedule.*') && !request()->routeIs('lesson-reschedule.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('lesson-reschedule.*') && !request()->routeIs('lesson-reschedule.index') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Request Reschedule
                </a>

                <!-- Reports -->
                <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('reports.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Reports
                </a>

                <!-- Manage Reschedule Requests - Admin/Teacher Only -->
                @role(['admin', 'teacher'])
                <a href="{{ route('lesson-reschedule.index') }}" class="{{ request()->routeIs('lesson-reschedule.index') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('lesson-reschedule.index') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Manage Reschedule Requests
                </a>
                @endrole

                <!-- Settings -->
                @role('admin')
                <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-5 w-5 {{ request()->routeIs('settings.*') ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </a>
                @endrole
            </nav>
        </div>
    </div>
</aside>
