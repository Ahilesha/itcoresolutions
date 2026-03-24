<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Predictions
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-semibold mb-4">Forecast Results</h3>
                    <div class="mb-6">
                        <canvas id="forecastChart"></canvas>
                    </div>
                    <table class="min-w-full border">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">Date</th>
                                <th class="border px-4 py-2">Predicted Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($forecasts as $forecast)
                                <tr>
                                    <td class="border px-4 py-2">{{ $forecast->forecast_date }}</td>
                                    <td class="border px-4 py-2">{{ $forecast->predicted_qty }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labels = @json($forecasts->pluck('forecast_date'));
    const data = @json($forecasts->pluck('predicted_qty'));

    new Chart(document.getElementById('forecastChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Predicted Demand',
                data: data,
                tension: 0.3
            }]
        }
    });
</script>
</x-app-layout>