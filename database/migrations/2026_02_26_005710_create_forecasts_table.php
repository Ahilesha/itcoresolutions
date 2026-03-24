<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forecasts', function (Blueprint $table) {
        $table->id();
        $table->string('item_type'); // product or material
        $table->unsignedBigInteger('item_id');
        $table->date('forecast_date');
        $table->integer('predicted_qty');
        $table->string('model_version')->nullable();
        $table->timestamps();

        $table->index(['item_type', 'item_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};
