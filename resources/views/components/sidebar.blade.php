<div x-data="{ open: true, currentRoute: window.location.pathname }" class="bg-white sidebar">
    <div class="space-y-1">
        <div x-on:click="open = !open" class="side-menu md:py-7">
            <button class="">
                <i class="fad fa-chevron-double-right" :class="{'rotate-180': open}"></i>
            </button>
            <h4 x-show="open" x-transition class="">Dashboard</h4>
        </div>
        <a href="/dashboard/course" class="side-menu" :class="{ 'side-menu-active': currentRoute.includes('/dashboard/course') }">
            <i class="fad fa-books py-2"></i>
            <template x-if="open">
                <h4>My Course</h4>
            </template>
        </a>
        <a href="/dashboard/today" class="side-menu" :class="{ 'side-menu-active': currentRoute.includes('/dashboard/today') }">
            <i class="fad fa-calendar-day py-2"></i>
            <h4 x-show="open" x-transition>Today's Schedule</h4>
        </a>
        <a href="/dashboard/profile" class="side-menu" :class="{ 'side-menu-active': currentRoute.includes('/dashboard/profile') }">
            <i class="fad fa-user-alt py-2"></i>
            <h4 x-show="open" x-transition>My Profile</h4>
        </a>
        <a href="/dashboard/complain" class="side-menu" :class="{ 'side-menu-active': currentRoute.includes('/dashboard/complain') }">
            <i class="fad fa-user-md-chat py-2"></i>
            <h4 x-show="open" x-transition>Complain Box</h4>
        </a>
        <a href="/dashboard/notice-list" class="side-menu" :class="{ 'side-menu-active': currentRoute.includes('/dashboard/notice') }">
            <i class="fad fa-bells py-2"></i>
            <h4 x-show="open" x-transition>Notice</h4>
        </a>
        <form method="POST" action="/logout">
            @method('POST')
            @csrf
            <button type="submit" class="side-menu bg-rose-50 w-full hover:bg-rose-600 text-rose-700">
                <i class="fad fa-sign-out-alt py-2"></i>
                <h4 x-show="open" x-transition>Logout</h4>
            </button>
        </form>
    </div>
</div>
