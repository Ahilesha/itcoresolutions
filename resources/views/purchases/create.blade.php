<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Purchase</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
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

                    <form method="POST" action="{{ route('purchases.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Supplier</label>
                                <select name="supplier_id" required class="mt-1 w-full rounded border-gray-300">
                                    <option value="">-- Select --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Purchase Date</label>
                                <input type="date" name="purchase_date" value="{{ old('purchase_date', now()->toDateString()) }}" required
                                       class="mt-1 w-full rounded border-gray-300" />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Reference No</label>
                                <input name="reference_no" value="{{ old('reference_no') }}" class="mt-1 w-full rounded border-gray-300" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" rows="2" class="mt-1 w-full rounded border-gray-300">{{ old('notes') }}</textarea>
                        </div>

                        <div class="border rounded-lg">
                            <div class="p-4 border-b flex items-center justify-between">
                                <h3 class="font-semibold">Items</h3>
                                <button type="button" id="addRow" class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-sm">+ Add Row</button>
                            </div>
                            <div class="p-4 overflow-x-auto">
                                <table class="min-w-full text-sm" id="itemsTable">
                                    <thead>
                                    <tr class="text-left text-gray-600 border-b">
                                        <th class="py-2">Material</th>
                                        <th class="py-2">Qty</th>
                                        <th class="py-2">Unit Price</th>
                                        <th class="py-2 text-right">Remove</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="border-b">
                                        <td class="py-2">
                                            <select name="items[0][material_id]" required class="w-full rounded border-gray-300">
                                                <option value="">-- Select --</option>
                                                @foreach($materials as $material)
                                                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="py-2"><input name="items[0][qty]" required type="number" step="0.001" min="0.001" class="w-full rounded border-gray-300" /></td>
                                        <td class="py-2"><input name="items[0][unit_price]" type="number" step="0.01" min="0" class="w-full rounded border-gray-300" /></td>
                                        <td class="py-2 text-right"><button type="button" class="removeRow px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">X</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <p class="text-xs text-gray-500 mt-2">Saving a purchase will automatically increase the selected materials' stock.</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('purchases.index') }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Cancel</a>
                            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Save Purchase</button>
                        </div>
                    </form>

                    <script>
                        (function () {
                            const tableBody = document.querySelector('#itemsTable tbody');
                            const addRowBtn = document.getElementById('addRow');
                            let index = 1;

                            function wireRemove(btn) {
                                btn.addEventListener('click', () => {
                                    const row = btn.closest('tr');
                                    if (tableBody.querySelectorAll('tr').length > 1) {
                                        row.remove();
                                    }
                                });
                            }

                            tableBody.querySelectorAll('.removeRow').forEach(wireRemove);

                            addRowBtn.addEventListener('click', () => {
                                const row = document.createElement('tr');
                                row.className = 'border-b';
                                row.innerHTML = `
                                    <td class="py-2">
                                        <select name="items[${index}][material_id]" required class="w-full rounded border-gray-300">
                                            <option value="">-- Select --</option>
                                            ${@json($materials).map(m => `<option value="${m.id}">${m.name}</option>`).join('')}
                                        </select>
                                    </td>
                                    <td class="py-2"><input name="items[${index}][qty]" required type="number" step="0.001" min="0.001" class="w-full rounded border-gray-300" /></td>
                                    <td class="py-2"><input name="items[${index}][unit_price]" type="number" step="0.01" min="0" class="w-full rounded border-gray-300" /></td>
                                    <td class="py-2 text-right"><button type="button" class="removeRow px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700">X</button></td>
                                `;
                                tableBody.appendChild(row);
                                wireRemove(row.querySelector('.removeRow'));
                                index++;
                            });
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
