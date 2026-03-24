<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Suppliers</h2>

            @can('suppliers.create')
                <a href="{{ route('suppliers.create') }}"
                   class="px-4 py-2 rounded bg-indigo-600 text-white text-sm hover:bg-indigo-700">
                    + Add Supplier
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
                                <th class="py-2">Name</th>
                                <th class="py-2">Phone</th>
                                <th class="py-2">Email</th>
                                <th class="py-2">Address</th>
                                <th class="py-2 text-right">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($suppliers as $supplier)
                                <tr class="border-b">
                                    <td class="py-3 font-medium">{{ $supplier->name }}</td>
                                    <td class="py-3">{{ $supplier->phone }}</td>
                                    <td class="py-3">{{ $supplier->email }}</td>
                                    <td class="py-3">{{ $supplier->address }}</td>
                                    <td class="py-3 text-right space-x-2">
                                        @can('suppliers.update')
                                            <a href="{{ route('suppliers.edit', $supplier) }}"
                                               class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">Edit</a>
                                        @endcan

                                        @can('suppliers.delete')
                                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Delete this supplier?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-500">No suppliers yet.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
