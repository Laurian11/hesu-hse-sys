<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add PPE Item') }}
            </h2>
            <a href="{{ route('ppe-inventory.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('ppe-inventory.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Category -->
                            <div>
                                <x-input-label for="category" :value="__('Category')" />
                                <select id="category" name="category" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select Category</option>
                                    <option value="helmet" {{ old('category') == 'helmet' ? 'selected' : '' }}>Helmet</option>
                                    <option value="gloves" {{ old('category') == 'gloves' ? 'selected' : '' }}>Gloves</option>
                                    <option value="boots" {{ old('category') == 'boots' ? 'selected' : '' }}>Boots</option>
                                    <option value="vest" {{ old('category') == 'vest' ? 'selected' : '' }}>Safety Vest</option>
                                    <option value="goggles" {{ old('category') == 'goggles' ? 'selected' : '' }}>Safety Goggles</option>
                                    <option value="mask" {{ old('category') == 'mask' ? 'selected' : '' }}>Face Mask</option>
                                    <option value="respirator" {{ old('category') == 'respirator' ? 'selected' : '' }}>Respirator</option>
                                    <option value="ear_protection" {{ old('category') == 'ear_protection' ? 'selected' : '' }}>Ear Protection</option>
                                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>

                            <!-- Size -->
                            <div>
                                <x-input-label for="size" :value="__('Size')" />
                                <x-text-input id="size" class="block mt-1 w-full" type="text" name="size" :value="old('size')" placeholder="e.g., M, L, XL, 10, 11" />
                                <x-input-error :messages="$errors->get('size')" class="mt-2" />
                            </div>

                            <!-- Color -->
                            <div>
                                <x-input-label for="color" :value="__('Color')" />
                                <x-text-input id="color" class="block mt-1 w-full" type="text" name="color" :value="old('color')" placeholder="e.g., Yellow, Black" />
                                <x-input-error :messages="$errors->get('color')" class="mt-2" />
                            </div>

                            <!-- Quantity Available -->
                            <div>
                                <x-input-label for="quantity_available" :value="__('Quantity Available')" />
                                <x-text-input id="quantity_available" class="block mt-1 w-full" type="number" name="quantity_available" :value="old('quantity_available')" min="0" required />
                                <x-input-error :messages="$errors->get('quantity_available')" class="mt-2" />
                            </div>

                            <!-- Quantity Minimum -->
                            <div>
                                <x-input-label for="quantity_minimum" :value="__('Minimum Stock Level')" />
                                <x-text-input id="quantity_minimum" class="block mt-1 w-full" type="number" name="quantity_minimum" :value="old('quantity_minimum', 10)" min="0" required />
                                <x-input-error :messages="$errors->get('quantity_minimum')" class="mt-2" />
                            </div>

                            <!-- Unit Cost -->
                            <div>
                                <x-input-label for="unit_cost" :value="__('Unit Cost ($)" />
                                <x-text-input id="unit_cost" class="block mt-1 w-full" type="number" name="unit_cost" :value="old('unit_cost')" step="0.01" min="0" />
                                <x-input-error :messages="$errors->get('unit_cost')" class="mt-2" />
                            </div>

                            <!-- Supplier -->
                            <div>
                                <x-input-label for="supplier" :value="__('Supplier')" />
                                <x-text-input id="supplier" class="block mt-1 w-full" type="text" name="supplier" :value="old('supplier')" />
                                <x-input-error :messages="$errors->get('supplier')" class="mt-2" />
                            </div>

                            <!-- Last Restocked -->
                            <div>
                                <x-input-label for="last_restocked" :value="__('Last Restocked Date')" />
                                <x-text-input id="last_restocked" class="block mt-1 w-full" type="date" name="last_restocked" :value="old('last_restocked')" />
                                <x-input-error :messages="$errors->get('last_restocked')" class="mt-2" />
                            </div>

                            <!-- Expiry Date -->
                            <div>
                                <x-input-label for="expiry_date" :value="__('Expiry Date')" />
                                <x-text-input id="expiry_date" class="block mt-1 w-full" type="date" name="expiry_date" :value="old('expiry_date')" />
                                <x-input-error :messages="$errors->get('expiry_date')" class="mt-2" />
                            </div>

                            <!-- Location -->
                            <div>
                                <x-input-label for="location" :value="__('Storage Location')" />
                                <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" placeholder="e.g., Warehouse A, Shelf 3" />
                                <x-input-error :messages="$errors->get('location')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="discontinued" {{ old('status') == 'discontinued' ? 'selected' : '' }}>Discontinued</option>
                                    <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-6">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Additional details about the PPE item...">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Attachments -->
                        <div class="mt-6">
                            <x-input-label for="attachments" :value="__('Attachments (Optional)')" />
                            <input id="attachments" type="file" name="attachments[]" multiple class="block mt-1 w-full" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
                            <p class="mt-1 text-sm text-gray-600">Upload product manuals, certificates, or images (PDF, DOC, DOCX, JPG, PNG - Max 5MB each)</p>
                            <x-input-error :messages="$errors->get('attachments')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Add PPE Item') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
