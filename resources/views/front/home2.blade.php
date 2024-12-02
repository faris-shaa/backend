@extends('front.master', ['activePage' => 'home'])
@section('title', __('Home'))
@section('content')

@php
use Illuminate\Support\Facades\Http;
$lang = session('direction') == 'rtl' ? 'ar' : 'en';


$citys = null;
try {
// Use environment variable for the base URL
$baseUrl = env('API_BASE_URL', 'https://ticketby.pixicard.com');
$response = Http::withOptions(['verify' => false])->get("{$baseUrl}/api/city");

if ($response->successful()) {
$citys = $response->json();
} else {
$error = $response->json();
}
} catch (\Exception $e) {
// Handle the exception
// Log the exception message or display an error message
}

$categorys = null;
try {
// Use environment variable for the base URL
$baseUrl = env('API_BASE_URL', 'https://ticketby.pixicard.com');
$response = Http::withOptions(['verify' => false])->get("{$baseUrl}/api/user/category");

if ($response->successful()) {
$categorys = $response->json();
} else {
$error = $response->json();
}
} catch (\Exception $e) {
// Handle the exception
// Log the exception message or display an error message
}

@endphp
<div class="lg:bg-primary_color_15 hero rounded-b-3xl">
   <div class="container mt-16 pb-0 lg:pb-32 overflow-hidden">
      <div class="grid grid-cols-1 lg:grid-cols-2 items-center">
         <div class=" tiket-info order-2 lg:order-1 mt-7 md:mt-0">
            @foreach ( $banner as $item )
            <div class="lg:w-w-400 xl:w-w-500 hidden " data-tiket-id="{{ $item['id']}}">
               <div class="lg:min-h-40">
                  <div class="overflow-hidden">
                     <h1 class="text-h4 lg:text-h2  xl:text-h9 font-medium anim-tiket-h text-center lg:text-left">{{ $lang == 'ar' ? $item->event->name_arabic : $item->event->name }}</h1>
                  </div>
                  <div class="overflow-hidden text-center lg:text-left">
                     <p class="mt-1 lg:mt-4 text-primary_color_4 anim-tiket-p text-h18 lg:text-h5 ">{{ $lang == 'ar' ? $item->event->name_arabic : $item->event->name }}</p>
                  </div>
               </div>
               <div class="hidden md:flex gap-4 mt-7 overflow-hidden relative z-10">
                  <a href="{{ url('event/' . $item->event->id . '/' . Str::slug($item->event->name) . '?scroll=tickets_section') }}"
                     class="rounded-5xl bg-primary_color_8 text-center py-2 px-4 lg:px-12 w-full lg:w-48 f-bri l leading-5 block">
                     {{ __('Get Ticket') }}
                  </a> <a href="{{ url('event/' . $item->event->id . '/' . Str::slug($item->event->name)) }}"
                     class=" rounded-5xl border border-primary_color_8   text-center    py-2 px-4 lg:px-12  w-full lg:w-48 f-bri l leading-5  block">
                     {{__('Learn More')}}
                  </a>
               </div>
            </div>
            @endforeach
         </div>
         <div class="swiper-hero order-1 lg:order-2 m-auto ">
            <div class="swiper-wrapper">
               @foreach ( $banner as $item )
               <div class="swiper-slide" id="{{$item['id']}}"
                  style="background-image:url('{{ asset('/images/upload/' . $item->image) }}')">
                  <a class="block w-full h-full" href="{{ url('event/' . $item->event->id . '/' . Str::slug($item->event->name)) }}"></a>
               </div>
               @endforeach
            </div>
         </div>
      </div>
   </div>
</div>

