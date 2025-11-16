<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Permit: ') . $permit->permit_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('permits.update', $permit) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Permit Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Permit Type</label>
                                <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="work" {{ $permit->type === 'work' ? 'selected' : '' }}>General Work</option>
                                    <option value="hot_work" {{ $permit->type === 'hot_work' ? 'selected' : '' }}>Hot Work</option>
                                    <option value="confined_space" {{ $permit->type === 'confined_space' ? 'selected' : '' }}>Confined Space</option>
                                    <option value="electrical" {{ $permit->type === 'electrical' ? 'selected' : '' }}>Electrical Work</option>
                                    <option value="height_work" {{ $permit->type === 'height_work' ? 'selected' : '' }}>Height Work</option>
                                    <option value="excavation" {{ $permit->type === 'excavation' ? 'selected' : '' }}>Excavation</option>
                                    <option value="other" {{ $permit->type === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $permit->title) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div class="md:col-span-2">
                                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                <input type="text" id="location" name="location" value="{{ old('location', $permit->location) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Planned Start -->
                            <div>
                                <label for="planned_start" class="block text-sm font-medium text-gray-700">Planned Start Date & Time</label>
                                <input type="datetime-local" id="planned_start" name="planned_start" value="{{ old('planned_start', $permit->planned_start->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                @error('planned_start')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Planned End -->
                            <div>
                                <label for="planned_end" class="block text-sm font-medium text-gray-700">Planned End Date & Time</label>
                                <input type="datetime-local" id="planned_end" name="planned_end" value="{{ old('planned_end', $permit->planned_end->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                @error('planned_end')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <option value="draft" {{ $permit->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="pending_approval" {{ $permit->status === 'pending_approval' ? 'selected' : '' }}>Pending Approval</option>
                                    <option value="approved" {{ $permit->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="active" {{ $permit->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="suspended" {{ $permit->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    <option value="closed" {{ $permit->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="cancelled" {{ $permit->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Actual Start (if applicable) -->
                            <div>
                                <label for="actual_start" class="block text-sm font-medium text-gray-700">Actual Start Date & Time</label>
                                <input type="datetime-local" id="actual_start" name="actual_start" value="{{ old('actual_start', $permit->actual_start ? $permit->actual_start->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('actual_start')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Actual End (if applicable) -->
                            <div>
                                <label for="actual_end" class="block text-sm font-medium text-gray-700">Actual End Date & Time</label>
                                <input type="datetime-local" id="actual_end" name="actual_end" value="{{ old('actual_end', $permit->actual_end ? $permit->actual_end->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('actual_end')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>{{ old('description', $permit->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Work Description -->
                            <div class="md:col-span-2">
                                <label for="work_description" class="block text-sm font-medium text-gray-700">Work Description</label>
                                <textarea id="work_description" name="work_description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>{{ old('work_description', $permit->work_description) }}</textarea>
                                @error('work_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Hazards Identified -->
                            <div class="md:col-span-2">
                                <label for="hazards_identified" class="block text-sm font-medium text-gray-700">Hazards Identified</label>
                                <textarea id="hazards_identified" name="hazards_identified" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('hazards_identified', $permit->hazards_identified) }}</textarea>
                                @error('hazards_identified')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Control Measures -->
                            <div class="md:col-span-2">
                                <label for="control_measures" class="block text-sm font-medium text-gray-700">Control Measures</label>
                                <textarea id="control_measures" name="control_measures" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('control_measures', $permit->control_measures) }}</textarea>
                                @error('control_measures')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- PPE Required -->
                            <div>
                                <label for="ppe_required" class="block text-sm font-medium text-gray-700">PPE Required</label>
                                <textarea id="ppe_required" name="ppe_required" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('ppe_required', $permit->ppe_required) }}</textarea>
                                @error('ppe_required')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Emergency Procedures -->
                            <div>
                                <label for="emergency_procedures" class="block text-sm font-medium text-gray-700">Emergency Procedures</label>
                                <textarea id="emergency_procedures" name="emergency_procedures" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('emergency_procedures', $permit->emergency_procedures) }}</textarea>
                                @error('emergency_procedures')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Closure Notes -->
                            <div class="md:col-span-2">
                                <label for="closure_notes" class="block text-sm font-medium text-gray-700">Closure Notes</label>
                                <textarea id="closure_notes" name="closure_notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('closure_notes', $permit->closure_notes) }}</textarea>
                                @error('closure_notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Attachments -->
                            <div class="md:col-span-2">
                                <label for="attachments" class="block text-sm font-medium text-gray-700">Add New Attachments</label>
                                <input type="file" id="attachments" name="attachments[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <p class="mt-1 text-sm text-gray-500">Upload additional documents (PDF, DOC, DOCX, JPG, PNG). Max 5MB each.</p>
                                @error('attachments')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @error('attachments.*')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if($permit->attachments && count($permit->attachments) > 0)
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-700">Current Attachments:</p>
                                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach($permit->attachments as $attachment)
                                                <div class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                                    <a href="{{ Storage::url($attachment) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                                        {{ basename($attachment) }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <a href="{{ route('permits.show', $permit) }}" class="mr-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Permit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
