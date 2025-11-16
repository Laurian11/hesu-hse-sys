<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hesu Investments HSE Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Title -->
                <h1 class="text-xl font-semibold text-gray-900">
                    Hesu Investments HSE Portal
                </h1>

                <!-- View Toggle -->
                <div class="flex items-center space-x-4">
                    <div class="flex bg-gray-100 rounded-lg p-1">
                        <button
                            onclick="setViewMode('grid')"
                            class="px-3 py-1 text-sm font-medium rounded-md transition-colors {{ $viewMode === 'grid' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                        >
                            Grid View
                        </button>
                        <button
                            onclick="setViewMode('list')"
                            class="px-3 py-1 text-sm font-medium rounded-md transition-colors {{ $viewMode === 'list' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                        >
                            List View
                        </button>
                    </div>

                    <!-- Login Button -->
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
        @if($companies->count() > 0)
            @if($viewMode === 'grid')
                <!-- Grid View -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($companies as $company)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer"
                             onclick="window.location.href='{{ route('public-dashboard', $company->slug) }}'">
                            <div class="p-6">
                                <!-- Company Logo -->
                                <div class="flex justify-center mb-4">
                                    @if($company->logo_url)
                                        <img src="{{ $company->logo_url }}" alt="{{ $company->name }} Logo" class="w-16 h-16 object-contain">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <span class="text-gray-500 text-lg font-semibold">
                                                {{ substr($company->name, 0, 2) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Company Info -->
                                <div class="text-center">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        {{ $company->name }}
                                    </h3>
                                    @if($company->description)
                                        <p class="text-sm text-gray-600">
                                            {{ $company->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- List View -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    @foreach($companies as $company)
                        <div class="border-b border-gray-200 last:border-b-0 hover:bg-gray-50 transition-colors cursor-pointer"
                             onclick="window.location.href='{{ route('public-dashboard', $company->slug) }}'">
                            <div class="px-6 py-4 flex items-center space-x-4">
                                <!-- Company Logo -->
                                @if($company->logo_url)
                                    <img src="{{ $company->logo_url }}" alt="{{ $company->name }} Logo" class="w-12 h-12 object-contain">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <span class="text-gray-500 text-sm font-semibold">
                                            {{ substr($company->name, 0, 2) }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Company Info -->
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $company->name }}
                                    </h3>
                                    @if($company->description)
                                        <p class="text-sm text-gray-600">
                                            {{ $company->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <!-- No Companies Message -->
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">🏢</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Companies Available</h3>
                <p class="text-gray-600">Companies will appear here once they are added to the system.</p>
            </div>
        @endif
    </main>

    <script>
        function setViewMode(mode) {
            const url = new URL(window.location);
            url.searchParams.set('view', mode);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>
