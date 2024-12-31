<div class="relative">
    <div class="bg-white py-2 shadow">
        <div class="container mx-auto flex justify-between items-center px-4">

            <div class="w-16 md:w-24">
                <a href="/">
                    <x-application-logo />
                </a>
            </div>

            <div class="hidden md:flex justify-center items-center gap-4">
                <a href="/" class="hover:text-sky-600">Home</a>
                @auth('admin')
                    <a href="{{ route('admin.tenants.index') }}" class="hover:text-sky-600">Tenants</a>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-sky-600">Logout</button>
                    </form>
                @else
                    <a href="{{ route('admin.login') }}" class="hover:text-sky-600">Login</a>
                    <a href="{{ route('admin.register') }}" class="hover:text-sky-600">Register</a>
                @endauth
            </div>
        </div>
    </div>
</div>
