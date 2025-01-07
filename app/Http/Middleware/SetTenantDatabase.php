<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Tenant;

class SetTenantDatabase
{
    public function handle(Request $request, Closure $next)
    {
        $domain = $this->getClientDomainFromRequest($request);

        $tenant = Cache::remember("tenant_{$domain}", now()->addMinutes(10), function () use ($domain) {
            return Tenant::where('domain', $domain)->first();
        });

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        config([
            'database.connections.tenant' => [
                'driver'    => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'username' => env('DB_USERNAME', 'forge'),
                'password' => env('DB_PASSWORD', ''),
                'database'  => $tenant->database,
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix'    => '',
                'strict'    => true,
            ],
        ]);

        DB::setDefaultConnection('tenant');

        return $next($request);
    }

    protected function getClientDomainFromRequest($request)
    {
        $origin = $request->header('Origin');
        $referer = $request->header('Referer');
        $domain = $origin ?? $referer;

        return rtrim(preg_replace('/^http(|s):\/\/(www\.|)|www\./', '', $domain), '/');
    }
}