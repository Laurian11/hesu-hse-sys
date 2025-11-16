<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $user->name }} - {{ session('current_company')->name ?? 'HSE System' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <h1 class="text-xl font-semibold text-gray-900">
                                HSE Dashboard
                            </h1>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('dashboard') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Dashboard</a>

                            @can('user.view')
                                <a href="{{ route('users.index') }}" class="border-indigo-400 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Users</a>
                            @endcan

                            @can('department.view')
                                <a href="{{ route('departments.index') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Departments</a>
                            @endcan

                            @can('employee.view')
                                <a href="{{ route('employees.index') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Employees</a>
                            @endcan

                            @can('company.view')
                                <a href="{{ route('companies.index') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Companies</a>
                            @endcan
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Company Selector -->
                        @if(auth()->user()->companies->count() > 1)
                            <div class="ml-3 relative">
                                <form method="POST" action="{{ route('switch-company') }}" class="inline">
                                    @csrf
                                    <select name="company_id" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        @foreach(auth()->user()->companies as $company)
                                            <option value="{{ $company->id }}" {{ session('current_company_id') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        @else
                            <span class="text-sm text-gray-700">
                                {{ session('current_company')->name ?? 'No Company' }}
                            </span>
                        @endif

                        <!-- Profile dropdown -->
                        <div class="ml-3 relative">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ auth()->user()->name }}">
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link href="{{ route('profile.edit') }}">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                        @csrf
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                        <p class="mt-1 text-sm text-gray-600">User details and company information</p>
                    </div>
                    <div class="flex space-x-3">
                        @can('user.edit')
                            <a href="{{ route('users.edit', $user) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                Edit User
                            </a>
                        @endcan
                        <a href="{{ route('users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Back to Users
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- User Information -->
                    <div class="lg:col-span-2">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Profile Picture -->
                                    <div class="md:col-span-2 flex justify-center">
                                        <img class="h-24 w-24 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF&size=96" alt="{{ $user->name }}">
                                    </div>

                                    <!-- Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Name</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                                    </div>

                                    <!-- Phone -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                                    </div>

                                    <!-- Status -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>

                                    <!-- Joined Date -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Joined Date</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                                    </div>

                                    <!-- Last Updated -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Company & Role Information -->
                    <div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Company & Role</h3>

                                <div class="space-y-4">
                                    @foreach($user->companies as $company)
                                        <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                            <div class="flex items-center mb-2">
                                                <img class="h-8 w-8 rounded-full mr-3" src="{{ $company->logo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($company->name) . '&color=7F9CF5&background=EBF4FF' }}" alt="{{ $company->name }}">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $company->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $company->description }}</p>
                                                </div>
                                            </div>

                                            <div class="ml-11">
                                                <label class="block text-xs font-medium text-gray-700">Role</label>
                                                <p class="text-sm text-gray-900">{{ $company->pivot->role->name ?? 'No Role' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Employee Information (if exists) -->
                        @if($user->employee)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                                <div class="p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Employee Details</h3>

                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Employee ID</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $user->employee->employee_id }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Department</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $user->employee->department->name ?? 'Not assigned' }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Designation</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $user->employee->designation }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Date of Joining</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $user->employee->date_of_joining->format('M d, Y') }}</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Work Location</label>
                                            <p class="mt-1 text-sm text-gray-900">{{ $user->employee->work_location ?? 'Not specified' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="mt-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>

                            @if($user->activityLogs->count() > 0)
                                <div class="space-y-4">
                                    @foreach($user->activityLogs->take(10) as $log)
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm text-gray-900">{{ $log->action }}</p>
                                                <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">No recent activity found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
