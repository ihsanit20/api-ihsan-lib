    <div x-data="{ showNotice: false }" class="bg-white py-2 shadow">
        <div class="mx-auto flex justify-between px-4">
            <div class="w-16 md:w-24">
                <a href="/">
                    <x-application-logo />
                </a>
            </div>
            <ul class="flex gap-4 items-center">
                <button class="btn-icon" @click="showNotice = !showNotice">
                    {{-- Notice Icon --}}
                    <i class="fas fa-bell"></i>
                </button>

                <a href="/dashboard/profile" class="menus font-bold bg-gray-100 hover:bg-sky-100 p-1 rounded-full border">
                    <div class="min-w-max rounded-full object-cover overflow-hidden border">
                        <div class="flex rounded-full overflow-hidden h-full aspect-square max-h-8 bg-sky-600 items-center justify-center">
                            @if(true)
                                <img src="{{ asset('/img/demo-dr.jpg') }}" alt="User Photo" class="" />
                            @else
                                <i class="fa fa-user text-white p-2"></i>
                            @endif
                        </div>
                    </div>
                    <h4 class="hidden md:flex overflow-hidden">
                        <span class="line-clamp-1 break-all pr-2">User Name</span>
                    </h4>
                </a>
            </ul>

            <div x-show="showNotice" @click.away="showNotice = false" class="bg-sky-50 shadow-xl rounded-lg absolute md:right-10 md:top-24 top-16 w-80">
                {{-- This is notice list --}}
                <ul class="divide-y divide-gray-200">
                    <x-notice.notice-item 
                        notice_id="1" 
                        notice_title="Notice Title"
                        notice_details="Brief description of the notice goes here. This is a summary. Brief description of the notice goes here. This is a summary"
                        date="01-05-2024"
                        url="/dashboard/notice/noticeId/notice-show" />
                    <x-notice.notice-item 
                        notice_id="1" 
                        notice_title="Notice Title"
                        notice_details="Brief description of the notice goes here. This is a summary. Brief description of the notice goes here. This is a summary"
                        date="01-05-2024"
                        url="/dashboard/notice/noticeId/notice-show" />
                    <x-notice.notice-item 
                        notice_id="1" 
                        notice_title="Notice Title"
                        notice_details="Brief description of the notice goes here. This is a summary. Brief description of the notice goes here. This is a summary"
                        date="01-05-2024"
                        url="/dashboard/notice/noticeId/notice-show" />
                </ul>
            </div>
        </div>
    </div>