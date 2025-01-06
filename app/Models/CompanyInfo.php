<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'phone',
        'alt_phone',
        'email',
        'fb_link',
        'yt_link',
        'x_link',
        'in_link',
        'logo',
    ];
}