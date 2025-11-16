<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Risk Assessment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('risk-assessments.update', $riskAssessment) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700">Assessment Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $riskAssessment->title) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $riskAssessment->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Risk Level -->
                            <div>
                                <label for="risk_level" class="block text-sm font-medium text-gray-700">Risk Level</label>
                                <select id="risk_level" name="risk_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="low" {{ $riskAssessment->risk_level == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $riskAssessment->risk_level == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $riskAssessment->risk_level == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ $riskAssessment->risk_level == 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                                @error('risk_level')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Probability -->
                            <div>
                                <label for="probability" class="block text-sm font-medium text-gray-700">Probability</label>
                                <select id="probability" name="probability" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="low" {{ $riskAssessment->probability == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $riskAssessment->probability == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $riskAssessment->probability == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('probability')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Impact -->
                            <div>
                                <label for="impact" class="block text-sm font-medium text-gray-700">Impact</label>
                                <select id="impact" name="impact" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="low" {{ $riskAssessment->impact == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $riskAssessment->impact == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $riskAssessment->impact == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('impact')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Assessment Date -->
                            <div>
                                <label for="assessment_date" class="block text-sm font-medium text-gray-700">Assessment Date</label>
                                <input type="date" id="assessment_date" name="assessment_date" value="{{ old('assessment_date', $riskAssessment->assessment_date->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('assessment_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="draft" {{ $riskAssessment->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="pending_review" {{ $riskAssessment->status == 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                                    <option value="approved" {{ $riskAssessment->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $riskAssessment->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Reviewed By -->
                            <div>
                                <label for="reviewed_by" class="block text-sm font-medium text-gray-700">Reviewed By</label>
                                <select id="reviewed_by" name="reviewed_by" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Select Reviewer</option>
                                    @foreach(\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}" {{ $riskAssessment->reviewed_by == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('reviewed_by')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Review Date -->
                            <div>
                                <label for="review_date" class="block text-sm font-medium text-gray-700">Review Date</label>
                                <input type="date" id="review_date" name="review_date" value="{{ old('review_date', $riskAssessment->review_date ? $riskAssessment->review_date->format('Y-m-d') : '') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error('review_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mitigation Plan -->
                            <div class="md:col-span-2">
                                <label for="mitigation_plan" class="block text-sm font-medium text-gray-700">Mitigation Plan</label>
                                <textarea id="mitigation_plan" name="mitigation_plan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('mitigation_plan', $riskAssessment->mitigation_plan) }}</textarea>
                                @error('mitigation_plan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Residual Risk -->
                            <div class="md:col-span-2">
                                <label for="residual_risk" class="block text-sm font-medium text-gray-700">Residual Risk</label>
                                <textarea id="residual_risk" name="residual_risk" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('residual_risk', $riskAssessment->residual_risk) }}</textarea>
                                @error('residual_risk')
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

                                @if($riskAssessment->attachments)
                                    <div class="mt-4">
                                        <h5 class="text-sm font-medium text-gray-700">Current Attachments:</h5>
                                        <ul class="mt-2 text-sm text-gray-600">
                                            @foreach($riskAssessment->attachments as $attachment)
                                                <li>{{ basename($attachment) }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('risk-assessments.show', $riskAssessment) }}" class="mr-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Assessment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
