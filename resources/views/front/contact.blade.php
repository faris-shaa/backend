@extends('front.master', ['activePage' => 'contact'])
@section('title', __('Contact Us'))
@section('content')
@php
$social = \App\Models\Setting::find(1);
$logo = \App\Models\Setting::find(1)->logo;
$admin = \App\Models\User::find(1);
@endphp


<div class="container mt-12 md:mt-32">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-4 ">
        <div class="p-1 md:p-3 xl:p-4 bg-light bg-opacity-5  border border-primary_color_o10_1 flex flex-col items-center justify-center rounded-2xl md:rounded-3xl">
            <div class="w-24 h-24 rounded-full flex items-center justify-center bg-gradient-circle border border-primary_color_o10_1">
                <svg width="36" height="48" viewBox="0 0 36 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_486_315)">
                        <path d="M18 0C8.05959 0 0 8.05959 0 18C0 25.2572 2.52834 27.2845 16.1513 47.0321C16.5982 47.6774 17.299 48 18 48C18.701 48 19.4018 47.6774 19.8487 47.0321C33.4717 27.2845 36 25.2572 36 18C36 8.05959 27.9404 0 18 0ZM18 44.4298C4.86863 25.4418 3 23.9971 3 18C3 9.72909 9.72891 3 18 3C26.2711 3 33 9.72909 33 18C33 23.9887 31.1781 25.3757 18 44.4298ZM18 10.5C13.8579 10.5 10.5 13.8577 10.5 18C10.5 22.1423 13.8579 25.5 18 25.5C22.1423 25.5 25.5 22.1423 25.5 18C25.5 13.8577 22.1423 10.5 18 10.5ZM18 22.5C15.5187 22.5 13.5 20.4813 13.5 18C13.5 15.5187 15.5187 13.5 18 13.5C20.4813 13.5 22.5 15.5187 22.5 18C22.5 20.4813 20.4813 22.5 18 22.5Z" fill="#C4ACD3" />
                    </g>
                    <defs>
                        <clipPath id="clip0_486_315">
                            <rect width="36" height="48" fill="white" />
                        </clipPath>
                    </defs>
                </svg>
            </div>
            <div class="font-medium mt-2 mb-1 text-h5 md:text-h3"> {{__('Address')}}</div>
            <p class="text-gray_b5 text-h6 md:text-h5 text-center "> {{__('Northern Ring Rd, Al Wadi, Riyadh')}}</p>
        </div>
        <div class="p-1 md:p-3 xl:p-4 bg-light bg-opacity-5  border border-primary_color_o10_1 flex flex-col items-center justify-center rounded-2xl md:rounded-3xl">
            <div class="w-24 h-24 rounded-full flex items-center justify-center bg-gradient-circle border border-primary_color_o10_1">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M45.4339 30.996C45.4325 30.996 45.431 30.996 45.4295 30.9931L35.8904 26.9018C34.156 26.1811 32.1213 26.6791 30.945 28.1278L27.838 31.9218C22.8927 29.2294 18.7808 25.1176 16.0899 20.1737L19.8883 17.0623C21.3576 15.851 21.8409 13.8602 21.0924 12.1082L17.004 2.56622C16.1866 0.682409 14.1344 -0.350341 12.1465 0.10819L3.29738 2.16188C1.35647 2.60428 0 4.3035 0 6.29278C0 29.291 18.7091 48 41.7073 48C43.698 48 45.4016 46.6464 45.8484 44.707L47.8919 35.8506C48.3504 33.8525 47.3162 31.8105 45.4339 30.996ZM42.9246 44.0332C42.7957 44.5928 42.283 45 41.7073 45C20.3644 45 3 27.6356 3 6.29278C3 5.71266 3.39703 5.21616 3.96975 5.08575L12.8204 3.03206C12.9142 3.01003 13.0079 2.99981 13.1002 2.99981C13.5909 2.99981 14.048 3.28988 14.2486 3.75272L18.3341 13.2875C18.5553 13.8045 18.4117 14.3904 17.9826 14.745L13.2481 18.6239C12.709 19.0663 12.545 19.8207 12.8527 20.4461C15.9816 26.8036 21.2067 32.0273 27.5656 35.1562C28.1882 35.4668 28.947 35.2998 29.3879 34.7607L33.2683 30.0263C33.6155 29.5986 34.2204 29.4579 34.7214 29.666L44.2445 33.7499C44.7982 33.9902 45.1028 34.5908 44.968 35.1767L42.9246 44.0332Z" fill="#C4ACD3" />
                </svg>
            </div>
            <div class="font-medium mt-2 mb-1 text-h5 md:text-h3"> {{__('Phone')}}</div>
            <p class="text-gray_b5 text-h6 md:text-h5 text-center ">556046094</p>
        </div>
        <div class="p-1 md:p-3 xl:p-4 bg-light bg-opacity-5  border border-primary_color_o10_1 flex flex-col items-center justify-center rounded-2xl md:rounded-3xl">
            <div class="w-24 h-24 rounded-full flex items-center justify-center bg-gradient-circle border border-primary_color_o10_1">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M42 6H6C2.68631 6 0 8.68613 0 12V36C0 39.3137 2.68631 42 6 42H42C45.3139 42 48 39.3137 48 36V12C48 8.68613 45.3139 6 42 6ZM6 9H42C43.6542 9 45 10.3458 45 12V15.3757L26.7011 29.1006C25.1074 30.293 22.8926 30.293 21.2989 29.1006L3 15.3757V12C3 10.3458 4.34578 9 6 9ZM45 36C45 37.6542 43.6542 39 42 39H6C4.34578 39 3 37.6542 3 36V19.1244L19.5 31.5C20.8242 32.4946 22.4121 32.9912 24 32.9912C25.5879 32.9912 27.1758 32.4946 28.5 31.5L45 19.1244V36Z" fill="#C4ACD3" />
                </svg>
            </div>
            <div class="font-medium mt-2 mb-1 text-h5 md:text-h3"> {{__('Email')}}</div>
            <p class="text-gray_b5 text-h6 md:text-h5 text-center ">info@ticketby.co</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-4 mt-4 xl:mt-20 shadow-dark">
        <div class=" bg-light bg-opacity-5 rounded-2xl border border-primary_color_o10_1 p-2 md:p-4">
            <div class="pb-4 border-b border-gray_f">
                <div class=" text-h4 md:text-h2 "> {{ __('Get in touch with us') }}</div>
                <p class="mt-1 h4 text-gray_9"> {{ __('Send us a message') }}</p>
            </div>
            <form action="{{ url('/send-to-admin') }}" method="post" class="mt-4 php-email-form">
                @csrf
                <div class="">
                    <div class="mb-4">
                        <label for="firstname" class="mb-1 block text-h6 md:text-h5">{{ __('First Name') }}</label>
                        <input type="text" name="name" required class="bg-dark_1 p-1 md:p-16-16 w-full focus:border-primary_color_6 outline-0 rounded-lg border border-primary_color_o10_1  md:text-h5 text-h6" placeholder="{{ __('Your Name') }}">
                    </div>
                    <div class="mb-4">
                        <label for="lastname" class="mb-1 block text-h6 md:text-h5">{{ __('Your Email') }}</label>
                        <input type="text" name="email" required class="bg-dark_1 p-1 md:p-16-16 w-full focus:border-primary_color_6 outline-0 rounded-lg border border-primary_color_o10_1  md:text-h5 text-h6" placeholder="{{ __('Your Email') }}">
                    </div>
                    <div class="mb-4">
                        <div class="">
                            <label for="subject" class="mb-1 block text-h6 md:text-h5">{{ __('Subject') }}</label>
                            <input type="text" required name="subject" class="bg-dark_1 p-1 md:p-16-16 w-full focus:border-primary_color_6 outline-0 rounded-lg border border-primary_color_o10_1  md:text-h5 text-h6" placeholder="{{ __('Subject Message') }}">
                        </div>
                    </div>
                    <div class="mb-4">
                        <textarea id="message" rows="4" name="msg" class="bg-dark_1 p-1 md:p-16-16 w-full focus:border-primary_color_6 outline-0 rounded-lg border border-primary_color_o10_1  md:text-h5 text-h6" required placeholder="{{ __('Describe your message...') }}"></textarea>
                    </div>
                    <div class=" ">
                        <button class="bg-primary_color_8 text-white  px-5 py-2 rounded-6xl w-full">{{ __('Send Message') }}</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="p-1 md:p-3 xl:p-4 bg-light bg-opacity-5  border border-primary_color_o10_1 flex flex-col items-center justify-center rounded-2xl md:rounded-3xl">
            <img class="w-full rounded-2xl md:rounded-3xl " src="{{asset('frontAssets/images/contactmap.jpg')}}" alt="">
        </div>
    </div>
</div>


<script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: {
                    {
                        $data - > lat ?? ''
                    }
                },
                lng: {
                    {
                        $data - > long ?? ''
                    }
                }
            },
            zoom: 13
        });
        let marker = new google.maps.Marker({
            position: {
                lat: {
                    {
                        $data - > lat ?? ''
                    }
                },
                lng: {
                    {
                        $data - > long ?? ''
                    }
                }
            },
            map: map
        });
    }
</script>

@php
$gmapkey = \App\Models\Setting::find(1)->map_key;
@endphp
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $gmapkey }}&loading=async&callback=initMap"></script>
@endsection