<div class="container hidden lg:block">
   <div class="fs-select bg-primary_color_10 py-7 px-2 md:px-8 xl:px-24 rounded-2xl -mt-20 flex items-center gap-6 justify-between flex-wrap xl:flex-nowrap">
      <div class="w-full sm:w-auto">
         <span class="flex items-center gap-1 mb-1">
            <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
               <path
                  d="M15.8438 15.1562C16.0312 15.3438 16.0312 15.6875 15.8438 15.875C15.75 15.9688 15.625 16 15.5 16C15.3438 16 15.2188 15.9688 15.0938 15.875L10.6875 11.4375C9.53125 12.4375 8.0625 13 6.46875 13C2.90625 13 0 10.0938 0 6.5C0 2.9375 2.875 0 6.46875 0C10.0312 0 12.9688 2.9375 12.9688 6.5C12.9688 8.125 12.4062 9.59375 11.4062 10.75L15.8438 15.1562ZM6.5 12C9.53125 12 12 9.5625 12 6.5C12 3.46875 9.53125 1 6.5 1C3.4375 1 1 3.46875 1 6.5C1 9.53125 3.4375 12 6.5 12Z"
                  fill="#EEE8F4" />
            </svg>
            <label for="">{{__('Event title')}}</label>
         </span>
         <div class="relative">
            <div class="ms-auto hidden absolute @if($lang == 'ar') left-0 @else right-0 @endif  bottom-1/2 transform translate-y-1/2 " id="Searchbtn">
               <svg class="@if($lang == 'ar') rotate-180 @endif" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M8.25 1.21875L13.75 6.46875C13.9062 6.625 14 6.8125 14 7.03125C14 7.21875 13.9062 7.40625 13.75 7.5625L8.25 12.8125C7.96875 13.0938 7.46875 13.0938 7.1875 12.7812C6.90625 12.5 6.90625 12 7.21875 11.7188L11.375 7.78125H0.75C0.3125 7.78125 0 7.4375 0 7.03125C0 6.59375 0.3125 6.28125 0.75 6.28125H11.375L7.21875 2.3125C6.90625 2.03125 6.90625 1.53125 7.1875 1.25C7.46875 0.9375 7.9375 0.9375 8.25 1.21875Z" fill="#A986BF" />
               </svg>
            </div>
            <div class="ms-auto hidden absolute @if($lang == 'ar') left-0 @else right-0 @endif  bottom-1/2 transform translate-y-1/2 " id="clear_search">
               <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M8 16C3.5625 16 0 12.4375 0 8C0 3.59375 3.5625 0 8 0C12.4062 0 16 3.59375 16 8C16 12.4375 12.4062 16 8 16ZM5.46875 5.46875C5.15625 5.78125 5.15625 6.25 5.46875 6.53125L6.9375 8L5.46875 9.46875C5.15625 9.78125 5.15625 10.25 5.46875 10.5312C5.75 10.8438 6.21875 10.8438 6.5 10.5312L7.96875 9.0625L9.4375 10.5312C9.75 10.8438 10.2188 10.8438 10.5 10.5312C10.8125 10.25 10.8125 9.78125 10.5 9.46875L9.03125 8L10.5 6.53125C10.8125 6.25 10.8125 5.78125 10.5 5.46875C10.2188 5.1875 9.75 5.1875 9.4375 5.46875L7.96875 6.9375L6.5 5.46875C6.21875 5.1875 5.75 5.1875 5.46875 5.46875Z" fill="#999999" />
               </svg>
            </div>
            <input type="text" name="" id="SearchEventName" value="" placeholder="{{__('Search by event name')}}"
               class="text-h4 placeholder-primary_color_6 w-full min-w-60 f-bri bg-transparent text-primary_color_6 border-0 border-b border-primary_color_6 py-3 border   outline-0">
         </div>
      </div>
      <div class="w-full sm:w-auto">
         <span class="flex items-center gap-1 mb-1">
            <svg width="13" height="17" viewBox="0 0 13 17" fill="none" xmlns="http://www.w3.org/2000/svg">
               <path d="M8.83325 6C8.83325 7.40625 7.70825 8.5 6.33325 8.5C4.927 8.5 3.83325 7.40625 3.83325 6C3.83325 4.625 4.927 3.5 6.33325 3.5C7.70825 3.5 8.83325 4.625 8.83325 6ZM6.33325 7.5C7.14575 7.5 7.83325 6.84375 7.83325 6C7.83325 5.1875 7.14575 4.5 6.33325 4.5C5.4895 4.5 4.83325 5.1875 4.83325 6C4.83325 6.84375 5.4895 7.5 6.33325 7.5ZM12.3333 6C12.3333 8.75 8.677 13.5938 7.052 15.625C6.677 16.0938 5.95825 16.0938 5.58325 15.625C3.95825 13.5938 0.333252 8.75 0.333252 6C0.333252 2.6875 2.9895 0 6.33325 0C9.64575 0 12.3333 2.6875 12.3333 6ZM6.33325 1C3.552 1 1.33325 3.25 1.33325 6C1.33325 6.5 1.4895 7.15625 1.83325 8C2.177 8.8125 2.64575 9.6875 3.20825 10.5625C4.27075 12.2812 5.52075 13.9375 6.33325 14.9375C7.1145 13.9375 8.3645 12.2812 9.427 10.5625C9.9895 9.6875 10.4583 8.8125 10.802 8C11.1458 7.15625 11.3333 6.5 11.3333 6C11.3333 3.25 9.08325 1 6.33325 1Z" fill="#EEE8F4" />
            </svg>
            <label for="">{{__('Place')}}</label>
         </span>
         <div class="min-w-60 f-bri bg-transparent text-primary_color_6 border-0 border-b border-primary_color_6 py-3 border">
            <select name="" id="SearchEventCity" placeholder="Search by event place" class="text-h4 select2 placeholder-primary_color_6 w-full min-w-60 f-bri  text-primary_color_6    outline-0" style="width: 100%;" data-minimum-results-for-search="Infinity">
               <option value="">{{__('Any place')}}</option>
               @foreach ($citys['city'] as $city)
               <option value="{{$city['id']}}">{{ $lang == 'ar' ? $city['arabic_name'] : $city['name'] }}</option>
               @endforeach
            </select>
         </div>
      </div>
      <div class="w-full sm:w-auto">
         <span class="flex items-center gap-1 mb-1">
            <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
               <path d="M8.16675 3.5C8.16675 3.25 8.3855 3 8.66675 3C8.91675 3 9.16675 3.25 9.16675 3.5V7.75L11.9167 9.59375C12.1667 9.75 12.2292 10.0625 12.073 10.2812C11.9167 10.5312 11.6042 10.5938 11.3855 10.4375L8.3855 8.4375C8.22925 8.34375 8.1355 8.1875 8.1355 8L8.16675 3.5ZM8.66675 0C13.073 0 16.6667 3.59375 16.6667 8C16.6667 12.4375 13.073 16 8.66675 16C4.22925 16 0.666748 12.4375 0.666748 8C0.666748 3.59375 4.22925 0 8.66675 0ZM1.66675 8C1.66675 11.875 4.79175 15 8.66675 15C12.5105 15 15.6667 11.875 15.6667 8C15.6667 4.15625 12.5105 1 8.66675 1C4.79175 1 1.66675 4.15625 1.66675 8Z" fill="#EEE8F4" />
            </svg>
            <label for="">{{__('Date')}}</label>
            <span class="ms-auto  cursor-pointer clear-datepicker hidden">
               <i class="fa-regular fa-circle-xmark fa-lg "></i>
            </span>
         </span>
         <div id="event_date" class=" min-w-60 f-bri bg-transparent text-primary_color_6 border-0 border-b border-primary_color_6 py-3 border">
            <select name="" id="SearchEventDate" placeholder="Search by event date" class="text-h4 select2 placeholder-primary_color_6 w-full    outline-0" style="width: 100%;" data-minimum-results-for-search="Infinity">
               <option value="">{{__('all')}}</option>
               <option value="Today">{{__('Today')}}</option>
               <option value="Tommorow">{{__('Tommorow')}}</option>
               <option value="This Week">{{__('This Week')}}</option>
               <option value="choose_date">{{__('choose date')}}</option>
            </select>
         </div>
         <input type="text" name="" placeholder="choose event date" id="datepicker" class="datepicker hidden placeholder-primary_color_6 w-full min-w-60 f-bri bg-transparent text-primary_color_6 border-0 border-b border-primary_color_6 py-3 border   outline-0">
         <div class="datepicker-container relative"></div>
      </div>
   </div>
