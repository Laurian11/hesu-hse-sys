<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">System Settings</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Companies -->
                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <i class="fas fa-building text-gray-500 mr-3"></i>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">Companies</h4>
                                    <p class="text-sm text-gray-500">Manage company information</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('companies.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Companies</a>
                            </div>
                        </div>

                        <!-- Users -->
                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <i class="fas fa-users text-gray-500 mr-3"></i>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">Users</h4>
                                    <p class="text-sm text-gray-500">Manage system users</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Users</a>
                            </div>
                        </div>

                        <!-- Departments -->
                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <i class="fas fa-sitemap text-gray-500 mr-3"></i>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">Departments</h4>
                                    <p class="text-sm text-gray-500">Manage departments</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('departments.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Departments</a>
                            </div>
                        </div>

                        <!-- Employees -->
                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <i class="fas fa-user-tie text-gray-500 mr-3"></i>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">Employees</h4>
                                    <p class="text-sm text-gray-500">Manage employee records</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('employees.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Employees</a>
                            </div>
                        </div>

                        <!-- UI Management -->
                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                            <div class="flex items-center">
                                <i class="fas fa-palette text-gray-500 mr-3"></i>
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900">UI Management</h4>
                                    <p class="text-sm text-gray-500">Manage user interface settings</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('ui-management.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Manage UI</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
