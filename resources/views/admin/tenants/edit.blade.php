<x-guest>
    <div class="mx-auto my-8 max-w-4xl px-4">
        <h1 class="text-2xl font-bold mb-4">Edit Tenant</h1>
        <form action="{{ route('admin.tenants.update', $tenant) }}" method="POST" class="bg-white p-6 rounded shadow-md">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" name="name" id="name" class="w-full border-gray-300 rounded p-2" value="{{ $tenant->name }}" required>
            </div>
            <div class="mb-4">
                <label for="database" class="block text-gray-700">Database</label>
                <input type="text" name="database" id="database" class="w-full border-gray-300 rounded p-2" value="{{ $tenant->database }}" required>
            </div>
            <div class="mb-4">
                <label for="domain" class="block text-gray-700">Domain</label>
                <input type="text" name="domain" id="domain" class="w-full border-gray-300 rounded p-2" value="{{ $tenant->domain }}" required>
            </div>
            <div class="mb-4">
                <label for="host" class="block text-gray-700">Host</label>
                <input type="text" name="host" id="host" class="w-full border-gray-300 rounded p-2" value="{{ $tenant->host }}">
            </div>
            <div class="mb-4">
                <label for="port" class="block text-gray-700">Port</label>
                <input type="text" name="port" id="port" class="w-full border-gray-300 rounded p-2" value="{{ $tenant->port }}">
            </div>
            <div class="mb-4">
                <label for="username" class="block text-gray-700">Username</label>
                <input type="text" name="username" id="username" class="w-full border-gray-300 rounded p-2" value="{{ $tenant->username }}" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" name="password" id="password" class="w-full border-gray-300 rounded p-2">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</x-guest>
