<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('task');
            $table->unsignedBigInteger('amount'); // stored as whole IQD (no decimals)
            $table->enum('status', ['pending', 'processing', 'completed'])->default('pending');
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
