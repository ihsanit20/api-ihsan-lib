<div class="relative">
    <div class="bg-white py-2 shadow">
        <div class="container mx-auto flex justify-between items-center px-4">
            <div class="w-16 md:w-24">
                <a href="/">
                    <x-application-logo />
                </a>
            </div>
                
            <div class="hidden md:flex justify-center items-center gap-4">
                <a href="{{ url('/') }}" class="hover:text-sky-600">Home</a>
                <a href="{{ route('courses') }}" class="hover:text-sky-600">Courses</a>
                <a href="{{ route('about') }}" class="hover:text-sky-600">About</a>
                <a href="{{ route('pages.contact') }}" class="hover:text-sky-600">Contact</a>
                <a href="{{ route('pages.faqs') }}" class="hover:text-sky-600">FAQs</a>

                @auth
                <a href="{{ url('dashboard/course') }}" class="btn-2">Dashboard</a>
                @else
                <a href="{{ url('join') }}" class="btn-2">Join Now</a>
                @endauth
            </div>
            <div class="md:hidden flex gap-2">
                @auth
                <a href="{{ url('dashboard/course') }}" class="btn-2 w-full">Dashboard</a>
                @else
                <a href="{{ url('join') }}" class="btn-2 w-full">Join Now</a>
                @endauth
            <div x-data="{ open: false }" class="">
                <button  @click="open = true" class="border rounded px-3 pt-2 pb-1">
                    <i class="fas fa-bars text-lg"></i>
                </button>
            
                <div x-show="open" @click.outside="open = false" x-transition class="md:hidden right-0 top-16 has-dropdown w-72 p-4" >
                        <a href="{{ url('/') }}" class="side-menu">Home</a>
                        <a href="{{ route('courses') }}" class="side-menu">Courses</a>
                        <a href="{{ route('about') }}" class="side-menu">About</a>
                        <a href="{{ route('pages.contact') }}" class="side-menu">Contact</a>
                        <a href="{{ route('pages.faqs') }}" class="side-menu">FAQs</a>
                        @auth
                        <a href="{{ url('dashboard/course') }}" class="btn-2 w-full">Dashboard</a>
                        @else
                        <a href="{{ url('join') }}" class="btn-2 w-full">Join Now</a>
                        @endauth
                </div>
            </div>
            </div>
        </div>
    </div>

</div>
