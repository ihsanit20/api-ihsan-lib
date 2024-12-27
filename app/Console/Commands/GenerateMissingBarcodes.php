<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Str;

class GenerateMissingBarcodes extends Command
{
    protected $signature = 'generate:missing-barcodes';
    protected $description = 'Generate barcodes for products missing barcodes';

    public function handle()
    {
        $products = Product::whereNull('barcode')->get();

        foreach ($products as $product) {
            do {
                $barcode = 'P' . strtoupper(Str::random(8));
            } while (Product::where('barcode', $barcode)->exists());

            $product->barcode = $barcode;
            $product->save();

            $this->info("Generated barcode for Product ID {$product->id}");
        }

        $this->info('All missing barcodes have been generated.');
    }
}

