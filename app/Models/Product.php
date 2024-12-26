<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected $with = ['categories', 'authors'];

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
            get: fn ($value) => $value ?? 'https://via.placeholder.com/300x400/0284c7?text=No+Photo',
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
}
