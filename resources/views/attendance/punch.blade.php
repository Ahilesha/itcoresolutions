<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Punch In / Punch Out</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-3 rounded bg-green-50 text-green-800 border border-green-200">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 rounded bg-red-50 text-red-800 border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <div>
                        <div class="text-sm text-gray-500">Employee</div>
                        <div class="text-lg font-semibold">{{ $employee->name }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 border rounded">
                            <div class="text-xs text-gray-500">Today Punch In</div>
                            <div class="font-medium">{{ $todayAttendance?->punch_in?->format('Y-m-d H:i') ?? '-' }}</div>
                            <form method="POST" action="{{ route('attendance.punchIn') }}" class="mt-3">
                                @csrf
                                <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700"
                                        {{ ($todayAttendance && $todayAttendance->punch_in) ? 'disabled' : '' }}>
                                    Punch In
                                </button>
                            </form>
                        </div>

                        <div class="p-4 border rounded">
                            <div class="text-xs text-gray-500">Today Punch Out</div>
                            <div class="font-medium">{{ $todayAttendance?->punch_out?->format('Y-m-d H:i') ?? '-' }}</div>
                            <form method="POST" action="{{ route('attendance.punchOut') }}" class="mt-3">
                                @csrf
                                <button class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700"
                                        {{ (!$todayAttendance || !$todayAttendance->punch_in || ($todayAttendance && $todayAttendance->punch_out)) ? 'disabled' : '' }}>
                                    Punch Out
                                </button>
                            </form>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500">Note: To enable punch for a user, link that user to an Employee profile in Employees module.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
