<x-guest>
    <div class="mx-auto my-8 max-w-7xl px-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Tenants</h1>
            <a href="{{ route('admin.tenants.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Tenant</a>
        </div>
        <table class="w-full bg-white shadow-md rounded border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 border">Name</th>
                    <th class="p-3 border">Database</th>
                    <th class="p-3 border">Domain</th>
                    <th class="p-3 border">Status</th>
                    <th class="p-3 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tenants as $tenant)
                <tr>
                    <td class="p-3 border">{{ $tenant->name }}</td>
                    <td class="p-3 border">{{ $tenant->database }}</td>
                    <td class="p-3 border">{{ $tenant->domain }}</td>
                    <td class="p-3 border">{{ $tenant->status ? 'Active' : 'Inactive' }}</td>
                    <td class="p-3 border flex space-x-2">
                        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</a>
                        <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-guest>