</div>


<div class="container mt-9 md:mt-40 xl:mt-m133 hidden lg:block " id="UpcomingEventsSection">
   <div class="flex justify-between flex-wrap gap-y-4">
      <h2 class="text-h5 lg:text-h2 text-primary_color_6 lg:text-white font-medium">{{__('Upcoming Events')}}</h2>
      <div class="select-parent flex gap-2 flex-wrap gap-y-4">
         <div
            class=" text-h6 rounded-full  bg-gray_f bg-opacity-5  hover:bg-primary_color_a11  hover:bg-opacity-10 gap-x-1  py-2 px-6 flex items-center  justify-between   h-8 md:h-12">
            <div>
               <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M8.61328 1.1875L11.2383 5.5625C11.4023 5.83594 11.4023 6.16406 11.2383 6.4375C11.1016 6.71094 10.8008 6.875 10.5 6.875H5.25C4.92188 6.875 4.62109 6.71094 4.48438 6.4375C4.32031 6.16406 4.32031 5.83594 4.48438 5.5625L7.10938 1.1875C7.27344 0.914062 7.54688 0.75 7.875 0.75C8.17578 0.75 8.44922 0.914062 8.61328 1.1875ZM7.875 9.28125C7.875 8.67969 8.33984 8.1875 8.96875 8.1875H12.9062C13.5078 8.1875 14 8.67969 14 9.28125V13.2188C14 13.8477 13.5078 14.3125 12.9062 14.3125H8.96875C8.33984 14.3125 7.875 13.8477 7.875 13.2188V9.28125ZM3.5 14.75C1.55859 14.75 0 13.1914 0 11.25C0 9.33594 1.55859 7.75 3.5 7.75C5.41406 7.75 7 9.33594 7 11.25C7 13.1914 5.41406 14.75 3.5 14.75Z" fill="#666666" />
               </svg>
            </div>
            <select name="" id="SearchEventCat" data-placeholder="{{ __('Filter') }}" style="width: 100%;" class=" select2 placeholder-primary_color_6 outline-0" data-minimum-results-for-search="Infinity">
               <option value="">{{ __('Filter') }}</option>
               @foreach ($categorys['data'] as $cat)
               <option value="{{ $cat['id']}}">{{ $lang == 'ar' ? $cat['ar_name'] : $cat['name'] }}</option>
               @endforeach
            </select>
         </div>
      </div>
   </div>
   <div class="mt-4 hidden text-center" id="empty_search">
      <svg class="mx-auto" width="128" height="120" viewBox="0 0 128 120" fill="none" xmlns="http://www.w3.org/2000/svg">
         <path fill-rule="evenodd" clip-rule="evenodd" d="M11.2766 14.0379C10.4503 13.4008 9.47195 13.0909 8.50501 13.0909C7.16908 13.0909 5.84495 13.6759 4.96014 14.7939C3.42508 16.7288 3.76483 19.5265 5.72195 21.0414L6.73287 21.8247C6.80973 21.8204 6.88673 21.8182 6.96377 21.8182C7.83402 21.8182 8.71455 22.0996 9.45823 22.6781L109.358 100.779C109.879 101.186 110.273 101.696 110.533 102.256L116.721 107.051C118.69 108.572 121.514 108.224 123.038 106.295C124.573 104.361 124.233 101.563 122.276 100.048L119.726 98.0716C123.216 95.6312 125.499 91.5822 125.499 87V70.125C119.907 70.125 115.374 65.5919 115.374 60C115.374 54.408 119.907 49.875 125.499 49.875V33C125.499 25.5438 119.455 19.5 111.999 19.5H18.3256L11.2766 14.0379ZM95.867 100.5L87.233 93.75H17.499C13.777 93.75 10.749 90.722 10.749 87V75.4655C16.7042 72.8568 20.874 66.9065 20.874 60C20.874 53.0935 16.7042 47.1427 10.749 44.534V33.9551L4.56478 29.1203C4.19675 30.349 3.99902 31.6514 3.99902 33V49.875C9.59098 49.875 14.124 54.408 14.124 60C14.124 65.5919 9.59098 70.125 3.99902 70.125V87C3.99902 94.4558 10.0432 100.5 17.499 100.5H95.867ZM30.999 49.7865V73.5C30.999 77.2277 34.0213 80.25 37.749 80.25H69.9651L61.3311 73.5H37.749V55.0636L30.999 49.7865ZM53.1702 46.5L88.0148 73.5H91.749V46.5H53.1702ZM98.499 73.5C98.499 75.8959 97.2507 78.0004 95.3688 79.1983L113.825 93.4992C116.663 92.701 118.749 90.0896 118.749 87V75.4655C112.794 72.8568 108.624 66.9065 108.624 60C108.624 53.0935 112.794 47.1427 118.749 44.534V33C118.749 29.278 115.721 26.25 111.999 26.25H27.0368L44.4591 39.75H91.749C95.4771 39.75 98.499 42.7719 98.499 46.5V73.5Z" fill="#312C35"></path>
      </svg>
      <p class="text-h6 mb-1 mt-4">{{__('No tickets found')}} </p>
   </div>
   <div class="spinner hidden mb-2" id="spinner" style="display: block;">
      <div><span></span><span></span><span></span></div>
   </div>
   <div class="mt-16 grid grid-cols-2 lg:grid-cols-3 gap-2 xl:gap-x-g32 xl:gap-y-g32 upcomingEventsCon" id="upcomingEventsCon"></div>
   <div class="flex justify-center">
      <button id="load_more"
         class="font-medium mt-16  rounded-5xl border border-light  bg-gray_f text-center    py-2 px-4 lg:px-p32    lg:w-48 f-bri l leading-5  inline-block">
         {{__('Load More')}}</button>
   </div>
