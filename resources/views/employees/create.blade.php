<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Employee</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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

                    <form method="POST" action="{{ route('employees.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Employee Code</label>
                                <input name="employee_code" value="{{ old('employee_code') }}" class="mt-1 w-full rounded border-gray-300" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded border-gray-300" />
                            </div>
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

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Designation</label>
                                <input name="designation" value="{{ old('designation') }}" class="mt-1 w-full rounded border-gray-300" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Joining Date</label>
                                <input type="date" name="joining_date" value="{{ old('joining_date') }}" class="mt-1 w-full rounded border-gray-300" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Linked User (for login & punch)</label>
                                <select name="user_id" class="mt-1 w-full rounded border-gray-300">
                                    <option value="">-- None --</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" @selected(old('user_id') == $u->id)>
                                            {{ $u->name }} ({{ $u->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Employee Roles (separate from User Roles)</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                @foreach($roles as $role)
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" class="rounded border-gray-300"
                                               @checked(is_array(old('role_ids')) && in_array($role->id, old('role_ids')) ) />
                                        <span class="text-sm">{{ $role->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300" checked />
                                <span class="text-sm">Active</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('employees.index') }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Cancel</a>
                            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
