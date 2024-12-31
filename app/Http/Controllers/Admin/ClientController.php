<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;

class ClientController extends Controller
{

    public function index()
    {
        $clients = Tenant::all();
        return view('admin.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|unique:tenants',
            'database' => 'required|unique:tenants',
            'username' => 'required',
            'password' => 'required',
        ]);

        Tenant::create([
            'name' => $request->name,
            'domain' => $request->domain,
            'database' => $request->database,
            'host' => '127.0.0.1',
            'port' => '3306',
            'username' => $request->username,
            'password' => $request->password,
            'status' => 1,
        ]);

        return redirect()->route('admin.create')->with('success', 'Client created successfully.');
    }

    /**
     * Update the specified client in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|unique:tenants,domain,' . $tenant->id,
            'database' => 'required|unique:tenants,database,' . $tenant->id,
            'username' => 'required',
            'password' => 'required',
        ]);

        $tenant->update($request->all());

        return redirect()->route('admin.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified client from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete();

        return redirect()->route('admin.index')->with('success', 'Client deleted successfully.');
    }

    /**
     * Run migration for the specified client.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function migrate($id)
    {
        $tenant = Tenant::findOrFail($id);

        // ডাইনামিক ডাটাবেজ কানেকশন
        config(['database.connections.mysql.database' => $tenant->database]);
        \DB::purge('mysql');
        \DB::reconnect('mysql');

        try {
            \Artisan::call('migrate', [
                '--force' => true,
                '--path' => '/database/migrations/clients',
            ]);
            return redirect()->route('admin.index')->with('success', 'Migration successful for ' . $tenant->name);
        } catch (\Exception $e) {
            return redirect()->route('admin.index')->with('error', 'Migration failed: ' . $e->getMessage());
        }
    }
}