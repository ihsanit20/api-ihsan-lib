<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'photo',
        'is_own',
    ];

    protected $casts = [
        'is_own' => 'bool',
    ];

    /**
     * Get the products for the publisher.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope a query to only include own publishers.
     */
    public function scopeOwn($query)
    {
        return $query->where('is_own', true);
    }

    /**
     * Scope a query to only include other publishers.
     */
    public function scopeOthers($query)
    {
        return $query->where('is_own', false);
    }
}
