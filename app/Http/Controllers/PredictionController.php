<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forecast;

class PredictionController extends Controller
{
    public function index()
    {
        $forecasts = Forecast::orderBy('forecast_date')->get();

        return view('predictions.index', compact('forecasts'));
    }
}