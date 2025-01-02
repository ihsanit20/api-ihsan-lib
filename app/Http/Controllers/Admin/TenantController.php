<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
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
            'database' => 'required|string|unique:tenants',
            'domain' => 'required|string|unique:tenants',
            'host' => 'required|string',
            'port' => 'required|string',
            'username' => 'required|string',
            'password' => '',
        ]);

        Tenant::create($request->all());

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant created successfully.');
    }

    public function checkDatabase(Tenant $tenant)
    {
        // return $tenant;

        try {
            config(['database.connections.tenant_check' => [
                'driver' => 'mysql',
                'host' => $tenant->host,
                'port' => $tenant->port,
                'database' => $tenant->database,
                'username' => $tenant->username,
                'password' => $tenant->password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]]);

            DB::connection('tenant_check')->getPdo();

            return redirect()->route('admin.tenants.index')->with('success', "Database '{$tenant->database}' exists and is accessible.");
        } catch (\Exception $e) {
            return redirect()->route('admin.tenants.index')->with('error', "Database '{$tenant->database}' does not exist or is not accessible. Error: {$e->getMessage()}");
        }
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
            'host' => 'required|string',
            'port' => 'required|string',
            'username' => 'required|string',
            'password' => '',
        ]);

        $data = $request->only(['name', 'database', 'domain', 'host', 'port', 'username']);

        $data['password'] = $request->password ?? '';

        // if ($request->filled('password')) {
        //     $data['password'] = bcrypt($request->password);
        // }

        $tenant->update($data);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return redirect()->route('admin.tenants.index')->with('success', 'Tenant deleted successfully.');
    }
}