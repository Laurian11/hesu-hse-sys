<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Incident Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Incident #{{ $incident->incident_number }}</h3>
                        <div>
                            <a href="{{ route('incidents.edit', $incident) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <a href="{{ route('incidents.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="md:col-span-2">
                            <h4 class="text-md font-semibold text-gray-900 mb-4">Basic Information</h4>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $incident->title }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Incident Number</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $incident->incident_number }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $incident->type)) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Severity</label>
                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($incident->severity == 'minor') bg-green-100 text-green-800
                                @elseif($incident->severity == 'moderate') bg-yellow-100 text-yellow-800
                                @elseif($incident->severity == 'major') bg-orange-100 text-orange-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($incident->severity) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($incident->status == 'open') bg-blue-100 text-blue-800
                                @elseif($incident->status == 'investigating') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($incident->status) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Incident Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $incident->incident_date->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $incident->location }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Reported By</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $incident->reporter->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Reported Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $incident->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $incident->description }}</p>
                        </div>

                        <!-- Affected Parties -->
                        @if($incident->affected_parties)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Affected Parties</label>
                                <ul class="mt-1 text-sm text-gray-900 list-disc list-inside">
                                    @foreach($incident->affected_parties as $party)
                                        <li>{{ $party }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Witnesses -->
                        @if($incident->witnesses)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Witnesses</label>
                                <ul class="mt-1 text-sm text-gray-900 list-disc list-inside">
                                    @foreach($incident->witnesses as $witness)
                                        <li>{{ $witness }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Immediate Actions -->
                        @if($incident->immediate_actions)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Immediate Actions Taken</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $incident->immediate_actions }}</p>
                            </div>
                        @endif

                        <!-- Investigation Details -->
                        @if($incident->status != 'open')
                            <div class="md:col-span-2 mt-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-4">Investigation Details</h4>
                            </div>

                            @if($incident->investigator)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Investigated By</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $incident->investigator->name }}</p>
                                </div>
                            @endif

                            @if($incident->root_cause)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Root Cause</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $incident->root_cause }}</p>
                                </div>
                            @endif

                            @if($incident->corrective_actions)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Corrective Actions</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $incident->corrective_actions }}</p>
                                </div>
                            @endif

                            @if($incident->preventive_actions)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Preventive Actions</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $incident->preventive_actions }}</p>
                                </div>
                            @endif

                            @if($incident->closure_date)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Closure Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $incident->closure_date->format('M d, Y') }}</p>
                                </div>
                            @endif
                        @endif

                        <!-- Attachments -->
                        @if($incident->attachments)
                            <div class="md:col-span-2 mt-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-4">Attachments</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($incident->attachments as $attachment)
                                        <div class="border border-gray-300 rounded p-4">
                                            <a href="{{ Storage::url($attachment) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                {{ basename($attachment) }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
