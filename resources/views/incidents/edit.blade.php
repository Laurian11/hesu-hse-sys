<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Incident') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('incidents.update', $incident) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Incident Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Incident Type</label>
                                <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="injury" {{ $incident->type == 'injury' ? 'selected' : '' }}>Injury</option>
                                    <option value="property_damage" {{ $incident->type == 'property_damage' ? 'selected' : '' }}>Property Damage</option>
                                    <option value="near_miss" {{ $incident->type == 'near_miss' ? 'selected' : '' }}>Near Miss</option>
                                    <option value="environmental" {{ $incident->type == 'environmental' ? 'selected' : '' }}>Environmental</option>
                                    <option value="other" {{ $incident->type == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Severity -->
                            <div>
                                <label for="severity" class="block text-sm font-medium text-gray-700">Severity</label>
                                <select id="severity" name="severity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="minor" {{ $incident->severity == 'minor' ? 'selected' : '' }}>Minor</option>
                                    <option value="moderate" {{ $incident->severity == 'moderate' ? 'selected' : '' }}>Moderate</option>
                                    <option value="major" {{ $incident->severity == 'major' ? 'selected' : '' }}>Major</option>
                                    <option value="critical" {{ $incident->severity == 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                                @error('severity')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $incident->title) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $incident->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Incident Date -->
                            <div>
                                <label for="incident_date" class="block text-sm font-medium text-gray-700">Incident Date</label>
                                <input type="date" id="incident_date" name="incident_date" value="{{ old('incident_date', $incident->incident_date->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('incident_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                <input type="text" id="location" name="location" value="{{ old('location', $incident->location) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Affected Parties -->
                            <div class="md:col-span-2">
                                <label for="affected_parties" class="block text-sm font-medium text-gray-700">Affected Parties (one per line)</label>
                                <textarea id="affected_parties" name="affected_parties" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('affected_parties', is_array($incident->affected_parties) ? implode("\n", $incident->affected_parties) : $incident->affected_parties) }}</textarea>
                                @error('affected_parties')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Witnesses -->
                            <div class="md:col-span-2">
                                <label for="witnesses" class="block text-sm font-medium text-gray-700">Witnesses (one per line)</label>
                                <textarea id="witnesses" name="witnesses" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('witnesses', is_array($incident->witnesses) ? implode("\n", $incident->witnesses) : $incident->witnesses) }}</textarea>
                                @error('witnesses')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Immediate Actions -->
                            <div class="md:col-span-2">
                                <label for="immediate_actions" class="block text-sm font-medium text-gray-700">Immediate Actions Taken</label>
                                <textarea id="immediate_actions" name="immediate_actions" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('immediate_actions', $incident->immediate_actions) }}</textarea>
                                @error('immediate_actions')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="open" {{ $incident->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="investigating" {{ $incident->status == 'investigating' ? 'selected' : '' }}>Investigating</option>
                                    <option value="closed" {{ $incident->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Investigated By -->
                            <div>
                                <label for="investigated_by" class="block text-sm font-medium text-gray-700">Investigated By</label>
                                <select id="investigated_by" name="investigated_by" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Investigator</option>
                                    @foreach(\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}" {{ $incident->investigated_by == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('investigated_by')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Root Cause -->
                            <div class="md:col-span-2">
                                <label for="root_cause" class="block text-sm font-medium text-gray-700">Root Cause</label>
                                <textarea id="root_cause" name="root_cause" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('root_cause', $incident->root_cause) }}</textarea>
                                @error('root_cause')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Corrective Actions -->
                            <div class="md:col-span-2">
                                <label for="corrective_actions" class="block text-sm font-medium text-gray-700">Corrective Actions</label>
                                <textarea id="corrective_actions" name="corrective_actions" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('corrective_actions', $incident->corrective_actions) }}</textarea>
                                @error('corrective_actions')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Preventive Actions -->
                            <div class="md:col-span-2">
                                <label for="preventive_actions" class="block text-sm font-medium text-gray-700">Preventive Actions</label>
                                <textarea id="preventive_actions" name="preventive_actions" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('preventive_actions', $incident->preventive_actions) }}</textarea>
                                @error('preventive_actions')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Closure Date -->
                            <div>
                                <label for="closure_date" class="block text-sm font-medium text-gray-700">Closure Date</label>
                                <input type="date" id="closure_date" name="closure_date" value="{{ old('closure_date', $incident->closure_date ? $incident->closure_date->format('Y-m-d') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('closure_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Attachments -->
                            <div class="md:col-span-2">
                                <label for="attachments" class="block text-sm font-medium text-gray-700">Add New Attachments</label>
                                <input type="file" id="attachments" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <p class="mt-1 text-sm text-gray-500">Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG (max 5MB each)</p>
                                @error('attachments')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if($incident->attachments)
                                    <div class="mt-4">
                                        <h5 class="text-sm font-medium text-gray-700">Current Attachments:</h5>
                                        <ul class="mt-2 text-sm text-gray-600">
                                            @foreach($incident->attachments as $attachment)
                                                <li>{{ basename($attachment) }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('incidents.show', $incident) }}" class="mr-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Incident
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
