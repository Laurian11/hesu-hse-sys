<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('UI Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden">
                <div class="p-6">
                    <form method="POST" action="{{ route('ui-management.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Theme Settings -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Theme Settings</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Theme</label>
                                        <select name="theme" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            <option value="light" {{ session('ui_preferences.theme') == 'light' ? 'selected' : '' }}>Light</option>
                                            <option value="dark" {{ session('ui_preferences.theme') == 'dark' ? 'selected' : '' }}>Dark</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" name="high_contrast" value="1" {{ session('ui_preferences.high_contrast') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label class="ml-2 block text-sm text-gray-900">High Contrast Mode</label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" name="reduced_motion" value="1" {{ session('ui_preferences.reduced_motion') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label class="ml-2 block text-sm text-gray-900">Reduced Motion</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Layout & Display -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Layout & Display</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Font Size</label>
                                        <select name="font_size" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            <option value="small" {{ session('ui_preferences.font_size') == 'small' ? 'selected' : '' }}>Small</option>
                                            <option value="medium" {{ session('ui_preferences.font_size') == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="large" {{ session('ui_preferences.font_size') == 'large' ? 'selected' : '' }}>Large</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Layout Style</label>
                                        <select name="layout" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            <option value="compact" {{ session('ui_preferences.layout') == 'compact' ? 'selected' : '' }}>Compact</option>
                                            <option value="expanded" {{ session('ui_preferences.layout') == 'expanded' ? 'selected' : '' }}>Expanded</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Language & Notifications -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Language & Notifications</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                                        <select name="language" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            <option value="en" {{ session('ui_preferences.language') == 'en' ? 'selected' : '' }}>English</option>
                                            <option value="es" {{ session('ui_preferences.language') == 'es' ? 'selected' : '' }}>Spanish</option>
                                            <option value="fr" {{ session('ui_preferences.language') == 'fr' ? 'selected' : '' }}>French</option>
                                            <option value="de" {{ session('ui_preferences.language') == 'de' ? 'selected' : '' }}>German</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" name="notifications" value="1" {{ session('ui_preferences.notifications') ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        <label class="ml-2 block text-sm text-gray-900">Enable Notifications</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Color Customization -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Color Customization</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Font Color</label>
                                        <input type="color" name="font_color" value="{{ session('ui_preferences.font_color', '#000000') }}" class="w-full h-10 border-gray-300 rounded-md">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Icon Color</label>
                                        <input type="color" name="icon_color" value="{{ session('ui_preferences.icon_color', '#6b6b6b') }}" class="w-full h-10 border-gray-300 rounded-md">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Shadow Color</label>
                                        <input type="color" name="shadow_color" value="{{ session('ui_preferences.shadow_color', '#000000') }}" class="w-full h-10 border-gray-300 rounded-md">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                                        <input type="color" name="primary_color" value="{{ session('ui_preferences.primary_color', '#000000') }}" class="w-full h-10 border-gray-300 rounded-md">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                                        <input type="color" name="secondary_color" value="{{ session('ui_preferences.secondary_color', '#6b6b6b') }}" class="w-full h-10 border-gray-300 rounded-md">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Accent Color</label>
                                        <input type="color" name="accent_color" value="{{ session('ui_preferences.accent_color', '#e0e0e0') }}" class="w-full h-10 border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>

                            <!-- Preview & Actions -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Preview & Actions</h3>
                                <div class="space-y-4">
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Current Settings Preview</h4>
                                        <p class="text-xs text-gray-600">Theme: {{ session('ui_preferences.theme', 'light') }}</p>
                                        <p class="text-xs text-gray-600">Font Size: {{ session('ui_preferences.font_size', 'medium') }}</p>
                                        <p class="text-xs text-gray-600">Layout: {{ session('ui_preferences.layout', 'expanded') }}</p>
                                        <p class="text-xs text-gray-600">Font Color: {{ session('ui_preferences.font_color', '#000000') }}</p>
                                        <p class="text-xs text-gray-600">Primary Color: {{ session('ui_preferences.primary_color', '#000000') }}</p>
                                    </div>

                                    <div class="flex space-x-4">
                                        <x-primary-button type="submit">
                                            <i class="fas fa-save mr-2"></i>Save Preferences
                                        </x-primary-button>
                                        <x-secondary-button type="button" onclick="resetSettings()">
                                            <i class="fas fa-undo mr-2"></i>Reset to Default
                                        </x-secondary-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function resetSettings() {
            if (confirm('Are you sure you want to reset all UI preferences to default?')) {
                // Reset form and submit
                document.querySelector('form').reset();
                document.querySelector('form').submit();
            }
        }
    </script>
</x-app-layout>
