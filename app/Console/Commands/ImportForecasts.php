<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportForecasts extends Command
{
    protected $signature = 'ml:import';
    protected $description = 'Import ML predictions into forecasts table';

    public function handle()
    {
        $filePath = base_path('predictions_products.csv');

        if (!file_exists($filePath)) {
            $this->error('Predictions file not found!');
            return;
        }

        $file = fopen($filePath, 'r');

        // Skip header
        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {

            DB::table('forecasts')->insert([
                'item_type' => 'product',
                'item_id' => 1, // placeholder (we’ll improve later)
                'forecast_date' => $row[0],
                'predicted_qty' => round($row[1]),
                'model_version' => 'prophet_v1',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        fclose($file);

        $this->info('Forecasts imported successfully');
    }
}