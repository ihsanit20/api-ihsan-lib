<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCompanyInfosTable extends Migration
{
    public function up()
    {
        Schema::create('company_infos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('alt_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('fb_link')->nullable();
            $table->string('yt_link')->nullable();
            $table->string('x_link')->nullable();
            $table->string('in_link')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        DB::table('company_infos')->insert([
            'name' => 'Default Company',
            'address' => '123 Default Address',
            'phone' => '01700000000',
            'alt_phone' => '01800000000',
            'email' => 'info@default.com',
            'fb_link' => 'https://facebook.com',
            'yt_link' => 'https://youtube.com',
            'x_link' => 'https://x.com',
            'in_link' => 'https://linkedin.com',
            'logo' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('company_infos');
    }
}