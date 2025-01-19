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
        'status',
        'host',
        'port',
        'username',
        'password',
    ];

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    public function scopeDomain($query, $domain)
    {
        return $query->where('domain', $domain);
    }
    
    public function scopeActive($query)
    {
        return $query->status('active');
    }
    
    public function scopeInactive($query)
    {
        return $query->status('inactive');
    }
    
    public function scopeActiveForDomain($query, $domain)
    {
        return $query->active()->domain($domain);
    }    
}
