<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Report New Incident') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('incidents.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Incident Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Incident Type</label>
                                <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Type</option>
                                    <option value="injury">Injury</option>
                                    <option value="property_damage">Property Damage</option>
                                    <option value="near_miss">Near Miss</option>
                                    <option value="environmental">Environmental</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Severity -->
                            <div>
                                <label for="severity" class="block text-sm font-medium text-gray-700">Severity</label>
                                <select id="severity" name="severity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Severity</option>
                                    <option value="minor">Minor</option>
                                    <option value="moderate">Moderate</option>
                                    <option value="major">Major</option>
                                    <option value="critical">Critical</option>
                                </select>
                                @error('severity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Incident Date -->
                            <div>
                                <label for="incident_date" class="block text-sm font-medium text-gray-700">Incident Date</label>
                                <input type="date" id="incident_date" name="incident_date" value="{{ old('incident_date') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('incident_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                <input type="text" id="location" name="location" value="{{ old('location') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Affected Parties -->
                            <div class="md:col-span-2">
                                <label for="affected_parties" class="block text-sm font-medium text-gray-700">Affected Parties (one per line)</label>
                                <textarea id="affected_parties" name="affected_parties" rows="3" placeholder="John Doe&#10;Jane Smith" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('affected_parties') }}</textarea>
                                @error('affected_parties')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Witnesses -->
                            <div class="md:col-span-2">
                                <label for="witnesses" class="block text-sm font-medium text-gray-700">Witnesses (one per line)</label>
                                <textarea id="witnesses" name="witnesses" rows="3" placeholder="Witness 1&#10;Witness 2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('witnesses') }}</textarea>
                                @error('witnesses')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Immediate Actions -->
                            <div class="md:col-span-2">
                                <label for="immediate_actions" class="block text-sm font-medium text-gray-700">Immediate Actions Taken</label>
                                <textarea id="immediate_actions" name="immediate_actions" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('immediate_actions') }}</textarea>
                                @error('immediate_actions')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Attachments -->
                            <div class="md:col-span-2">
                                <label for="attachments" class="block text-sm font-medium text-gray-700">Attachments</label>
                                <input type="file" id="attachments" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <p class="mt-1 text-sm text-gray-500">Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (max 5MB each)</p>
                                @error('attachments')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('incidents.index') }}" class="mr-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Report Incident
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Convert textarea to arrays for affected parties and witnesses
        document.querySelector('form').addEventListener('submit', function(e) {
            const affectedPartiesTextarea = document.getElementById('affected_parties');
            const witnessesTextarea = document.getElementById('witnesses');

            if (affectedPartiesTextarea.value.trim()) {
                const affectedParties = affectedPartiesTextarea.value.split('\n').map(p => p.trim()).filter(p => p);
                affectedPartiesTextarea.value = JSON.stringify(affectedParties);
            }

            if (witnessesTextarea.value.trim()) {
                const witnesses = witnessesTextarea.value.split('\n').map(w => w.trim()).filter(w => w);
                witnessesTextarea.value = JSON.stringify(witnesses);
            }
        });
    </script>
</x-app-layout>
