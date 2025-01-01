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
        $domain = $request->getHost();
        $tenant = Tenant::where('domain', $domain)->first();

        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        config([
            'database.connections.tenant' => [
                'driver'    => 'mysql',
                'host'      => $tenant->host,
                'port'      => $tenant->port,
                'database'  => $tenant->database,
                'username'  => $tenant->username,
                'password'  => $tenant->password ?? '',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix'    => '',
                'strict'    => true,
            ],
        ]);

        DB::setDefaultConnection('tenant');

        return $next($request);
    }
}