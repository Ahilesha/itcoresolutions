<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    protected $fillable = [
        'item_type',
        'item_id',
        'forecast_date',
        'predicted_qty',
        'model_version'
    ];
}
