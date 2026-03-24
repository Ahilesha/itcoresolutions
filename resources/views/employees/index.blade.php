<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Employees</h2>

            @can('employees.create')
                <a href="{{ route('employees.create') }}"
                   class="px-4 py-2 rounded bg-indigo-600 text-white text-sm hover:bg-indigo-700">
                    + Add Employee
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                            <tr class="text-left text-gray-600 border-b">
                                <th class="py-2">Code</th>
                                <th class="py-2">Name</th>
                                <th class="py-2">Designation</th>
                                <th class="py-2">Roles</th>
                                <th class="py-2">Linked User</th>
                                <th class="py-2">Active</th>
                                <th class="py-2 text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($employees as $employee)
                                <tr class="border-b">
                                    <td class="py-3">{{ $employee->employee_code ?? '-' }}</td>
                                    <td class="py-3 font-medium">{{ $employee->name }}</td>
                                    <td class="py-3">{{ $employee->designation ?? '-' }}</td>
                                    <td class="py-3">
                                        @forelse($employee->roles as $r)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-800 text-xs mr-1">{{ $r->name }}</span>
                                        @empty
                                            <span class="text-gray-400">-</span>
                                        @endforelse
                                    </td>
                                    <td class="py-3">{{ $employee->user?->email ?? '-' }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 rounded text-xs {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $employee->is_active ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right space-x-2">
                                        @can('employees.update')
                                            <a href="{{ route('employees.edit', $employee) }}"
                                               class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">Edit</a>
                                        @endcan

                                        @can('employees.delete')
                                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Delete this employee?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-gray-500">No employees yet.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
