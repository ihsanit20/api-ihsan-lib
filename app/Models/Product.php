<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ISBN',
        'description',
        'photo',
    ];

    protected $with = ['categories', 'authors'];

    protected $appends = ['mrp', 'selling_price'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories')
                    ->withTimestamps();
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'product_authors')
                    ->withPivot('role') // Role (e.g., author/translator)
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


    // Accessors

    protected function photo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?? 'https://via.placeholder.com/300x400/0284c7?text=No+Photo',
        );
    }

    protected function mrp(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stocks()->orderBy('stock_date', 'desc')->first()?->mrp,
        );
    }

    protected function sellingPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stocks()->orderBy('stock_date', 'desc')->first()?->selling_price,
        );
    }
}