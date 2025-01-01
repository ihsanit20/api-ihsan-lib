<x-guest>
    <div class="mx-auto my-8 max-w-7xl px-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Tenants</h1>
            <a href="{{ route('admin.tenants.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Tenant</a>
        </div>
        <table class="w-full bg-white shadow rounded-md overflow-hidden border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">Name</th>
                    <th class="p-2 border">Database</th>
                    <th class="p-2 border">Domain</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tenants as $tenant)
                <tr>
                    <td class="p-2 border text-center">{{ $tenant->name }}</td>
                    <td class="p-2 border text-center">{{ $tenant->database }}</td>
                    <td class="p-2 border text-center">{{ $tenant->domain }}</td>
                    <td class="p-2 border text-center">{{ $tenant->status ? 'Active' : 'Inactive' }}</td>
                    <td class="p-2 border ">
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn-icon">
                                <i class="fad fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon">
                                    <i class="fad fa-trash text-red-500"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-3 border text-center text-gray-500">No tenant found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-guest>
