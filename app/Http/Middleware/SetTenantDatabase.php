<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class SetTenantDatabase
{
    public function handle(Request $request, Closure $next)
    {
        // return
        $domain = $this->getClientDomainFromRequest($request);

        $tenant = Tenant::where('domain', $domain)->first();

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
        $origin = $request->header('Origin'); // For CORS requests
        $referer = $request->header('Referer'); // Standard Referer header

        // Use the one that is available or fits your use case
        $domain = $origin ?? $referer;

        // Remove http://, https://, www., and trailing slashes
        $domain = rtrim(preg_replace('/^http(|s)\:\/\/(www\.|)|www./','', $domain ), '/');

        return $domain;
    }
}