</div>

<div class="container mt-9 md:mt-40 xl:mt-32">
   <div class=" lg:text-center mb-4 lg:mb-8">
      <h2 class="text-h5 lg:text-h2 text-primary_color_6 lg:text-white font-medium text-primary_color_6 lg:text-white">
         <span class="hidden md:inline-block">{{__('Discover the lovely ')}}</span> {{__('categories')}}
      </h2>
      <p class="text-gray_6 mt-1">{{__('Discover the lovely categories')}}</p>
   </div>
</div>
<div class="xl:px-p423 m-auto">
   <div class="swiper swiper-cat order-1 lg:order-2 m-auto">
      <div class="swiper-wrapper">
         @foreach ($categorys['data'] as $cat)
         <div class="swiper-slide text-center flex items-center flex-col ">
            <a href="/all-events">
               <a href="/all-events?category_id={{$cat['id']}}">
                  <div
                     class=" mx-auto flex items-center justify-center w-16 h-16 lg:w-24 lg:h-24 rounded-full bg-light bg-opacity-5  border border-primary_color_o10_1   ">
                     <img class="w-9 lg:w-w-32" src="{{ url('images/upload/' .$cat['app_icon']) }}" alt="{{ $cat['name']}}">
                  </div>
                  <h5 class="mt-3 text-h6 lg:text-h5">{{ $lang == 'ar' ? $cat['ar_name'] : $cat['name'] }}</h5>
               </a>
         </div>
         @endforeach
      </div>
   </div>
