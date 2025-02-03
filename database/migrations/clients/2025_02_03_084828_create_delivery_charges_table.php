<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_charges', function (Blueprint $table) {
            $table->id();
            $table->decimal('inside_city', 8, 0)->default(50);
            $table->decimal('outside_city', 8, 0)->default(100);
            $table->timestamps();
        });

        DB::table('delivery_charges')->insert([
            'inside_city' => 50,
            'outside_city' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_charges');
    }
};
