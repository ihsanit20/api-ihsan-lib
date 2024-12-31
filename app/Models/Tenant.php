<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'database',
        'domain',
        'host',
        'port',
        'username',
        'password',
        'status',
    ];

    public function getIsActiveAttribute()
    {
        return $this->status == 1;
    }
}