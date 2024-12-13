<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'photo',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_authors')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    protected function photo(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?? ('https://ui-avatars.com/api/?name=' . str_replace(' ', '+', $this->name)),
        );
    }
}
