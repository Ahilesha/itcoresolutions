<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Predictions & Reorder Suggestions
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Next 7 Days Demand Prediction (Linear Regression)</h3>

                    @if(count($chartDatasets) > 0)
                        <div class="mb-6">
                            <canvas id="forecastChart" height="100"></canvas>
                        </div>
                    @else
                        <div class="text-gray-500">
                            No order history available yet to generate predictions.
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Prediction Summary</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-left">Product</th>
                                    <th class="border px-4 py-2 text-left">Avg / Day</th>
                                    <th class="border px-4 py-2 text-left">Tomorrow</th>
                                    <th class="border px-4 py-2 text-left">After 3 Days</th>
                                    <th class="border px-4 py-2 text-left">After 7 Days</th>
                                    <th class="border px-4 py-2 text-left">Trend</th>
                                    <th class="border px-4 py-2 text-left">Prediction Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($predictionRows as $row)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $row['product_name'] }}</td>
                                        <td class="border px-4 py-2">{{ $row['history_avg'] }}</td>
                                        <td class="border px-4 py-2">{{ $row['tomorrow_qty'] }}</td>
                                        <td class="border px-4 py-2">{{ $row['day_3_qty'] }}</td>
                                        <td class="border px-4 py-2">{{ $row['day_7_qty'] }}</td>
                                        <td class="border px-4 py-2">{{ $row['trend'] }}</td>
                                        <td class="border px-4 py-2">
                                            @if($row['status'] === 'Less Orders / Low Demand')
                                                <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs font-semibold">
                                                    {{ $row['status'] }}
                                                </span>
                                            @else
                                                <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs font-semibold">
                                                    {{ $row['status'] }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="border px-4 py-6 text-center text-gray-500">
                                            No predictions available.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Reorder Suggestions (Based on Tomorrow Prediction)</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-left">Product</th>
                                    <th class="border px-4 py-2 text-left">Material</th>
                                    <th class="border px-4 py-2 text-left">Current Stock</th>
                                    <th class="border px-4 py-2 text-left">Threshold</th>
                                    <th class="border px-4 py-2 text-left">Required Tomorrow</th>
                                    <th class="border px-4 py-2 text-left">Suggested Reorder</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reorderRows as $row)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $row['product_name'] }}</td>
                                        <td class="border px-4 py-2">{{ $row['material_name'] }}</td>
                                        <td class="border px-4 py-2">{{ $row['current_stock'] }} {{ $row['unit'] }}</td>
                                        <td class="border px-4 py-2">{{ $row['threshold'] }} {{ $row['unit'] }}</td>
                                        <td class="border px-4 py-2">{{ $row['required_tomorrow'] }} {{ $row['unit'] }}</td>
                                        <td class="border px-4 py-2 font-semibold text-red-600">{{ $row['suggested_reorder'] }} {{ $row['unit'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="border px-4 py-6 text-center text-green-600 font-medium">
                                            No reorder required based on tomorrow's prediction.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json($labels);
        const datasets = @json($chartDatasets);

        if (datasets.length > 0) {
            new Chart(document.getElementById('forecastChart'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }
    </script>
</x-app-layout>
