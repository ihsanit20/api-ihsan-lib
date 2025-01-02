<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|unique:tenants',
            'status' => 'required|in:active,inactive',
        ]);

        $databaseName = "{$request->name}";

        if (Tenant::where('database', $databaseName)->exists()) {
            return redirect()->back()->with('error', 'Database name already exists. Please use a different name.');
        }

        Tenant::create([
            'name' => $request->name,
            'database' => $databaseName,
            'domain' => $request->domain,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant created successfully.');
    }

    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'database' => 'required|string|unique:tenants,database,' . $tenant->id,
            'domain' => 'required|string|unique:tenants,domain,' . $tenant->id,
            'status' => 'required|in:active,inactive',
        ]);

        $tenant->update([
            'name' => $request->name,
            'database' => $request->database,
            'domain' => $request->domain,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return redirect()->route('admin.tenants.index')->with('success', 'Tenant deleted successfully.');
    }

    public function checkDatabase(Tenant $tenant)
    {
        try {
            config(['database.connections.tenant_check' => [
                'driver'    => 'mysql',
                'host'      => env('DB_HOST', '127.0.0.1'),
                'port'      => env('DB_PORT', '3306'),
                'username'  => env('DB_USERNAME'),
                'password'  => env('DB_PASSWORD'),
                'database'  => $tenant->database,
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix'    => '',
                'strict'    => true,
            ]]);

            DB::connection('tenant_check')->getPdo();

            return redirect()->route('admin.tenants.index')->with('success', "Database '{$tenant->database}' exists and is accessible.");
        } catch (\Exception $e) {
            return redirect()->route('admin.tenants.index')->with('error', "Database '{$tenant->database}' does not exist or is not accessible. Error: {$e->getMessage()}");
        }
    }

    public function runMigration(Tenant $tenant)
    {
        try {
            config(['database.connections.tenant_check' => [
                'driver'    => 'mysql',
                'host'      => env('DB_HOST', '127.0.0.1'),
                'port'      => env('DB_PORT', '3306'),
                'username'  => env('DB_USERNAME'),
                'password'  => env('DB_PASSWORD'),
                'database'  => $tenant->database,
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix'    => '',
                'strict'    => true,
            ]]);

            Artisan::call('migrate', [
                '--path' => 'database/migrations/clients',
                '--database' => 'tenant',
                '--force' => true,
            ]);

            return redirect()->route('admin.tenants.index')->with('success', "Migration ran successfully for tenant '{$tenant->name}'.");
        } catch (\Exception $e) {
            return redirect()->route('admin.tenants.index')->with('error', "Failed to run migration for tenant '{$tenant->name}'. Error: {$e->getMessage()}");
        }
    }

    public function checkMigrationStatus(Tenant $tenant)
    {
        try {
            config(['database.connections.tenant' => [
                'driver'    => 'mysql',
                'host'      => env('DB_HOST', '127.0.0.1'),
                'port'      => env('DB_PORT', '3306'),
                'database'  => $tenant->database,
                'username'  => env('DB_USERNAME', 'forge'),
                'password'  => env('DB_PASSWORD', ''),
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix'    => '',
                'strict'    => true,
            ]]);

            Artisan::call('migrate:status', [
                '--database' => 'tenant', // Tenant connection name
                '--path' => 'database/migrations/clients',
            ]);

            $migrationStatus = Artisan::output();

            return redirect()->route('admin.tenants.index')->with('success', nl2br($migrationStatus));
        } catch (\Exception $e) {
            return redirect()->route('admin.tenants.index')->with('error', "Failed to check migration status for tenant '{$tenant->name}'. Error: {$e->getMessage()}");
        }
    }

}
