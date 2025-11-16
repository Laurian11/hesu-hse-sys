<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('PPE Item Details') }}
            </h2>
            <div class="flex space-x-2">
                @can('ppe.manage')
                    <a href="{{ route('ppe-inventory.edit', $ppeInventory) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endcan
                <a href="{{ route('ppe-inventory.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Item Code -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Item Code</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $ppeInventory->item_code }}</p>
                        </div>

                        <!-- Name -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Name</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $ppeInventory->name }}</p>
                        </div>

                        <!-- Category -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Category</h3>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $ppeInventory->category)) }}
                                </span>
                            </p>
                        </div>

                        <!-- Status -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($ppeInventory->status === 'active') bg-green-100 text-green-800
                                    @elseif($ppeInventory->status === 'discontinued') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $ppeInventory->status)) }}
                                </span>
                            </p>
                        </div>

                        <!-- Size -->
                        @if($ppeInventory->size)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Size</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $ppeInventory->size }}</p>
                            </div>
                        @endif

                        <!-- Color -->
                        @if($ppeInventory->color)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Color</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $ppeInventory->color }}</p>
                            </div>
                        @endif

                        <!-- Quantity Available -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Quantity Available</h3>
                            <p class="mt-1 text-sm {{ $ppeInventory->isLowStock() ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                {{ $ppeInventory->quantity_available }}
                                @if($ppeInventory->isLowStock())
                                    <span class="text-xs text-red-500">(Low Stock - Minimum: {{ $ppeInventory->quantity_minimum }})</span>
                                @endif
                            </p>
                        </div>

                        <!-- Quantity Minimum -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Minimum Stock Level</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $ppeInventory->quantity_minimum }}</p>
                        </div>

                        <!-- Unit Cost -->
                        @if($ppeInventory->unit_cost)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Unit Cost</h3>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($ppeInventory->unit_cost, 2) }}</p>
                            </div>
                        @endif

                        <!-- Supplier -->
                        @if($ppeInventory->supplier)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Supplier</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $ppeInventory->supplier }}</p>
                            </div>
                        @endif

                        <!-- Last Restocked -->
                        @if($ppeInventory->last_restocked)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Last Restocked</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $ppeInventory->last_restocked->format('M d, Y') }}</p>
                            </div>
                        @endif

                        <!-- Expiry Date -->
                        @if($ppeInventory->expiry_date)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Expiry Date</h3>
                                <p class="mt-1 text-sm {{ $ppeInventory->isExpired() ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                    {{ $ppeInventory->expiry_date->format('M d, Y') }}
                                    @if($ppeInventory->isExpired())
                                        <span class="text-xs text-red-500">(Expired)</span>
                                    @endif
                                </p>
                            </div>
                        @endif

                        <!-- Location -->
                        @if($ppeInventory->location)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Storage Location</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ $ppeInventory->location }}</p>
                            </div>
                        @endif

                        <!-- Created By -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Created By</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $ppeInventory->creator->name }}</p>
                        </div>

                        <!-- Created At -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Created At</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $ppeInventory->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($ppeInventory->description)
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-500">Description</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $ppeInventory->description }}</p>
                        </div>
                    @endif

                    <!-- Attachments -->
                    @if($ppeInventory->attachments && count($ppeInventory->attachments) > 0)
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-gray-500">Attachments</h3>
                            <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($ppeInventory->attachments as $attachment)
                                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                        <svg class="w-8 h-8 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ basename($attachment) }}
                                            </p>
                                            <a href="{{ Storage::url($attachment) }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-500">
                                                View/Download
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @can('ppe.manage')
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <form method="POST" action="{{ route('ppe-inventory.destroy', $ppeInventory) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this PPE item?')">
                                    Delete Item
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
