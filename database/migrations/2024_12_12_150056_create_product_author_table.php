<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('author_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('role', ['author', 'translator'])->default('author');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_authors');
    }
};
