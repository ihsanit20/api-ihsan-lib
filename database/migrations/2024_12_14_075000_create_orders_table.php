<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['online', 'offline'])->default('offline');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 10, 0);
            $table->decimal('discount', 10, 0)->default(0);
            $table->decimal('payable_amount', 10, 0)->default(0);
            $table->decimal('total_paid', 10, 0)->default(0);
            $table->decimal('remaining_due', 10, 0);
            $table->enum('status', ['Pending', 'Completed', 'Cancelled'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};