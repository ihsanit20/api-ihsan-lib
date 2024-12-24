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
            $table->decimal('discount_percentage', 5, 0)->default(0);
            $table->decimal('discount_amount', 10, 0)->default(0);
            $table->decimal('adjust_amount', 10, 0)->default(0);
            $table->decimal('payable_amount', 10, 0)->default(0);
            $table->decimal('paid_amount', 10, 0)->default(0);
            $table->decimal('due_amount', 10, 0);
            $table->enum('status', ['Pending', 'Completed', 'Cancelled'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