</div>



<div class="container mt-9 md:mt-40 xl:mt-32 block lg:hidden overflow-hidden" id="">
   <div class="flex justify-between flex-wrap gap-y-4">
      <h2 class="text-h5 lg:text-h2 text-primary_color_6 lg:text-white font-medium">{{__('Upcoming Events')}}</h2>
   </div>

   <div class="upcomingEventsConswiper">
      <div class="mt-4 lg:mt-16  swiper-wrapper upcomingEventsCon" id=""></div>
   </div>

</div>

<div class="container mt-9 md:mt-40 xl:mt-32">
   <div class="text-start lg:text-center mb-4 lg:mb-16">
      <h2 class="text-h5 lg:text-h2 text-primary_color_6 lg:text-white hidden lg:block font-medium">{{__('Discover the lovely cities')}}</h2>
      <h2 class="text-h5 lg:text-h2 text-primary_color_6 lg:text-white lg:hidden">{{__('Cities to discover')}}</h2>
      <p class="text-gray_6 mt-1 hidden lg:block">{{__('Discover the lovely cities')}}</p>
   </div>
</div>
<div class="md:container">
   <div class="relative">
      <div class="swiper swiper-city order-1 lg:order-2 m-auto">
         <div class="swiper-wrapper">
            @foreach ($citys['city'] as $city)
            <div class="swiper-slide text-center flex items-center flex-col">
               <a href="/all-events?city_id={{$city['id']}}">
                  <div class="lg:w-24 w-32 h-20 lg:h-28  md:h-52 md:w-48  xl:h-h-256  xl:w-w-256 rounded-lg rounded-2xl overflow-hidden">
                     <img class="w-full h-full object-cover"
                        src="{{ url('images/upload/' .$city['image']) }}"
                        alt="">
                  </div>
                  <h3 class="lg:text-h3 text-h6 mt-3">{{ $lang == 'ar' ? $city['arabic_name'] : $city['name'] }}</h3>
               </a>
            </div>
            @endforeach
         </div>
      </div>
      <div class="swiper-pagination hidden lg:block"></div>
   </div>
