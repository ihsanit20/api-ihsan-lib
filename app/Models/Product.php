<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Milon\Barcode\DNS1D;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mrp',
        'selling_price',
        'ISBN',
        'description',
        'photo',
        'barcode',
    ];

    protected $casts = [
        'mrp' => 'int',
        'selling_price' => 'int',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
                    ->withTimestamps();
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'product_authors')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }

    protected function photo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?: 'https://placehold.co/300x400/0284c7/fff?text=No+Photo',
        );
    }

    protected function availableStock(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stocks()->sum('quantity') - $this->orderDetails()->sum('quantity'),
        );
    }

    protected static function booted()
    {
        static::creating(function ($product) {
            do {
                $barcode = 'P' . strtoupper(Str::random(8));
            } while (Product::where('barcode', $barcode)->exists());

            $product->barcode = $barcode;
        });
    }

    protected function barcodeImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->barcode ?
                (new DNS1D())->getBarcodePNG($this->barcode, 'C128', 2, 50) : null
        );
    }

}