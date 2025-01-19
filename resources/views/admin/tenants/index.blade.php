<x-guest>
    <div class="mx-auto my-8 max-w-7xl px-4">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded shadow">
                {!! session('success') !!}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded shadow">
                {!! session('error') !!}
            </div>
        @endif

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
                    <td class="p-2 border text-center">{{ ucfirst($tenant->status) }}</td>
                    <td class="p-2 border">
                        <div class="flex justify-center gap-4">
                            <!-- Edit Tenant -->
                            <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn-icon">
                                <i class="fad fa-edit"></i>
                            </a>
                            <!-- Delete Tenant -->
                            {{-- 
                            <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon">
                                    <i class="fad fa-trash text-red-500"></i>
                                </button>
                            </form>
                            --}}
                            <!-- Check Database -->
                            <a href="{{ route('admin.tenants.check', $tenant) }}" class="btn-icon text-green-500">
                                <i class="fad fa-database"></i>
                            </a>
                            <!-- Run Migration -->
                            <a href="{{ route('admin.tenants.migrate', $tenant) }}" class="btn-icon text-blue-500">
                                <i class="fad fa-play"></i>
                            </a>
                            <!-- Check Migration Status -->
                            <a href="{{ route('admin.tenants.migrationStatus', $tenant) }}" class="btn-icon text-yellow-500">
                                <i class="fad fa-info-circle"></i>
                            </a>
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
