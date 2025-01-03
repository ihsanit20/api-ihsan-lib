<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('password')->nullable();
            $table->text('address')->nullable();
            $table->enum('role', ['customer', 'staff', 'admin', 'developer'])->default('customer');
            $table->enum('customer_type', ['regular', 'retailer', 'wholesale', 'distributor'])->default('regular');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('users');
    }
}
