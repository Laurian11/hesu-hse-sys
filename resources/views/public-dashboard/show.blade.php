<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $company->name }} - HSE Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Company Info -->
                <div class="flex items-center space-x-4">
                    @if($company->logo_url)
                        <img src="{{ $company->logo_url }}" alt="{{ $company->name }} Logo" class="w-8 h-8 object-contain">
                    @else
                        <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-gray-500 text-sm font-semibold">
                                {{ substr($company->name, 0, 2) }}
                            </span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900">
                            {{ $company->name }}
                        </h1>
                        <p class="text-sm text-gray-600">HSE Dashboard</p>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex items-center space-x-4">
                    <a
                        href="{{ route('landing') }}"
                        class="text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors"
                    >
                        ← Back to Companies
                    </a>
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center px-4 py-2 bg-black hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors shadow-sm hover:shadow-md"
                    >
                        Staff Login
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Safe Man-Hours -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Safe Man-Hours</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($metrics['total_safe_man_hours']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Monthly Incident Rate -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Monthly Incident Rate</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $metrics['monthly_incident_rate'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Active Safety Observations -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Safety Observations</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $metrics['active_safety_observations'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Days Since Last LTI -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Days Since Last LTI</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $metrics['days_since_lti'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section (Placeholder) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Incident Trend Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Incident Trend (Last 12 Months)</h3>
                <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                    <p class="text-gray-500">Chart will be implemented with Chart.js</p>
                </div>
            </div>

            <!-- Safety Observations by Department -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Safety Observations by Department</h3>
                <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                    <p class="text-gray-500">Chart will be implemented with Chart.js</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
