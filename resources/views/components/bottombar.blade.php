
<div x-data="{ active: 'courses', show: false, currentRoute: window.location.pathname }" class="flex justify-between text-center gap-2 w-full items-center px-4 bg-white p-3 border shadow">
    <a href="/dashboard/course" class="flex items-center justify-center gap-2 p-3 px-4 bg-gray-100 rounded-lg cursor-pointer" :class="{'bg-sky-600 text-white': currentRoute.includes('/dashboard/course') }">
        <i class="fad fa-books text-xl"></i>
        <template x-if="currentRoute.includes('/dashboard/course')">
            <h4 class="text-sm font-semibold flex">Course</h4> 
        </template> 
    </a>
    <a href="/dashboard/today" class="flex items-center justify-center gap-2 p-3 px-4 bg-gray-100 rounded-lg cursor-pointer" :class="{'bg-sky-600 text-white': currentRoute.includes('/dashboard/today') }">
        <i class="fad fa-calendar-day text-xl"></i>
        
        <template x-if="currentRoute === '/dashboard/today'">
            <h4 class="text-sm font-semibold flex">Today's</h4> 
        </template>
    </a>
    <a href="/dashboard/profile" class="flex items-center justify-center gap-2 p-3 px-4 bg-gray-100 rounded-lg cursor-pointer" :class="{'bg-sky-600 text-white': currentRoute.includes('/dashboard/profile') }">
        <i class="fad fa-user-alt text-xl"></i>
        <template x-if="currentRoute === '/dashboard/profile'">
            <h4 class="text-sm font-semibold flex">Profile</h4> 
        </template>
    </a>
    <button x-on:click="show = ! show" class="flex items-center justify-center gap-2 p-3 px-4 bg-gray-100 hover:bg-sky-100 rounded-lg cursor-pointer">
        <i class="fad fa-bars text-xl"></i>
    </button>
    <template x-if="show">
        <div class="absolute bottom-[70px] right-0  rounded-lg p-4 bg-white shadow-xl transition-all ease-in-out duration-300">
            <x-layouts.sidebar/>
        </div>
    </template>
</div>