</div>

<div class="container mt-9 md:mt-40 xl:mt-32 hidden lg:block">
   <div class="">
      <h2 class="text-h5 lg:text-h2 text-primary_color_6 lg:text-white font-medium">{{__('Previous Events')}}</h2>
   </div>
   <div class="mt-16 grid grid-cols-2 lg:grid-cols-3 gap-4 ">
      @foreach ($pervious_events as $item)
      <a href="{{ url('event/' . $item->id . '/' . Str::slug($item->name)) }}">
         <div class="h-full bg-light hover:bg-primary_color_o25_9 bg-opacity-5 rounded-2xl border border-primary_color_o10_1 overflow-hidden grayscale-hover ">
            <div class="h-32 md:h-48">
               <img class="w-full h-full object-cover grayscale transition-all "
                  src="{{ url('images/upload/' . $item->image) }}"
                  alt="">
            </div>
            <div class="flex gap-2 md:gap-4 p-1 md:p-4 flex-wrap md:flex-nowrap">
               <div class="text-center flex  items-baseline gap-1 md:gap-0 md:flex-col ">
                  <span class="text-primary_color_7 text-h7 font-bold uppercase f-bri">
                     {{ Carbon\Carbon::parse($item->start_time)->format('M') }}
                  </span>
                  <span class="font-bold text-h3  f-bri">
                     {{ Carbon\Carbon::parse($item->start_time)->format('d') }}
                  </span>
               </div>
               <div>
                  <h5 class="text-h6 md:text-h5 font-medium  md:mb-2">
                     {{ $lang == 'ar' ? $item->name_arabic : $item->name }}
                  </h5>
                  <!-- <p class="pline2 f-bri text-gray_6 text-h6">
                     {{ $lang == 'ar' ? $item->description_arabic : $item->description }}
                  </p> -->
               </div>
            </div>
         </div>
      </a>
      @endforeach
   </div>
   <div class="flex justify-center">
      <a href="/all-events"
         class=" m-auto mt-16  rounded-5xl border border-light   text-center    py-2 px-4 lg:px-p32    f-bri l leading-5  inline-block text-h6 md:text-h5 ">
         {{__('Discover all previous events')}}</a>
   </div>
</div>

