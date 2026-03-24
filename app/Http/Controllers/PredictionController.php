<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PredictionController extends Controller
{
    public function index()
    {
        $daysHistory = 30;
        $daysAhead = 7;

        $products = Product::with(['materials.unit'])->orderBy('name')->get();

        $labels = collect(range(1, $daysAhead))
            ->map(fn ($i) => now()->addDays($i)->format('Y-m-d'))
            ->values()
            ->all();

        $chartDatasets = [];
        $predictionRows = [];
        $reorderRows = [];

        foreach ($products as $product) {
            $series = $this->buildDailySeries($product->id, $daysHistory);

            if (count($series) < 2) {
                continue;
            }

            [$slope, $intercept] = $this->linearRegression($series);

            $historyAvg = round(collect($series)->avg('y'), 2);

            $future = [];
            foreach (range(1, $daysAhead) as $i) {
                $x = $daysHistory + ($i - 1);
                $qty = max(0, round(($slope * $x) + $intercept, 2));

                $future[] = [
                    'date' => now()->addDays($i)->format('Y-m-d'),
                    'qty' => $qty,
                ];
            }

            $tomorrowQty = $future[0]['qty'] ?? 0;
            $day3Qty = $future[2]['qty'] ?? 0;
            $day7Qty = $future[6]['qty'] ?? 0;

            $status = $tomorrowQty <= max(1, round($historyAvg * 0.7, 2))
                ? 'Less Orders / Low Demand'
                : 'Normal';

            $trend = 'Flat';
            if ($slope > 0) {
                $trend = 'Increasing';
            } elseif ($slope < 0) {
                $trend = 'Decreasing';
            }

            $predictionRows[] = [
                'product_name' => $product->name,
                'history_avg' => $historyAvg,
                'tomorrow_qty' => $tomorrowQty,
                'day_3_qty' => $day3Qty,
                'day_7_qty' => $day7Qty,
                'trend' => $trend,
                'status' => $status,
            ];

            $chartDatasets[] = [
                'label' => $product->name,
                'data' => array_map(fn ($row) => $row['qty'], $future),
                'borderWidth' => 2,
                'tension' => 0.3,
            ];

            foreach ($product->materials as $material) {
                $requiredTomorrow = round($tomorrowQty * (float) $material->pivot->qty_per_product, 3);
                $stock = (float) $material->stock;
                $threshold = (float) $material->threshold;
                $suggestedReorder = max(0, round(($threshold + $requiredTomorrow) - $stock, 3));

                if ($suggestedReorder > 0) {
                    $reorderRows[] = [
                        'product_name' => $product->name,
                        'material_name' => $material->name,
                        'unit' => $material->unit?->symbol ?? '',
                        'current_stock' => $stock,
                        'threshold' => $threshold,
                        'required_tomorrow' => $requiredTomorrow,
                        'suggested_reorder' => $suggestedReorder,
                    ];
                }
            }
        }

        usort($predictionRows, fn ($a, $b) => $a['tomorrow_qty'] <=> $b['tomorrow_qty']);
        usort($reorderRows, fn ($a, $b) => $b['suggested_reorder'] <=> $a['suggested_reorder']);

        return view('predictions.index', compact(
            'labels',
            'chartDatasets',
            'predictionRows',
            'reorderRows'
        ));
    }

    private function buildDailySeries(int $productId, int $daysHistory): array
    {
        $startDate = now()->subDays($daysHistory - 1)->startOfDay();
        $endDate = now()->endOfDay();

        $rows = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('DATE(orders.placed_at) as day, SUM(order_items.quantity) as total_qty')
            ->where('order_items.product_id', $productId)
            ->whereBetween('orders.placed_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'Cancelled')
            ->groupByRaw('DATE(orders.placed_at)')
            ->orderByRaw('DATE(orders.placed_at)')
            ->get()
            ->keyBy('day');

        $series = [];

        for ($i = 0; $i < $daysHistory; $i++) {
            $date = now()->subDays(($daysHistory - 1) - $i)->format('Y-m-d');

            $series[] = [
                'x' => $i,
                'y' => isset($rows[$date]) ? (float) $rows[$date]->total_qty : 0,
            ];
        }

        return $series;
    }

    private function linearRegression(array $points): array
    {
        $n = count($points);

        if ($n < 2) {
            return [0, 0];
        }

        $sumX = array_sum(array_column($points, 'x'));
        $sumY = array_sum(array_column($points, 'y'));

        $sumXY = 0;
        $sumXX = 0;

        foreach ($points as $point) {
            $sumXY += $point['x'] * $point['y'];
            $sumXX += $point['x'] * $point['x'];
        }

        $denominator = ($n * $sumXX) - ($sumX * $sumX);

        if ($denominator == 0) {
            return [0, $sumY / $n];
        }

        $slope = (($n * $sumXY) - ($sumX * $sumY)) / $denominator;
        $intercept = ($sumY - ($slope * $sumX)) / $n;

        return [$slope, $intercept];
    }
}
