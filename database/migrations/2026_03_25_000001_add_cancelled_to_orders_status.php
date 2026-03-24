<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE orders
            MODIFY status ENUM('Received', 'In Progress', 'Completed', 'Dispatched', 'Cancelled')
            NOT NULL DEFAULT 'Received'
        ");
    }

    public function down(): void
    {
        DB::statement("
            UPDATE orders
            SET status = 'Received'
            WHERE status = 'Cancelled'
        ");

        DB::statement("
            ALTER TABLE orders
            MODIFY status ENUM('Received', 'In Progress', 'Completed', 'Dispatched')
            NOT NULL DEFAULT 'Received'
        ");
    }
};