<div class="container mt-9 md:mt-40 xl:mt-32 block lg:hidden overflow-hidden">
   <div class="">
      <h2 class="text-h5 lg:text-h2 text-primary_color_6 lg:text-white font-medium">{{__('Previous Events')}}</h2>
   </div>
   <div class="upcomingPreviousEvents">
      <div class="mt-4 lg:mt-16 swiper-wrapper ">
         @foreach ($pervious_events as $item)
         <div class="swiper-slide ">
            <a href="{{ url('event/' . $item->id . '/' . Str::slug($item->name)) }}">
               <div class=" h-full bg-light hover:bg-primary_color_o25_9 bg-opacity-5 rounded-2xl border border-primary_color_o10_1 overflow-hidden grayscale-hover ">
                  <div class="h-32 md:h-48">
                     <img class="w-full h-full object-cover grayscale transition-all "
                        src="{{ url('images/upload/' . $item->image) }}"
                        alt="">
                  </div>
                  <div class="flex gap-1 md:gap-4 p-1 md:p-4 flex-wrap md:flex-nowrap flex-col lg:flex-row">
                     <div class="text-center flex  items-baseline gap-1 md:gap-0 md:flex-col ">
                        <span class="text-primary_color_7 text-h7 font-bold uppercase f-bri">
                           {{ Carbon\Carbon::parse($item->start_time)->format('M') }}
                        </span>
                        <span class="font-bold text-h7 lg:text-h3 f-bri text-primary_color_7 lg:text-white">
                           {{ Carbon\Carbon::parse($item->start_time)->format('d') }}
                        </span>
                     </div>
                     <div>
                        <h5 class="text-h6 md:text-h5 font-medium  md:mb-2">
                           {{ $lang == 'ar' ? $item->name_arabic : $item->name }}
                        </h5>
                        <!-- <p class="pline2 f-bri text-gray_6 text-h6">
                           {{ $lang == 'ar' ? $item->description_arabic : $item->description }}
                        </p> -->
                     </div>
                  </div>
               </div>
            </a>
         </div>
         @endforeach
      </div>
   </div>
   <div class="flex justify-center">
      <a href="/all-events"
         class=" m-auto mt-16  rounded-5xl border border-light   text-center    py-2 px-4 lg:px-9    f-bri l leading-5  inline-block text-h6 md:text-h5 ">
         {{__('Discover all previous events')}}</a>
   </div>
</div>

@endsection



@if (session('direction') == 'rtl')
<script>
   var lang = 'ar';
</script>
@else
<script>
   var lang = 'en';
