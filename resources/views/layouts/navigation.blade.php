<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-black" />
                    </a>
                </div>

                <!-- Menu Button -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <button @click="open = !open" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:shadow-lg focus:outline-none transition ease-in-out duration-150">
                        <svg class="fill-current h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        Menu
                    </button>
                </div>
            </div>

            <!-- Gear/Settings Button -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <a href="{{ route('settings.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:shadow-lg focus:outline-none transition ease-in-out duration-150">
                    <i class="fas fa-cog h-4 w-4"></i>
                </a>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mega Menu Panel -->
    <div x-show="open" class="hidden sm:block bg-white border-t border-gray-200 w-full" @click.stop>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                    <!-- Incident & Risk Management -->
                    <div>
                        <h3 class="text-sm font-semibold text-black uppercase tracking-wider mb-4">Incident & Risk</h3>
                        <div class="space-y-3">
                            <a href="{{ route('incidents.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-exclamation-triangle text-lg text-red-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Incidents & Accidents</div>
                                    <div class="text-xs text-gray-600">Reporting & Investigation</div>
                                </div>
                            </a>

                            <a href="{{ route('risk-assessments.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-search text-lg text-gray-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Risk Assessment</div>
                                    <div class="text-xs text-gray-600">Hazard Identification</div>
                                </div>
                            </a>

                            <a href="{{ route('permits.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-tools text-lg text-gray-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Permit to Work</div>
                                    <div class="text-xs text-gray-600">High-Risk Activities</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Safety Operations -->
                    <div>
                        <h3 class="text-sm font-semibold text-black uppercase tracking-wider mb-4">Safety Operations</h3>
                        <div class="space-y-3">
                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-comments text-lg text-blue-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Toolbox Talks</div>
                                    <div class="text-xs text-gray-600">Safety Briefings</div>
                                </div>
                            </a>

                            <a href="{{ route('ppe-inventory.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-hard-hat text-lg text-gray-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">PPE Management</div>
                                    <div class="text-xs text-gray-600">Equipment Tracking</div>
                                </div>
                            </a>

                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-clipboard-check text-lg text-gray-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Inspections & Audits</div>
                                    <div class="text-xs text-gray-600">Compliance Checks</div>
                                </div>
                            </a>

                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-fire-extinguisher text-lg text-red-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Emergency Response</div>
                                    <div class="text-xs text-gray-600">Preparedness & Drills</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Training & Health -->
                    <div>
                        <h3 class="text-sm font-semibold text-black uppercase tracking-wider mb-4">Training & Health</h3>
                        <div class="space-y-3">
                            <a href="{{ route('trainings.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-graduation-cap text-lg text-green-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Training Management</div>
                                    <div class="text-xs text-gray-600">Certification & Competency</div>
                                </div>
                            </a>

                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-heartbeat text-lg text-red-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Health & Wellness</div>
                                    <div class="text-xs text-gray-600">Medical Records</div>
                                </div>
                            </a>

                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-leaf text-lg text-green-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Environmental</div>
                                    <div class="text-xs text-gray-600">Waste & Sustainability</div>
                                </div>
                            </a>

                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-home text-lg text-gray-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Housekeeping</div>
                                    <div class="text-xs text-gray-600">5S & Organization</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Management & Analytics -->
                    <div>
                        <h3 class="text-sm font-semibold text-black uppercase tracking-wider mb-4">Management</h3>
                        <div class="space-y-3">
                            <a href="{{ route('dashboard') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-tachometer-alt text-lg text-blue-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Dashboard</div>
                                    <div class="text-xs text-gray-600">Overview & Analytics</div>
                                </div>
                            </a>

                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-chart-bar text-lg text-purple-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Analytics & Reports</div>
                                    <div class="text-xs text-gray-600">KPI & Dashboards</div>
                                </div>
                            </a>

                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-file-alt text-lg text-gray-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Documents</div>
                                    <div class="text-xs text-gray-600">Policies & Records</div>
                                </div>
                            </a>

                            <a href="#" class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition ease-in-out duration-150">
                                <i class="fas fa-bell text-lg text-yellow-600 mr-3"></i>
                                <div>
                                    <div class="text-sm font-medium text-black">Notifications</div>
                                    <div class="text-xs text-gray-600">Alerts & Reminders</div>
                                </div>
                            </a>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-6 space-y-3">
                            <button onclick="window.location.href='{{ route('companies.create') }}'" class="w-full flex items-center justify-center px-4 py-2 bg-black border border-transparent rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:shadow-lg transition ease-in-out duration-150">
                                <i class="fas fa-plus mr-2"></i>
                                New Company
                            </button>

                            <button onclick="window.location.href='{{ route('users.create') }}'" class="w-full flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md text-xs font-semibold text-gray-700 uppercase tracking-widest hover:shadow-lg transition ease-in-out duration-150">
                                <i class="fas fa-user-plus mr-2"></i>
                                Add User
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('companies.index')" :active="request()->routeIs('companies.*')">
                {{ __('Companies') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                {{ __('Users') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('departments.index')" :active="request()->routeIs('departments.*')">
                {{ __('Departments') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">
                {{ __('Employees') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                {{ __('Profile') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-black">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-600">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
