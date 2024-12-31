<div class="bg-white md:py-8 py-4 md:mt-10 mt-6">
    <div class="container mx-auto md:grid md:grid-cols-6 gap-4 divide-y-2 md:divide-y-0 px-4">
        <div class="col-span-2 flex flex-col items-center md:items-start text-center md:text-left gap-2 pb-2">
            <a href="/" class="pb-2 w-24 md:w-28">
                <x-application-logo />
            </a>
            <div>
                <i class="far fa-map-marker-alt"></i>
                <b>Head Office:</b>
                <p>185 Sonargaon Road, Near Hatirpool Kacha Bazar (Chiana Kitchen), Dhaka-1205 </p>
            </div>
            
            <div class="flex justify-center md:justify-start gap-4 text-xl">
                <a href="https://wa.me/+8801958021101" target="_blank" class="hover:text-sky-500 text-green-500"><i class="fab fa-whatsapp"></i></a>
                <a href="https://www.facebook.com/messages/t/105224544832138" target="_blank" class="hover:text-sky-500 text-purple-500"><i class="fab fa-facebook-messenger" target="_blank" class="hover:text-sky-500"></i></a>
                <a href="https://www.facebook.com/Neuronpg.A/" target="_blank" class="hover:text-sky-500 text-blue-500"><i class="fab fa-facebook" target="_blank"></i></a>
                <a href="https://www.youtube.com/@neuronacademy7160" target="_blank" class="hover:text-sky-500 text-red-600"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
        <div class="col-span-1 flex flex-col items-center md:items-start pb-2 space-y-1">
            <p class="py-2">
                <b>Company</b>
            </p>
            <a href="{{ url('/') }}" class="hover:text-sky-500">Home</a>
            <a href="{{ route('about') }}" class="hover:text-sky-500">About Us</a>
            <a href="{{ route('pages.contact') }}" class="hover:text-sky-500">Contact Us</a>
            <a href="{{ url('/terms-of-use') }}" class="hover:text-sky-500">Terms of Use</a>
            <a href="{{ url('/refund-policy') }}" class="hover:text-sky-500">Refund Policy</a>
            <a href="{{ route('pages.faqs') }}" class="hover:text-sky-500">FAQs</a>
        </div>
        <div class="col-span-1 flex flex-col items-center md:items-start pb-2 space-y-1">
            <p class="py-2">
                <b>Our Services</b>
            </p>
            <a href="{{ url('/') }}" class="hover:text-sky-500">FCPS P-I Surgery</a>
            <a href="{{ route('about') }}" class="hover:text-sky-500">FCPS P-I Medicine</a>
            <a href="{{ route('pages.contact') }}" class="hover:text-sky-500">FCPS P-I Paediatric</a>
            <a href="{{ url('login') }}" class="hover:text-sky-500">FCPS P-I Obs & Gynae</a>
            <a href="{{ url('login') }}" class="hover:text-sky-500">Residency</a>
            <a href="{{ url('login') }}" class="hover:text-sky-500">M.phil Diploma</a>
        </div>
        
        <div class="col-span-2 flex flex-col items-center md:items-start">
            <b class="py-2 pb-4">Join Us</b>
            <form action="{{ route('join') }}" method="POST" class="flex items-center gap-2 pb-2 w-full">
                @csrf
                <input id="phone" class="w-full input-1" type="number" name="phone" :value="old('phone')" required placeholder="Enter Phone" />
                <button class="btn-2 w-40" @disabled(auth()->check())>{{ __('Join Now') }} </button>
            </form>
            <div class="space-y-2 pt-2">
                <div class="flex items-center gap-2">
                    <i class="far fa-envelope"></i>
                    <a href="mailto:neuronoffice.bd@gmail.com" target="_blank" class="hover:text-sky-500">neuronoffice.bd@gmail.com</a>
                </div>
                <div class="flex items-center gap-2">
                    <i class="far fa-phone"></i>
                    <p>
                        <a href="tel:+8801958021101" target="_blank" class="hover:text-sky-500">+8801958021101</a>
                        <a href="tel:+8801958021102" target="_blank" class="hover:text-sky-500">+8801958021102</a>
                    </p>
                    
                </div>
            </div>
            
        </div>
    </div>
</div>
