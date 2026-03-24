<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Supplier</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input name="name" value="{{ old('name') }}" required
                                   class="mt-1 w-full rounded border-gray-300" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <input name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded border-gray-300" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input name="email" value="{{ old('email') }}" class="mt-1 w-full rounded border-gray-300" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea name="address" rows="3" class="mt-1 w-full rounded border-gray-300">{{ old('address') }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('suppliers.index') }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Cancel</a>
                            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
