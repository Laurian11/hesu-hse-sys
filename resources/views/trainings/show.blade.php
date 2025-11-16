<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Training Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Training #{{ $training->training_number }}</h3>
                        <div>
                            <a href="{{ route('trainings.edit', $training) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <a href="{{ route('trainings.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
                            <p class="mt-1 text-sm text-gray-900">{{ $training->title }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Training Number</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $training->training_number }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst($training->type) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($training->status == 'scheduled') bg-blue-100 text-blue-800
                                @elseif($training->status == 'in_progress') bg-yellow-100 text-yellow-800
                                @elseif($training->status == 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $training->status)) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Scheduled Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $training->scheduled_date->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Time</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $training->start_time }} - {{ $training->end_time }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Trainer</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $training->trainer }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $training->location ?: 'Not specified' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created By</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $training->creator->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $training->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $training->description }}</p>
                        </div>

                        <!-- Attendees -->
                        @if($training->attendees)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Attendees</label>
                                <ul class="mt-1 text-sm text-gray-900 list-disc list-inside">
                                    @foreach($training->attendees as $attendeeId)
                                        @php $user = \App\Models\User::find($attendeeId); @endphp
                                        <li>{{ $user ? $user->name : 'Unknown User' }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Objectives -->
                        @if($training->objectives)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Learning Objectives</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $training->objectives }}</p>
                            </div>
                        @endif

                        <!-- Materials -->
                        @if($training->materials)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Training Materials</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $training->materials }}</p>
                            </div>
                        @endif

                        <!-- Completion Details -->
                        @if($training->status == 'completed')
                            <div class="md:col-span-2 mt-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-4">Completion Details</h4>
                            </div>

                            @if($training->completion_date)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Completion Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $training->completion_date->format('M d, Y') }}</p>
                                </div>
                            @endif

                            @if($training->notes)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $training->notes }}</p>
                                </div>
                            @endif
                        @endif

                        <!-- Attachments -->
                        @if($training->attachments)
                            <div class="md:col-span-2 mt-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-4">Attachments</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($training->attachments as $attachment)
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
