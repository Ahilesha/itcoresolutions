<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExportMLData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ml:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export inventory data for ML forecasting';

    /**
     * Execute the console command.
     */
    public function handle()
    {

    // 📊 Product Demand Data
    $productDemand = DB::select("
        SELECT 
            DATE(o.placed_at) as date,
            oi.product_id,
            SUM(oi.quantity) as total_qty
        FROM order_items oi
        JOIN orders o ON o.id = oi.order_id
        GROUP BY date, oi.product_id
        ORDER BY date
    ");

    // 📊 Material Consumption Data
    $materialConsumption = DB::select("
        SELECT
            DATE(created_at) as date,
            material_id,
            SUM(qty) as consumed
        FROM stock_logs
        WHERE type = 'deduct'
        GROUP BY date, material_id
        ORDER BY date
    ");

    // 📁 Create directory if not exists
    Storage::makeDirectory('ml');

    // 📝 Convert Product Demand to CSV
    $productCsv = "date,product_id,total_qty\n";
    foreach ($productDemand as $row) {
        $productCsv .= "{$row->date},{$row->product_id},{$row->total_qty}\n";
    }
    Storage::put('ml/product_demand.csv', $productCsv);

    // 📝 Convert Material Consumption to CSV
    $materialCsv = "date,material_id,consumed\n";
    foreach ($materialConsumption as $row) {
        $materialCsv .= "{$row->date},{$row->material_id},{$row->consumed}\n";
    }
    Storage::put('ml/material_consumption.csv', $materialCsv);

    $this->info('ML CSV data exported successfully');
}
    }

