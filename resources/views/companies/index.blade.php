<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-black leading-tight">
            {{ __('Companies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden card">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-black">Manage Companies</h3>
                        @can('company.create')
                            <a href="{{ route('companies.create') }}" class="btn inline-flex items-center px-4 py-2 bg-black border border-black rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-plus mr-2"></i>
                                Add Company
                            </a>
                        @endcan
                    </div>

                    <!-- Quick Actions Panel -->
                    <div class="mb-6 bg-gray-50 rounded-lg p-4 panel">
                        <h4 class="text-sm font-medium text-black mb-3">Quick Actions</h4>
                        <div class="flex flex-wrap gap-3">
                            @can('company.create')
                                <a href="{{ route('companies.create') }}" class="btn inline-flex items-center px-3 py-2 bg-black text-white text-sm font-medium rounded-md hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-plus mr-2"></i>
                                    New Company
                                </a>
                            @endcan
                            <div class="flex gap-2">
                                <button onclick="exportCompanies('excel')" class="btn inline-flex items-center px-3 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-file-excel mr-2"></i>
                                    Export Excel
                                </button>
                                <button onclick="exportCompanies('csv')" class="btn inline-flex items-center px-3 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-file-csv mr-2"></i>
                                    Export CSV
                                </button>
                            </div>
                            <button onclick="importCompanies()" class="btn inline-flex items-center px-3 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-upload mr-2"></i>
                                Import
                            </button>
                            <button onclick="bulkActions()" class="btn inline-flex items-center px-3 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-tasks mr-2"></i>
                                Bulk Actions
                            </button>
                        </div>
                    </div>

                    <!-- Filters and Search -->
                    <div class="mb-6 bg-white border border-gray-200 rounded-lg p-4 panel">
                        <div class="flex flex-wrap gap-4 items-end">
                            <div class="flex-1 min-w-64">
                                <label class="block text-sm font-medium text-black mb-1">Search Companies</label>
                                <input type="text" id="searchInput" placeholder="Search by name, slug, or description..." class="w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-black mb-1">Status</label>
                                <select id="statusFilter" class="border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div>
                                <button onclick="applyFilters()" class="btn inline-flex items-center px-4 py-2 bg-black text-white text-sm font-medium rounded-md hover:bg-gray-800 focus:bg-gray-800 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-filter mr-2"></i>
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-black focus:ring-black">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departments</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employees</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($companies as $company)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" class="company-checkbox rounded border-gray-300 text-black focus:ring-black" value="{{ $company->id }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-12">
                                                    @if($company->logo_url)
                                                        <img class="h-12 w-12 rounded-lg object-cover" src="{{ asset('storage/' . $company->logo_url) }}" alt="{{ $company->name }}">
                                                    @else
                                                        <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-600">{{ substr($company->name, 0, 2) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $company->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $company->slug }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $company->description ?? 'No description' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $company->departments->count() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $company->employees->count() }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $company->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $company->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-1">
                                                @can('company.view')
                                                    <a href="{{ route('companies.show', $company) }}" class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 hover:shadow-md transition ease-in-out duration-150" title="View Company">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                @endcan
                                                @can('company.edit')
                                                    <a href="{{ route('companies.edit', $company) }}" class="inline-flex items-center justify-center w-8 h-8 bg-yellow-50 text-yellow-700 rounded-md hover:bg-yellow-100 hover:shadow-md transition ease-in-out duration-150" title="Edit Company">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </a>
                                                @endcan
                                                @can('company.delete')
                                                    <form method="POST" action="{{ route('companies.destroy', $company) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this company? This action cannot be undone.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-700 rounded-md hover:bg-red-100 hover:shadow-md transition ease-in-out duration-150" title="Delete Company">
                                                            <i class="fas fa-trash text-sm"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 bg-gray-50">
                        {{ $companies->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.company-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Status filter
        document.getElementById('statusFilter').addEventListener('change', function() {
            const status = this.value;
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                if (!status) {
                    row.style.display = '';
                    return;
                }

                const statusCell = row.querySelector('td:nth-child(7) span');
                const isActive = statusCell.textContent.trim().toLowerCase() === 'active';
                const matches = (status === 'active' && isActive) || (status === 'inactive' && !isActive);
                row.style.display = matches ? '' : 'none';
            });
        });

        function applyFilters() {
            // Trigger both search and status filter
            document.getElementById('searchInput').dispatchEvent(new Event('input'));
            document.getElementById('statusFilter').dispatchEvent(new Event('change'));
        }

        function exportCompanies(format) {
            // Implement export functionality
            const selected = document.querySelectorAll('.company-checkbox:checked');
            const ids = Array.from(selected).map(cb => cb.value);

            if (ids.length === 0) {
                alert('Please select companies to export');
                return;
            }

            // Create a form to submit the export request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("companies.export") }}';
            form.style.display = 'none';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add format parameter
            const formatInput = document.createElement('input');
            formatInput.type = 'hidden';
            formatInput.name = 'format';
            formatInput.value = format;
            form.appendChild(formatInput);

            // Add selected IDs
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'company_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        function importCompanies() {
            // Open file input dialog
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = '.csv,.xlsx,.xls';
            input.onchange = function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Create form data for upload
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    // Show loading indicator
                    const button = document.querySelector('button[onclick="importCompanies()"]');
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Importing...';
                    button.disabled = true;

                    // Send AJAX request
                    fetch('{{ route("companies.import") }}', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Companies imported successfully!');
                            location.reload();
                        } else {
                            alert('Import failed: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        alert('Import failed: ' + error.message);
                    })
                    .finally(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    });
                }
            };
            input.click();
        }

        function bulkActions() {
            const selected = document.querySelectorAll('.company-checkbox:checked');
            if (selected.length === 0) {
                alert('Please select companies first');
                return;
            }

            const action = prompt(`Choose bulk action for ${selected.length} companies:\n1. Activate\n2. Deactivate\n3. Delete\n\nEnter action number:`);

            if (!action) return;

            const ids = Array.from(selected).map(cb => cb.value);
            let endpoint, method;

            switch (action) {
                case '1':
                    endpoint = '{{ route("companies.bulk-activate") }}';
                    method = 'POST';
                    break;
                case '2':
                    endpoint = '{{ route("companies.bulk-deactivate") }}';
                    method = 'POST';
                    break;
                case '3':
                    if (!confirm(`Are you sure you want to delete ${selected.length} companies? This action cannot be undone.`)) return;
                    endpoint = '{{ route("companies.bulk-delete") }}';
                    method = 'DELETE';
                    break;
                default:
                    alert('Invalid action');
                    return;
            }

            // Create form to submit bulk action
            const form = document.createElement('form');
            form.method = method;
            form.action = endpoint;
            form.style.display = 'none';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add selected IDs
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'company_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    </script>
</x-app-layout>
