<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Risk Assessment Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Assessment #{{ $riskAssessment->assessment_number }}</h3>
                        <div>
                            <a href="{{ route('risk-assessments.edit', $riskAssessment) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Edit
                            </a>
                            <a href="{{ route('risk-assessments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
                            <p class="mt-1 text-sm text-gray-900">{{ $riskAssessment->title }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Assessment Number</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $riskAssessment->assessment_number }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Risk Level</label>
                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($riskAssessment->risk_level == 'low') bg-green-100 text-green-800
                                @elseif($riskAssessment->risk_level == 'medium') bg-yellow-100 text-yellow-800
                                @elseif($riskAssessment->risk_level == 'high') bg-orange-100 text-orange-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($riskAssessment->risk_level) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Probability</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst($riskAssessment->probability) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Impact</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst($riskAssessment->impact) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($riskAssessment->status == 'draft') bg-gray-100 text-gray-800
                                @elseif($riskAssessment->status == 'pending_review') bg-blue-100 text-blue-800
                                @elseif($riskAssessment->status == 'approved') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $riskAssessment->status)) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Assessment Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $riskAssessment->assessment_date->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created By</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $riskAssessment->creator->name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Created Date</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $riskAssessment->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $riskAssessment->description }}</p>
                        </div>

                        <!-- Mitigation Plan -->
                        @if($riskAssessment->mitigation_plan)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Mitigation Plan</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $riskAssessment->mitigation_plan }}</p>
                            </div>
                        @endif

                        <!-- Residual Risk -->
                        @if($riskAssessment->residual_risk)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Residual Risk</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $riskAssessment->residual_risk }}</p>
                            </div>
                        @endif

                        <!-- Review Details -->
                        @if($riskAssessment->status != 'draft')
                            <div class="md:col-span-2 mt-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-4">Review Details</h4>
                            </div>

                            @if($riskAssessment->reviewer)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Reviewed By</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $riskAssessment->reviewer->name }}</p>
                                </div>
                            @endif

                            @if($riskAssessment->review_date)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Review Date</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $riskAssessment->review_date->format('M d, Y') }}</p>
                                </div>
                            @endif
                        @endif

                        <!-- Attachments -->
                        @if($riskAssessment->attachments)
                            <div class="md:col-span-2 mt-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-4">Attachments</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($riskAssessment->attachments as $attachment)
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
