<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Optional link to an existing user account for login / punch in-out.
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('employee_code')->nullable()->unique();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('designation')->nullable();
            $table->date('joining_date')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        Schema::create('employee_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_employee_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('employee_role_id')->constrained('employee_roles')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['employee_id', 'employee_role_id']);
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date');
            $table->dateTime('punch_in')->nullable();
            $table->dateTime('punch_out')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('employee_employee_role');
        Schema::dropIfExists('employee_roles');
        Schema::dropIfExists('employees');
    }
};
