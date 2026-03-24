<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Attendance</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-6">

                    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Employee</label>
                            <select name="employee_id" class="mt-1 w-full rounded border-gray-300">
                                <option value="">-- All --</option>
                                @foreach($employees as $e)
                                    <option value="{{ $e->id }}" @selected(request('employee_id') == $e->id)>
                                        {{ $e->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}" class="mt-1 w-full rounded border-gray-300" />
                        </div>
                        <div class="flex items-end gap-2">
                            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Filter</button>
                            <a href="{{ route('attendance.index') }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Reset</a>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                            <tr class="text-left text-gray-600 border-b">
                                <th class="py-2">Date</th>
                                <th class="py-2">Employee</th>
                                <th class="py-2">Punch In</th>
                                <th class="py-2">Punch Out</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($attendances as $a)
                                <tr class="border-b">
                                    <td class="py-3">{{ $a->date?->format('Y-m-d') }}</td>
                                    <td class="py-3 font-medium">{{ $a->employee?->name }}</td>
                                    <td class="py-3">{{ $a->punch_in?->format('Y-m-d H:i') ?? '-' }}</td>
                                    <td class="py-3">{{ $a->punch_out?->format('Y-m-d H:i') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-gray-500">No attendance records found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div>
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
