<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('mrp', 10, 0);
            $table->decimal('selling_price', 10, 0);
            $table->string('ISBN')->nullable();
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->string('barcode')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