</script>
@endif


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script script>
   $(document).ready(function() {

      let limit = 6;

      function updateLimitBasedOnScreenSize() {
         if (window.innerWidth <= 768) { // 1024px is the breakpoint for 'lg' in Tailwind CSS
            limit = -1;
         } else {
            limit = 6;
         }
      }

      // Initial check
      updateLimitBasedOnScreenSize();

      // Update limit on window resize
      window.addEventListener('resize', updateLimitBasedOnScreenSize);

      console.log(limit); // You can remove this line; it's just for testing

      $("#load_more").click(function() {
         limit += 3
         fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);
      });

      function scroll() {
         $('html, body').animate({
            scrollTop: $("#UpcomingEventsSection").offset().top
         }, 800);
      }

      var SearchEventName = ''
      $('#SearchEventName').on('keyup', function() {
         $("#Searchbtn").removeClass('hidden');
         SearchEventName = $(this).val();
         if (SearchEventName == '') {
            $("#Searchbtn").addClass('hidden');
            fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);
         }
      });

      $("#Searchbtn").on("click", function() {
         $("#clear_search").removeClass('hidden');
         $("#Searchbtn").addClass('hidden');

         fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);
         scroll()
      })
      $("#clear_search").on("click", function() {
         $("#clear_search").addClass('hidden');
         $("#Searchbtn").addClass('hidden');
         $('#SearchEventName').val('')
         fetchEvents('', SearchEventDate, SearchEventCity, SearchEventCat, limit);
      })
      var SearchEventCity = ''
      $('#SearchEventCity').on('change', function() {
         SearchEventCity = $(this).val();
         console.log(SearchEventCity);
         fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);
         scroll()
      });
      var SearchEventDate = ''
      $('#SearchEventDate').on('change', function() {
         SearchEventDate = $(this).val();
         fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);
         scroll()
      });
      var SearchEventCat = ''
      $('#SearchEventCat').on('change', function() {
         SearchEventCat = $(this).val();
         fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);
      });

      fetchEvents(SearchEventName, SearchEventDate, SearchEventCity, SearchEventCat, limit);

      function fetchEvents(query = '', date = '', city_id = '', category_id = '', limit = 3) {
         let url = `{{url('api/user/search-event/web')}}`;
         $.ajax({
            url: url,
            method: 'POST',
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
               search: query,
               date: date,
               city_id: city_id,
               category_id: category_id,
               limit: limit
            },
            beforeSend: function() {
               $('.spinner').show();
            },
            success: function(data) {
               $('.upcomingEventsCon').html('');
               let events = [];
               if (Array.isArray(data)) {
                  events = data;
               } else if (data.data && Array.isArray(data.data)) {
                  events = data.data;
               }
               if (events.length === 0) {
                  $('#empty_search').removeClass("hidden");
               } else {
                  $('#empty_search').addClass("hidden");
               }
               events.forEach(item => {
                  let gallery = '';
                  if (item.gallery && item.gallery.length > 0) {
                     gallery = item.gallery.split(',').map(image => {
                        return `<div class="swiper-slide h-32 md:h-48"> <img class='w-full h-full object-cover' src="${item.imagePath}${image}" alt="${item.name}"> </div>`;
                     }).join('');
                  }
                  let day = new Date(item.start_time).getUTCDate();
                  let month = dateFormat(item.start_time).shortMonth;
                  /*${item.gallery && item.gallery.length > 0 ? 
                                    `<div class="swiper-event">
                                          <div class="swiper-wrapper">
                                            ${gallery}
                                          </div>
                                    </div>` 
                                    : 
                                    `<img class="w-full h-full object-cover" src="${item.imagePath}${item.image}" alt="${item.name}">`
                                 }*/
                  let eventHtml = `
                        <a href="/event/${item.id}/${item.slug}" class="swiper-slide">
                            <div class="ticket-wahlist h-full bg-light hover:bg-primary_color_o25_9 bg-opacity-5 rounded-2xl border border-primary_color_o10_1 hover:border-gray_9 overflow-hidden">
                            <div class="h-32 md:h-48">
                                 
                                 <img class="w-full h-full object-cover" src="${item.imagePath}${item.image}" alt="${item.name}">
                              </div>
                                <div class="relative flex gap-1 md:gap-4 p-1 md:p-4 flex-wrap md:flex-nowrap flex-col lg:flex-row">
                                    <div class="text-center flex  items-baseline gap-1 md:gap-0 md:flex-col">
                                       ${item.is_repeat == 0 ?
                                       `<span class="text-primary_color_7 text-h7 font-bold uppercase f-bri">${month}
                                        </span>
                                        <span class="font-bold text-h7 lg:text-h3 f-bri text-primary_color_7 lg:text-white">${day}
                                        </span>`:``}
                                    </div>
                                    <div>
                                        <h5 class="text-h6 md:text-h5 font-medium  md:mb-2">
                                    ${ lang == 'ar' ? item.name_arabic : item.name }
                                        </h5>
                                      
                                    </div>
                                    
                                </div>
                            </div>
                        </a>
                    `;
                  $('.upcomingEventsCon').append(eventHtml);
               });
               setTimeout(initializeSwiper, 1000);

            },
            complete: function() {
               $('.spinner').hide();
            }
         });
      }

      function initializeSwiper() {
         let swiperEvent = new Swiper(".swiper-event", {
            slidesPerView: 1,
            loop: true,
            autoplay: {
               delay: 1200,
               disableOnInteraction: false,
            },
         });
      }

      document.addEventListener('DOMContentLoaded', function() {
         initializeSwiper();
      });


      function dateFormat(date) {
         let dateObject = new Date(date);
         let dayOptions = {
            day: 'numeric'
         };
         let day = dateObject.toLocaleDateString('en-US', dayOptions);
         let monthOptions = {
            month: 'long'
         };
         let month = dateObject.toLocaleDateString('en-US', monthOptions);
         let shortMonth = month.substring(0, 3);

         return {
            day,
            shortMonth
         }
      }
   });

   $(document).ready(function() {
      $(".datepicker").datepicker({
         beforeShow: function(input, inst) {
            setTimeout(function() {
               inst.dpDiv.appendTo('.datepicker-container');
            }, 0);
         }
      });


      $('#event_date').change(function() {
         var selectedValue = $('#event_date select').val();
         if (selectedValue === 'choose_date') {
            $(this).hide();
            $('.clear-datepicker').show();
            $('#datepicker').removeClass('hidden').datepicker('show');
         }
      });

      $('.clear-datepicker').click(function() {
         $(this).hide();

         if ($('#event_date selec').val('all')) {
            $("#event_date select").val('all').trigger("change");
            $('#event_date ').show();
            $('#datepicker').addClass('hidden');
         }
      });

   });
</script>