<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Purchase #{{ $purchase->id }}</h2>
            <a href="{{ route('purchases.index') }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Back</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <div class="text-xs text-gray-500">Date</div>
                            <div class="font-medium">{{ $purchase->purchase_date?->format('Y-m-d') }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Supplier</div>
                            <div class="font-medium">{{ $purchase->supplier?->name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Reference</div>
                            <div class="font-medium">{{ $purchase->reference_no ?? '-' }}</div>
                        </div>
                    </div>

                    @if($purchase->notes)
                        <div>
                            <div class="text-xs text-gray-500">Notes</div>
                            <div class="text-gray-800">{{ $purchase->notes }}</div>
                        </div>
                    @endif

                    <div class="border rounded-lg">
                        <div class="p-4 border-b font-semibold">Items</div>
                        <div class="p-4 overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                <tr class="text-left text-gray-600 border-b">
                                    <th class="py-2">Material</th>
                                    <th class="py-2">Qty</th>
                                    <th class="py-2">Unit Price</th>
                                    <th class="py-2">Line Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($purchase->items as $item)
                                    <tr class="border-b">
                                        <td class="py-3">{{ $item->material?->name }}</td>
                                        <td class="py-3">{{ $item->qty }}</td>
                                        <td class="py-3">{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="py-3">{{ number_format($item->line_total, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 border-t flex justify-end font-semibold">
                            Total: {{ number_format($purchase->total_amount, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
