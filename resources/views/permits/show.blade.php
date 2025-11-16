<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Permit Details: ') . $permit->permit_number }}
            </h2>
            <div class="flex space-x-2">
                @can('update', $permit)
                    <a href="{{ route('permits.edit', $permit) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endcan
                @can('approve', $permit)
                    @if($permit->canBeApproved())
                        <form method="POST" action="{{ route('permits.approve', $permit) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to approve this permit?')">
                                Approve
                            </button>
                        </form>
                    @endif
                @endcan
                @can('issue', $permit)
                    @if($permit->status === 'approved')
                        <form method="POST" action="{{ route('permits.issue', $permit) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to issue this permit?')">
                                Issue
                            </button>
                        </form>
                    @endif
                @endcan
                @can('close', $permit)
                    @if($permit->canBeClosed())
                        <button onclick="openCloseModal()" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                            Close Permit
                        </button>
                    @endif
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Permit Number</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permit->permit_number }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($permit->status === 'active') bg-green-100 text-green-800
                                @elseif($permit->status === 'approved') bg-blue-100 text-blue-800
                                @elseif($permit->status === 'pending_approval') bg-yellow-100 text-yellow-800
                                @elseif($permit->status === 'closed') bg-gray-100 text-gray-800
                                @elseif($permit->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucwords(str_replace('_', ' ', $permit->status)) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $permit->type)) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permit->location }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Planned Start</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permit->planned_start->format('M d, Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Planned End</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permit->planned_end->format('M d, Y H:i') }}</p>
                        </div>

                        @if($permit->actual_start)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Actual Start</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permit->actual_start->format('M d, Y H:i') }}</p>
                        </div>
                        @endif

                        @if($permit->actual_end)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Actual End</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permit->actual_end->format('M d, Y H:i') }}</p>
                        </div>
                        @endif

                        <!-- Work Details -->
                        <div class="md:col-span-2 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Work Details</h3>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permit->title }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permit->description }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Work Description</label>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $permit->work_description }}</p>
                        </div>

                        <!-- Safety Information -->
                        <div class="md:col-span-2 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Safety Information</h3>
                        </div>

                        @if($permit->hazards_identified)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Hazards Identified</label>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $permit->hazards_identified }}</p>
                        </div>
                        @endif

                        @if($permit->control_measures)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Control Measures</label>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $permit->control_measures }}</p>
                        </div>
                        @endif

                        @if($permit->ppe_required)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">PPE Required</label>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $permit->ppe_required }}</p>
                        </div>
                        @endif

                        @if($permit->emergency_procedures)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Emergency Procedures</label>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $permit->emergency_procedures }}</p>
                        </div>
                        @endif

                        <!-- People Involved -->
                        <div class="md:col-span-2 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">People Involved</h3>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Requested By</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permit->requester->name ?? 'N/A' }}</p>
                        </div>

                        @if($permit->approver)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Approved By</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permit->approver->name }}</p>
                        </div>
                        @endif

                        @if($permit->issuer)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Issued By</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permit->issuer->name }}</p>
                        </div>
                        @endif

                        @if($permit->closer)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Closed By</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $permit->closer->name }}</p>
                        </div>
                        @endif

                        <!-- Attachments -->
                        @if($permit->attachments && count($permit->attachments) > 0)
                        <div class="md:col-span-2 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Attachments</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($permit->attachments as $attachment)
                                    <div class="border rounded-lg p-4">
                                        <a href="{{ Storage::url($attachment) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                            {{ basename($attachment) }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Closure Notes -->
                        @if($permit->closure_notes)
                        <div class="md:col-span-2 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Closure Notes</h3>
                            <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $permit->closure_notes }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="mt-8 flex items-center justify-start">
                        <a href="{{ route('permits.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to Permits
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Close Permit Modal -->
    <div id="closeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" id="my-modal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Close Permit</h3>
                <form method="POST" action="{{ route('permits.close', $permit) }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="closure_notes" class="block text-sm font-medium text-gray-700">Closure Notes</label>
                        <textarea id="closure_notes" name="closure_notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                    </div>
                    <div class="flex items-center justify-end">
                        <button type="button" onclick="closeCloseModal()" class="mr-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Cancel
                        </button>
                        <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                            Close Permit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCloseModal() {
            document.getElementById('closeModal').classList.remove('hidden');
        }

        function closeCloseModal() {
            document.getElementById('closeModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
