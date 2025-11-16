<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Actions Panel -->
            <div class="bg-white mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                        <div class="flex space-x-2">
                            @can('user.create')
                                <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:shadow-lg transition ease-in-out duration-150">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add User
                                </a>
                            @endcan
                            @can('user.manage')
                                <button onclick="toggleBulkActions()" class="inline-flex items-center px-3 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:shadow-lg transition ease-in-out duration-150">
                                    <i class="fas fa-tasks"></i>
                                </button>
                                <button onclick="exportUsers('excel')" class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:shadow-lg transition ease-in-out duration-150">
                                    <i class="fas fa-file-excel"></i>
                                </button>
                                <button onclick="exportUsers('csv')" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:shadow-lg transition ease-in-out duration-150">
                                    <i class="fas fa-file-csv"></i>
                                </button>
                                <button onclick="document.getElementById('import-file').click()" class="inline-flex items-center px-3 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:shadow-lg transition ease-in-out duration-150">
                                    <i class="fas fa-upload"></i>
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions Panel (Hidden by default) -->
            <div id="bulk-actions" class="bg-white mb-6 hidden">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-700"><span id="selected-count">0</span> users selected</span>
                        <div class="flex space-x-2">
                            <button onclick="bulkActivateUsers()" class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:shadow-lg transition ease-in-out duration-150">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="bulkDeactivateUsers()" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:shadow-lg transition ease-in-out duration-150">
                                <i class="fas fa-times"></i>
                            </button>
                            <button onclick="bulkDeleteUsers()" class="inline-flex items-center px-3 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:shadow-lg transition ease-in-out duration-150">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" id="search" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search users...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select id="role-filter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Roles</option>
                                @foreach(\App\Models\Role::all() as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select id="status-filter" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button onclick="applyFilters()" class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:shadow-lg transition ease-in-out duration-150">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="users-table-body">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" class="user-checkbox" value="{{ $user->id }}" onchange="updateSelectedCount()">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $user->name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $user->companies->where('id', session('current_company_id'))->first()?->pivot?->role?->name ?? 'No Role' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @can('user.view')
                                                    <a href="{{ route('users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endcan
                                                @can('user.edit')
                                                    <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('user.delete')
                                                    <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to remove this user from the company?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            <i class="fas fa-user-times"></i>
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
                        {{ $users->links() }}
                    </div>
                </div>
            </div>

            <!-- Hidden Import Form -->
            <form id="import-form" action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                @csrf
                <input type="file" id="import-file" name="file" accept=".xlsx,.xls,.csv" onchange="submitImport()">
            </form>
        </div>
    </div>

    <script>
        function toggleBulkActions() {
            const bulkActions = document.getElementById('bulk-actions');
            bulkActions.classList.toggle('hidden');
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            document.getElementById('selected-count').textContent = checkboxes.length;
        }

        function applyFilters() {
            const search = document.getElementById('search').value;
            const role = document.getElementById('role-filter').value;
            const status = document.getElementById('status-filter').value;

            // Implement AJAX filtering here
            console.log('Filters:', { search, role, status });
        }

        function exportUsers(format) {
            const selected = document.querySelectorAll('.user-checkbox:checked');
            const ids = Array.from(selected).map(cb => cb.value);

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("users.export") }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const formatInput = document.createElement('input');
            formatInput.type = 'hidden';
            formatInput.name = 'format';
            formatInput.value = format;
            form.appendChild(formatInput);

            if (ids.length > 0) {
                ids.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
            }

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        function bulkActivateUsers() {
            const selected = document.querySelectorAll('.user-checkbox:checked');
            if (selected.length === 0) return alert('Please select users first');

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("users.bulk-activate") }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            Array.from(selected).forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        function bulkDeactivateUsers() {
            const selected = document.querySelectorAll('.user-checkbox:checked');
            if (selected.length === 0) return alert('Please select users first');

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("users.bulk-deactivate") }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            Array.from(selected).forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        function bulkDeleteUsers() {
            const selected = document.querySelectorAll('.user-checkbox:checked');
            if (selected.length === 0) return alert('Please select users first');
            if (!confirm('Are you sure you want to delete selected users?')) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("users.bulk-delete") }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);

            Array.from(selected).forEach(cb => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        function submitImport() {
            document.getElementById('import-form').submit();
        }
    </script>
</x-app-layout>
