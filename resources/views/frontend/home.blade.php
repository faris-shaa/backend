@extends('frontend.master', ['activePage' => 'home'])
@section('title', __('Home'))
@section('content')
<!-- home style  -->
<style>
   .control-slider
   {
   position: absolute;
   top: 13% !important;
   width: 107% !important;
   display: flex;
   justify-content: space-between;
   transform: translateY(-50%);
   pointer-events: none;
   z-index: 999;
   left: -2.3em;
   }
   .hover-hide
   {
      display: none;
   }
</style>
<!-- home style  -->
<link rel="stylesheet" href="{{ url('/frontend/css/event-card-style.css') }}">
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
<link rel="stylesheet" href="{{asset('frontend/css/slider-custom.css')}}">
<link rel="stylesheet" href="{{asset('frontend/css/slider-custom-second.css')}}">
<div class="bg-scroll" style="background:linear-gradient(to top, #eae9e9, white)">
   <div class="w-full h-full m-auto ">
      <div id="default-carousel" class="relative" data-carousel="slide">
         <!-- Carousel wrapper -->
         <div class="your-carousel relative w-full mx-auto overflow-hidden h-1/2" style="direction:ltr !important ">
            @forelse ($banner as $item)
            <div class="h-1/2 relative">
               <a href="{{ url('event/' . $item->event->id . '/' . Str::slug($item->event->name)) }}">
                  <div class="h-1/2 relative">
                     <img src="{{ asset('/images/upload/' . $item->image) }}"
                        class="object-cover h-[600px] w-full mx-auto xxsm:max-msm:h-full" alt="Image 1">
                     <!-- <h1
                        class="font-poppins font-medium leading-6 text-center absolute inset-0 flex items-center justify-center text-5xl text-white drop-shadow-[1px_1px_1px_rgba(0,0,0,0.5)]" style="font-weight:600;">
                        {{ $item->title }}
                     </h1> -->
                  </div>
               </a>
            </div>
            @empty
            <div class="h-1/2 bg-primary relative">
               <div class="h-1/2 relative">
                  <div class="object-cover h-[600px] w-full mx-auto xxsm:max-msm:h-full" alt="">
                  </div>
                  <h1
                     class="font-poppins font-medium leading-6 text-center absolute inset-0 flex items-center justify-center text-5xl text-white drop-shadow-[1px_1px_1px_rgba(0,0,0,0.5)]">
                     {{ __('Welcome') }}
                  </h1>
               </div>
               <h1
                  class="font-poppins font-medium leading-6 text-center absolute inset-0 flex items-center justify-center text-5xl text-white drop-shadow-[1px_1px_1px_rgba(0,0,0,0.5)]">
                  {{__('Welcome')}}
               </h1>
            </div>
            @endforelse
         </div>
         <!-- Custom privious/next button -->
         <button type="button"
            class="hidden absolute hs-carousel-prev left-2 top-1/2 md:max-xxmd:top-1/3 transform -translate-y-1/2 bg-primary text-white rounded-full w-10 h-10 md:flex justify-center items-center hover:bg-gray-600">
         <i class="fa-solid fa-arrow-left opacity-100"></i>
         
         </button>
         <button type="button"
            class="hidden absolute hs-carousel-next right-2 top-1/2 md:max-xxmd:top-1/3 transform -translate-y-1/2 bg-primary text-white rounded-full w-10 h-10 md:flex justify-center items-center hover:bg-gray-600">
         <i class="fa-solid fa-arrow-right"></i>
         </button>
         <!-- Carousel wrapper end -->
         {{-- Searchbar --}}
         <div class="main-search-design">
            <div
               class="sm-ml-40 ml-95 sm-margin-top-search-bar xxmd:absolute xmd:max-lg:top-[20%] z-30 3xl:top-1/2 2xl:top-1/2 2xl:mt-2 3xl:mx-52 2xl:mx-60 1xl:top-1/2 1xl:mt-0 1xl:mx-36 xl:top-[60%] xl:mt-32 xl:mx-36 xlg:mt-5
               xlg:mx-32 lg:top-[90%] lg:mx-36 xxmd:top-[0%] xxmd:mx-24 xmd:top-12 xmd:mx-32 md:top-80 md:mx-28 sm:top-96 sm:flex-wrap sm:mx-20 msm:flex-wrap msm:mx-16 msm:top-5 xsm:flex-wrap xsm:mx-10 xxsm:flex-wrap xxsm:top-0 xxsm:mx-5
               3xl:w-[74%] 1xl:w-[81%] xl:w-[82%] xlg:w-[77%] lg:w-[70%] xxmd:w-[80%] xmd:w-[70%] md:w-[70%] sm:w-[70%] msm:w-[70%] xsm:w-[80%] xxsm:w-[80%] rtl-mr-9rem sm-rm-remove">
               <div
                  class="search-seaction xlg:ml-[7%] xxmd:max-lg:mt-[50%] xxsm:ml-[0%] bg-white rounded-lg flex p-6 justify-between lg:mt-0 md:mt-[5rem] xlg:mt-8 3xl:flex-nowrap 1xl:flex-nowrap xxmd:flex-nowrap md:flex-wrap sm:flex-wrap msm:flex-wrap xsm:flex-wrap xxsm:flex-wrap" style=" border-radius:3.5rem">
                  <div
                     class=" xmd:w-1/2 md:w-full sm:w-full msm:w-full xsm:w-full xxsm:w-full xmd:mx-0 xmd:py-3 xxmd:py-b-10 xxmd:mx-5 sm:py-3 msm:py-3 xsm:py-3 xxsm:py-3 md:mx-0 md:py-3 sm:mx-0 msm:mx-0 xsm:mx-0 xxsm:mx-0">
                     <div class="flex">
                        <label for="category"
                           class="font-poppins font-medium text-lg leading-4 text-color bold">{{ __('Category') }}</label>
                     </div>
                     <div class="pt-3">
                        <form method="post" action="{{ url('all-events') }}">
                           @csrf
                           <select id="category" name="category" class="select2 z-20 w-full">
                              <option
                                 class="text-black font-poppins hover:text-primary hover:bg-primary-light p-2"
                                 value="">
                                 {{ __('All') }}
                              </option>
                              @foreach ($category as $item)
                              <option
                                 class="text-black font-poppins hover:text-primary hover:bg-primary-light p-2"
                                 value="{{ $item->id }}">
                                 @if (session('direction') == 'rtl')
                                 {{ $item->ar_name }}
                                 @else
                                 {{ $item->name }}
                                 @endif
                              </option>
                              @endforeach
                           </select>
                     </div>
                  </div>
                  <!-- <div class="xmd:w-1/2 md:w-full sm:w-full msm:w-full xsm:w-full xxsm:w-full">
                  <div class="flex">
                  <label for="event"
                     class="font-poppins font-medium text-lg leading-4 text-color">{{ __('Event Type') }}</label>
                  </div>
                  <div class="pt-3 ">
                  <select id="event" name="type" class="select2 z-20 w-full">
                  <option class="font-poppins font-normal text-sm text-black leading-6" selected
                     value="">
                  {{ __('All') }}</option>
                  <option class="font-poppins font-normal text-sm text-black leading-6"
                     value="online">
                  {{ __('Online') }}</option>
                  <option class="font-poppins font-normal text-sm text-black leading-6"
                     value="offline">
                  {{ __('Venue') }}</option>
                  </select>
                  </div>
                  </div> -->
                  <div
                     class="xmd:w-1/2 md:w-full sm:w-full msm:w-full xsm:w-full xxsm:w-full xmd:mx-0 xmd:py-0 xxmd:py-0 xxmd:mx-5 sm:py-3 msm:py-3 xsm:py-3 xxsm:py-3 md:mx-0 md:py-3 sm:mx-0 msm:mx-0 xsm:mx-0 xxsm:mx-0">
                  <div class="flex">
                  <label for="duration"
                     class="font-poppins font-medium text-lg leading-4 text-color bold">{{ __('Duration') }}</label>
                  </div>
                  <div class="pt-3">
                  <select id="duration" name="duration"
                     class="select2 z-20 w-full border border-gray-300">
                  <option class="font-poppins font-normal text-sm text-black leading-6 " selected
                     value="">
                  {{ __('All') }}</option>
                  <option class="font-poppins font-normal text-sm text-black leading-6"
                     value="Today">
                  {{ __('Today') }}</option>
                  <option class="font-poppins font-normal text-sm text-black leading-6"
                     value="Tomorrow">
                  {{ __('Tomorrow') }}</option>
                  <option class="font-poppins font-normal text-sm text-black leading-6"
                     value="ThisWeek">
                  {{ __('This week') }}</option>
                  <option class="font-poppins font-normal text-sm text-black leading-6"
                     value="date">
                  {{ __('Choose Date') }}</option>
                  </select>
                  </div>
                  </div>
                  <div
                     class="xmd:w-1/2 md:w-full sm:w-full msm:w-full xsm:w-full xxsm:w-full xmd:mx-0 xmd:py-0 xxmd:py-0 xxmd:mx-5 sm:py-3 msm:py-3 xsm:py-3 xxsm:py-3 md:mx-0 md:py-3 sm:mx-0 msm:mx-0 xsm:mx-0 xxsm:mx-0 date-section hidden">
                  <div class="flex">
                  <label for="date"
                     class="font-poppins font-medium text-lg leading-4 text-black bold">{{ __('Choose date') }}</label>
                  </div>
                  <div class="pt-3">
                  <input class=" border rounded form-control form-control-a date"
                     placeholder="{{ __('Choose date') }}" name="date" id="date">
                  </div>
                  </div>
                  <div class="pt-2 ml-35pr">
                  <button type="submit"
                     class="px-10 py-3 sign-in-button-css text-center font-poppins font-normal text-base leading-6 rounded-md bold" >
                  {{ __('Search') }}
                  </button>
                  </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      {{-- 
   </div>
   --}}
   {{-- scroll --}}
   <div class="mr-4 flex justify-end">
      <a type="button" href="{{ url('#') }}"
         class="back-to-top bg-primary rounded-full p-4 fixed z-20  mt-72">
      <img src="{{ url('images/downarrow.png') }}" alt="" class="w-3 h-3 z-20">
      </a>
   </div>
   {{-- main --}}
   <div
      class="xxmd:mt-20 3xl:mx-52 2xl:mx-28 1xl:mx-28 xl:mx-36 xlg:mx-32 lg:mx-36 xxmd:mx-24 xmd:mx-32 md:mx-28 sm:mx-20 msm:mx-16 xsm:mx-10 xxsm:mx-5  xxmd:pt-0  z-10 relative">
      {{-- Latest Events --}}
      <div
         class="absolute  blur-3xl opacity-10 s:bg-opacity-10 3xl:w-[370px] 3xl:h-[370px] 2xl:w-[300px] 2xl:h-[300px] 1xl:w-[300px] xmd:w-[300px] xmd:h-[300px] sm:w-[200px] sm:h-[300px] xxsm:w-[300px] xxsm:h-[300px] rounded-full -mt-5 2xl:-ml-20 1xl:-ml-20 sm:ml-2 xxsm:-ml-7">
      </div>
      <div class="flex sm:flex-wrap msm:flex-wrap xsm:flex-wrap xxsm:flex-wrap justify-between pt-20 mx-5 z-10 sm-pt-3-rem sm-remove-mx-5">
         <div class="">
            <p
               class="font-poppins font-semibold md:text-5xl xxsm:text-2xl xsm:text-2xl sm:text-2xl text-primary-color leading-1 ">
               {{ __('Latest Event') }}
            </p>
         </div>
         <div class=" xxsm:max-sm:hidden">
            <a type="button" href="{{ url('/all-events') }}"
               class="px-10 py-3 text-blue border border-blue text-center font-poppins font-normal text-base leading-6 rounded-md flex primary-color-button sm-button-center ">{{ __('See all') }}
               <i class="fa-solid fa-arrow-right w-3 h-3 mt-1.5 ml-2 arrow-image mt-255  rtl-mr-t-7" ></i></a>
         </div>
      </div>
      @if (count($events) == 0)
      <div class="font-poppins font-medium text-lg leading-4 text-black mt-5 ml-5 capitalize">
         {{ __('There are no events added yet') }}
      </div>
      @endif
      <div class="container-slider event">
         <div class="slider">
            @foreach ($events as $item)
            <div class="slide" onmouseover="showDiv('{{$item->id}}')" 
        onmouseout="hideDiv('{{$item->id}}')" >
               <a href="{{ url('event/' . $item->id . '/' . Str::slug($item->name)) }}" class="card_event">
                  <img src="{{ url('images/upload/' . $item->image) }}" class="card__image" alt="" />
                  <div class="card__overlay hover-hide" id="{{$item->id}}">
                     <div class="card__header">
                        <div class="card__header-text">
                           <h3 class="card__title"> @if (session('direction') == 'rtl') {{ $item->name_arabic }} @else {{ $item->name }}  @endif</h3>
                           <span class="card__status rtl-font-18">
                           <i class="fa fa-calendar" aria-hidden="true"></i>

                           @if($item->is_repeat == 1 )
                              @if (session('direction') == 'rtl')
                               
                                 {{ Carbon\Carbon::now()->locale('ar')->translatedFormat('d F Y') }}
                                 
                              @else
                                 
                                  {{ Carbon\Carbon::now()->format('d M Y') }}
                                 
                                 
                              @endif
                           @else 
                              @if (session('direction') == 'rtl')
                                 @if($item->start_time ==  $item->end_time)
                                 {{ Carbon\Carbon::parse($item->start_time)->locale('ar')->translatedFormat('d F Y') }}
                                 @else
                                 {{ Carbon\Carbon::parse($item->start_time)->locale('ar')->translatedFormat('d F Y') }} -
                                 {{ Carbon\Carbon::parse($item->end_time)->locale('ar')->translatedFormat('d F Y') }}
                                 @endif
                              @else
                                  @if($item->start_time ==  $item->end_time)
                                  {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }}
                                  @else
                                  {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }} -
                                    {{ Carbon\Carbon::parse($item->end_time)->format('d M Y') }}
                                  @endif
                              @endif

                           @endif
                           </span>
                        </div>
                        <button type="submit" class="event-button-ticket  text-success border border-success">
                        {{ __('Ticket') }}
                        </button>
                        @if (Auth::guard('appuser')->user())
                        @if (Str::contains($user->favorite, $item->id))
                        <button class="event-button-ticket "  style="margin-top: -400px;margin-left: -35px; background: rgba(225, 225, 225, 0.2);
                           border-radius: 50%;">
                        <img src="{{ url('images/heart-fill.svg') }}" alt="" class="">
                        </button>
                        @else
                        <button class="event-button-ticket "  style="margin-top: -400px;margin-left: -35px;background: rgba(225, 225, 225, 0.2);
                           border-radius: 50%;">
                        <img src="{{ url('images/heart.svg') }}" alt="" class="">
                        </button>
                        @endif
                        @endif
                     </div>
                     <p class="card__description">  
                        {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }} -
                        {{ Carbon\Carbon::parse($item->end_time)->format('d M Y') }}
                     </p>
                  </div>
               </a>
            </div>
            <!-- @if ($loop->iteration == 4)
            @break
            @endif -->
            @endforeach
         </div>
         <script type="text/javascript">
  function showDiv(id) {
            document.getElementById(id).style.display = 'block';
        }

        function hideDiv(id) {
          document.getElementById(id).style.display = 'none';
          
        }
</script>
         <!-- <div class="control-slider">
            <i class="fas fa-chevron-left opacity-100" id="arrow-left"></i>
            <i class="fas fa-chevron-right opacity-100" id="arrow-right"></i>
         </div> -->
      </div>
    
      <div
         class=" grid gap-x-7 3xl:grid-cols-4 xl:grid-cols-4 xlg:grid-cols-2 xxmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 remove-pt-10">
   
         <div class="sm:hidden">
            <a type="button" href="{{ url('/all-events') }}"
               class="px-10 py-3 text-blue border border-blue text-center font-poppins font-normal text-base leading-6 rounded-md flex primary-color-button sm-button-center">{{ __('See all') }}
               <i class="fa-solid fa-arrow-right w-3 h-3 mt-1.5 ml-2 arrow-image mt-255  rtl-mr-t-7" ></i></a>
         </div>
      </div>
      {{-- Feature Categories --}}
      <div
         class="absolute  blur-3xl opacity-10 s:bg-opacity-10 3xl:w-[370px] 3xl:h-[370px] 2xl:w-[300px] 2xl:h-[300px] 1xl:w-[300px] xmd:w-[300px] xmd:h-[300px] sm:w-[200px] sm:h-[300px] xxsm:w-[300px] xxsm:h-[300px] rounded-full -mt-5 2xl:-ml-20 1xl:-ml-20 sm:ml-2 xxsm:-ml-7">
      </div>
      <div class="flex sm:flex-wrap msm:flex-wrap xsm:flex-wrap xxsm:flex-wrap justify-between pt-20 mx-5 z-10 sm-pt-3-rem sm-remove-mx-5">
         <div class="">
            <p
               class="font-poppins font-semibold md:text-5xl xxsm:text-2xl xsm:text-2xl sm:text-2xl text-primary-color leading-1 ">
               {{ __('Feature Categories') }}
            </p>
         </div>
         <div class=" xxsm:max-sm:hidden">
            <a type="button" href="{{ url('/all-category') }}"
               class="px-10 py-3 text-success border border-success text-center font-poppins font-normal text-base leading-6 rounded-md flex primary-color-button sm-button-center">{{ __('See all') }}
               <i class="fa-solid fa-arrow-right w-3 h-3 mt-1.5 ml-2 arrow-image mt-255  rtl-mr-t-7" ></i></a>
         </div>
      </div>
      @if (count($category) == 0)
      <div class="font-poppins font-medium text-lg leading-4 text-black mt-5 ml-5 capitalize">
         {{ __('There are no category added yet') }}
      </div>
      @endif
      <div class="container-slider">
         <div class="slider slider-second" style="height:205px">
            @foreach ($category as $item)
            <div class="slide">
            <a href="{{ url('events-category/' . $item->id) . '/' . Str::slug($item->name) }}" class="card_event">
               <img src="{{ url('images/upload/' . $item->image) }}" class="card__image" alt=""  style="height:155px;"/>
               <div class="card__overlay">
                  <div class="card__header" >
                     <div class="card__header-text" style="text-align: center;width: 100%;">
                        <h3 class="card__title">@if (session('direction') == 'rtl')
                                 {{ $item->ar_name }}
                                 @else
                                 {{ $item->name }}
                                 @endif</h3>
                     </div>
                  </div>
               </div>
            </a>
            </div>
            <!-- @if ($loop->iteration == 4)
            @break
            @endif -->
            @endforeach

         </div>
         <!-- <div class="control-slider-second">
            <i class="fas fa-chevron-left opacity-100" id="arrow-left-second"></i>
            <i class="fas fa-chevron-right opacity-100" id="arrow-right-second"></i>
         </div> -->

      </div>
      <div
         class=" grid gap-x-7 3xl:grid-cols-4 xl:grid-cols-4 xlg:grid-cols-2 xxmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 ">
   
         <div class="sm:hidden">
            <a type="button" href="{{ url('/all-category') }}"
               class="px-10 py-3 text-blue border border-blue text-center font-poppins font-normal text-base leading-6 rounded-md flex primary-color-button sm-button-center">{{ __('See all') }}
               <i class="fa-solid fa-arrow-right w-3 h-3 mt-1.5 ml-2 arrow-image mt-255  rtl-mr-t-7" ></i></a>
         </div>
      </div>
      <!-- <div
         class="grid gap-x-7 3xl:grid-cols-3 xl:grid-cols-3 xlg:grid-cols-2 xxmd:grid-cols-2 xxmd:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 msm:gapy-7 xxsm:gap-y-7 justify-between pt-10 z-10 relative" style="height:285px;">
         @foreach ($category as $item)
         <a href="{{ url('events-category/' . $item->id) . '/' . Str::slug($item->name) }}">
            <img src="{{ url('images/upload/' . $item->image) }}" alt=""
               class="rounded-lg w-full h-40 bg-cover object-cover">
            <a href="{{ url('events-category/' . $item->id) . '/' . Str::slug($item->name) }}">
               <p class="font-popping font-semibold text-xl leading-8 text-center pt-3">
                  {{ $item->name }}
               </p>
            </a>
            </a>
         <a href="{{ url('events-category/' . $item->id) . '/' . Str::slug($item->name) }}" class="card_event">
            <img src="{{ url('images/upload/' . $item->image) }}" class="card__image" alt=""  style="height:190px;"/>
            <div class="card__overlay">
               <div class="card__header" >
                  <div class="card__header-text" style="text-align: center;width: 100%;">
                     <h3 class="card__title">{{ $item->name }}</h3>
                  </div>
               </div>
            </div>
         </a>
         @if ($loop->iteration == 3)
         @break
         @endif
         @endforeach
         <div class="sm:hidden">
            <a type="button" href="{{ url('/all-category') }}"
               class="px-10 py-3 text-success border border-success text-center font-poppins font-normal text-base leading-6 rounded-md flex">{{ __('See all') }}
            <img src="{{ url('images/right-success.png') }}" alt=""
               class="w-3 h-3 mt-1.5 ml-2 arrow-image"></a>
         </div>
      </div> -->

      <div class="flex sm:flex-wrap msm:flex-wrap xsm:flex-wrap xxsm:flex-wrap justify-between pt-20 mx-5 z-10 sm-pt-3-rem sm-remove-mx-5">
         <div class="">
            <p
               class="font-poppins font-semibold md:text-5xl xxsm:text-2xl xsm:text-2xl sm:text-2xl text-primary-color leading-1 ">
               {{ __('Previous Events') }}
            </p>
         </div>
         <div class=" xxsm:max-sm:hidden">
            <a type="button" href="{{ url('/events/previous') }}"
               class="px-10 py-3 text-success border border-success text-center font-poppins font-normal text-base leading-6 rounded-md flex primary-color-button sm-button-center">{{ __('See all') }}
               <i class="fa-solid fa-arrow-right w-3 h-3 mt-1.5 ml-2 arrow-image mt-255  rtl-mr-t-7" ></i></a>
         </div>
      </div>
      @if (count($pervious_events) == 0)
      <div class="font-poppins font-medium text-lg leading-4 text-black mt-5 ml-5 capitalize">
         {{ __('There are no category added yet') }}
      </div>
      @endif
      <div class="container-slider event">
         <div class="slider">
            @foreach ($pervious_events as $item)
            <div class="slide" onmouseover="showDivup('{{$item->id}}')" 
        onmouseout="hideDivup('{{$item->id}}')" >
               <a href="{{ url('event/' . $item->id . '/' . Str::slug($item->name)) }}" class="card_event">
                  <img src="{{ url('images/upload/' . $item->image) }}" class="card__image" alt="" />
                  <div class="card__overlay hover-hide" id="{{$item->id}}">
                     <div class="card__header">
                        <div class="card__header-text">
                           <h3 class="card__title"> @if (session('direction') == 'rtl') {{ $item->name_arabic }} @else {{ $item->name }}  @endif</h3>
                           <span class="card__status rtl-font-18">
                           <i class="fa fa-calendar" aria-hidden="true"></i>
                           @if (session('direction') == 'rtl')
                           @if($item->start_time ==  $item->end_time)
                           {{ Carbon\Carbon::parse($item->start_time)->locale('ar')->translatedFormat('d F Y') }}
                           @else
                           {{ Carbon\Carbon::parse($item->start_time)->locale('ar')->translatedFormat('d F Y') }} -
                           {{ Carbon\Carbon::parse($item->end_time)->locale('ar')->translatedFormat('d F Y') }}
                           @endif
                           
                           @else
                            @if($item->start_time ==  $item->end_time)
                            {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }}
                            @else
                            {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }} -
                              {{ Carbon\Carbon::parse($item->end_time)->format('d M Y') }}
                            @endif
                           
                           @endif
                           </span>
                        </div>
                        <button type="submit" class="event-button-ticket  text-success border border-success">
                        {{ __('Ticket') }}
                        </button>
                        @if (Auth::guard('appuser')->user())
                        @if (Str::contains($user->favorite, $item->id))
                        <button class="event-button-ticket "  style="margin-top: -400px;margin-left: -35px; background: rgba(225, 225, 225, 0.2);
                           border-radius: 50%;">
                        <img src="{{ url('images/heart-fill.svg') }}" alt="" class="">
                        </button>
                        @else
                        <button class="event-button-ticket "  style="margin-top: -400px;margin-left: -35px;background: rgba(225, 225, 225, 0.2);
                           border-radius: 50%;">
                        <img src="{{ url('images/heart.svg') }}" alt="" class="">
                        </button>
                        @endif
                        @endif
                     </div>
                     <p class="card__description">  
                        {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }} -
                        {{ Carbon\Carbon::parse($item->end_time)->format('d M Y') }}
                     </p>
                  </div>
               </a>
            </div>
            <!-- @if ($loop->iteration == 4)
            @break
            @endif -->
            @endforeach
         </div>
         <script type="text/javascript">
  function showDivup(id) {
            document.getElementById(id).style.display = 'block';
        }

        function hideDivup(id) {
          document.getElementById(id).style.display = 'none';
          
        }
</script>
         <!-- <div class="control-slider">
            <i class="fas fa-chevron-left opacity-100" id="arrow-left"></i>
            <i class="fas fa-chevron-right opacity-100" id="arrow-right"></i>
         </div> -->
      </div>   
      <div
         class=" grid gap-x-7 3xl:grid-cols-4 xl:grid-cols-4 xlg:grid-cols-2 xxmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 ">
   
         <div class="sm:hidden">
            <a type="button" href="{{ url('/events/previous') }}"
               class="px-10 py-3 text-blue border border-blue text-center font-poppins font-normal text-base leading-6 rounded-md flex primary-color-button sm-button-center">{{ __('See all') }}
               <i class="fa-solid fa-arrow-right w-3 h-3 mt-1.5 ml-2 arrow-image mt-255  rtl-mr-t-7" ></i></a>
         </div>
      </div>
      {{-- Latest blogs --}}
      <!-- <div
         class="absolute  blur-3xl opacity-10 s:bg-opacity-10 3xl:w-[370px] 3xl:h-[370px] 2xl:w-[300px] 2xl:h-[300px] 1xl:w-[300px] xmd:w-[300px] xmd:h-[300px] sm:w-[200px] sm:h-[300px] xxsm:w-[300px] xxsm:h-[300px] rounded-full -mt-5 2xl:-ml-20 1xl:-ml-20 sm:ml-2 xxsm:-ml-7">
      </div>
      <div class="flex sm:flex-wrap msm:flex-wrap xsm:flex-wrap xxsm:flex-wrap justify-between pt-20 mx-5 z-0 sm-remove-mx-5 sm-pt-0-rem">
         <div>
            <p
               class="font-poppins font-semibold md:text-5xl xxsm:text-2xl xsm:text-2xl sm:text-2xl text-primary-color leading-10">
               {{ __('Latest Blogs') }}
            </p>
         </div>
         <div class=" xxsm:max-sm:hidden">
            <a type="button" href="{{ url('/all-blogs') }}"
               class="px-10 py-3 text-warning border border-warning text-center font-poppins font-normal text-base leading-6 rounded-md flex primary-color-button sm-button-center">{{ __('See all') }}
               <i class="fa-solid fa-arrow-right w-3 h-3 mt-1.5 ml-2 arrow-image mt-255  rtl-mr-t-7" ></i></a>
         </div>
      </div>
      @if (count($blog) == 0)
      <div class="font-poppins font-medium text-lg leading-4 text-black mt-5 ml-5 capitalize">
         {{ __('There are no blog added yet') }}
      </div>
      @endif -->
      
      <!-- <div class="grid xl:grid-cols-1 gap-5 lg:grid-cols-1 xxsm:grid-cols-1 pb-5 blogs-div" >
         @foreach ($blog as $item)
         <div
            class="flex 3xl:flex-row 2xl:flex-nowrap 1xl:flex-nowrap xl:flex-nowrap xlg:flex-wrap flex-wrap justify-between 3xl:pt-5 xl:pt-5 gap-x-5 xl:w-full xlg:w-full sm-rm-max-height" style="max-height:250px;min-height:250px;">
            <div
               class="w-full shadow-lg p-5 rounded-lg flex 3xl:flex-nowrap md:flex-nowrap sm:flex-wrap msm:flex-wrap xsm:flex-wrap xxsm:flex-wrap bg-white xlg:w-full xmd:w-full 3xl:mb-0 xl:mb-0 xlg:mb-5 xxsm:mb-5" style="padding:20px; border: #a6a6a6 solid 1px;">
               <div
                  class="relative 3xl:w-[40%] xl:w-[40%] xlg:w-[40%] xmd:w-[60%] xxmd:w-[30%] sm:w-[60%]">
                  <img src="{{ asset('images/upload/' . $item->image) }}" alt=""
                     class="rounded-lg h-56 w-full cover-fit" style="height: 100%;" >
                  @if (Auth::guard('appuser')->user())
                  <div
                     class="shadow-lg rounded-lg w-10 h-10 text-center absolute bg-white top-3 left-3">
                     @if (Str::contains($user->favorite_blog, $item->id))
                     <a href="javascript:void(0);" class="like"
                        onclick="addFavorite('{{ $item->id }}','{{ 'blog' }}')"><img
                        src="{{ url('images/heart-fill.svg') }}" alt=""
                        class="object-cover bg-cover fillLike bg-white-light p-2 rounded-lg"></a>
                     @else
                     <a href="javascript:void(0);" class="like"
                        onclick="addFavorite('{{ $item->id }}','{{ 'blog' }}')"><img
                        src="{{ url('images/heart.svg') }}" alt=""
                        class="object-cover bg-cover fillLike bg-white-light p-2 rounded-lg"></a>
                     @endif
                  </div>
                  @endif
               </div>
               <div class="ml-4 3xl:w-full xl:w-full xlg:w-full xmd:w-full xxmd:w-[80%] sm:w-1/2">
                  <div class="flex justify-between">
                     <button
                        class="px-3 py-1 xxsm:max-md:mt-5 text-success bg-success-light rounded-full rtl-mr-20 sm-rtl-mr-0 sm-margin-category-button">{{ $item->category->name }}</button>
                     <p class="font-poppins font-medium text-base  leading-6 text-gray sm-mt-10 30">
                        
                        @if (session('direction') == 'rtl')
                           
                           {{ Carbon\Carbon::parse($item->created_at)->locale('ar')->translatedFormat('d M Y') }}
                           @else
                           {{ Carbon\Carbon::parse($item->created_at)->format('d M Y') }} 
                           @endif
                     </p>
                  </div>
                  <div class="">
                  <p class="font-popping font-bold capitalize text-xl  leading-8 text-left pt-3 ">
                     {{ $item->title }}
                  </p>
                  <p class="font-popping font-normal text-base !leading-7 text-gray text-left ">
                     {{ \Illuminate\Support\Str::limit(strip_tags($item->description), 150, $end = '...') }}
                  </p>
                  <a type="button"
                     href="{{ url('/blog-detail/' . $item->id . '/' . str::slug($item->title)) }}"
                     class="mt-5 text-primary font-poppins font-medium text-base leading-7 flex pt-1 justify-end">{{ __('Read More') }}
                  <i class="fa-solid fa-arrow-right w-3 h-3 mt-1.5 ml-2 arrow-image  rtl-mr-t-7" ></i>
                  </a>
               </div>
               </div>
            </div>
         </div>
         @if ($loop->iteration == 4)
         @break
         @endif
         @endforeach
      </div> -->
      @if ($showLinkBanner->show_link_banner == 1)
      <div class="w-full h-full bg-gradient-to-r from-gradient-bg1 to-gradient-bg2">
         <div class="w-full bg-cover bg-no-repeat"
            style="background-image: url({{ asset('/images/bg-img.png') }});height: 548px;">
            <div id="success_msg"
               class="w-full bg-[#4fd69c] text-white font-semibold text-center text-lg tracking-wide"></div>
            <div
               class="xxxxl:pl-[300px] xxxxl:pr-[300px] xxxxl:pt-[116px] s:pl-[10px] s:pr-[10px] s:pt-[50px] xxl:pl-[100px] xxl:pr-[100px] xxxl:pr-[150px] xxxl:pl-[150px] p-10 ml-28">
               <div class="xxxxl:w-[658px] lg:w-[700px] pt-20">
                  <h1
                     class="text-dark-gray xxxxl:text-6xl mb-7 s:text-4xl font-poppins font-semibold md:text-5xl xxsm:text-2xl xsm:text-2xl sm:text-2xl leading-10">
                     {{ __('Book Your Favorite Events From Anywhere') }} 
                  </h1>
                  <p class="font-poppins font-medium text-[#8896AB] text-base leading-8 mb-10">
                     {{ __('Mobile Apps are available for Android & iOS both.') }} <br>
                     {{ __(' Please Download & Start Booking Now!') }}
                  </p>
                  <a href="{{$showLinkBanner->googleplay_link}}" target="_blank"><button
                     class="w-48 h-14 text-white font-poppins font-semibold text-lg rounded-[6px]"><img
                     src="{{ asset('images/AppStore.svg') }}" alt=""></button></a>
                  <a href="{{$showLinkBanner->appstore_link}}" target="_blank"><button
                     class="w-48 h-14 text-white font-poppins font-semibold text-lg rounded-[6px]"><img
                     src="{{ asset('images/GooglePlay.svg') }}" alt=""></button></a>
               </div>
            </div>
         </div>
      </div>
      @endif
      <div style="height:30px" class="sm-hidden">
      </div>
      <!-- <div class="w-full h-full bg-gradient-to-r from-gradient-bg1 to-gradient-bg2" style="margin-top:90px;">
         <div class="w-full bg-cover bg-no-repeat"
            style="background-image: url({{ asset('/images/bg-img.png') }});height: 548px;">
            <div id="success_msg"
               class="w-full bg-[#4fd69c] text-white font-semibold text-center text-lg tracking-wide"></div>
            <div
               class="xxxxl:pl-[300px] xxxxl:pr-[300px] xxxxl:pt-[116px] s:pl-[10px] s:pr-[10px] s:pt-[50px] xxl:pl-[100px] xxl:pr-[100px] xxxl:pr-[150px] xxxl:pl-[150px] p-10 ml-28">
               <div class="xxxxl:w-[658px] lg:w-[700px] pt-20">
                  <h1
                     class="text-dark-gray xxxxl:text-6xl mb-7 s:text-4xl font-poppins font-semibold md:text-5xl xxsm:text-2xl xsm:text-2xl sm:text-2xl leading-10">
                     {{ __('Book Your Favorite Events From Anywhere') }} 
                  </h1>
                  <p class="font-poppins font-medium text-[#8896AB] text-base leading-8 mb-10">
                     {{ __('Mobile Apps are available for Android & iOS both.') }} <br>
                     {{ __(' Please Download & Start Booking Now!') }}
                  </p>
                  <a href="{{$showLinkBanner->googleplay_link}}" target="_blank"><button
                     class="w-48 h-14 text-white font-poppins font-semibold text-lg rounded-[6px]"><img
                     src="{{ asset('images/AppStore.svg') }}" alt=""></button></a>
                  <a href="{{$showLinkBanner->appstore_link}}" target="_blank"><button
                     class="w-48 h-14 text-white font-poppins font-semibold text-lg rounded-[6px]"><img
                     src="{{ asset('images/GooglePlay.svg') }}" alt=""></button></a>
               </div>
            </div>
         </div>
      </div> -->
      
    <!--   <div class="sm:hidden sm-pb-30">
         <a type="button" href="{{ url('/all-blogs') }}"
            class="px-10 py-3 text-warning border border-warning text-center font-poppins font-normal text-base leading-6 rounded-md flex primary-color-button sm-button-center">{{ __('See all') }}
            <i class="fa-solid fa-arrow-right w-3 h-3 mt-1.5 ml-2 arrow-image mt-255  rtl-mr-t-7" ></i></a></a>
      </div> -->
   </div>
   @if (session('direction') == 'rtl')
   <div class="book-ticket container">
         <div class="info">
           
            
                <p class="info-line-one text-color-primary"  style="font-size: 26px;" > احجز تذكرتك بسهولة  </p>
                                 
                                
                                
                
            </div>
            <br>
            <div class="ticket-book-limti">
            <div class="flex grid xl:grid-cols-3  lg:grid-cols-2 xxsm:grid-cols-2  sm-ml-0 sm-mt-0 first-div-booking-rtl" >
               <div
                  class="card-steps grid gap-x-7 1xl:grid-cols-3 xl:grid-cols-3 xlg:grid-cols-3 xmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 z-30 relative" >
                  <div class="slide-book-ticekt">
                     <div class="book-ticket-card-overlay">
                        <img src="{{asset('/images/ticket-book.png')}}" class="book-image rtl-mr-50" >
                     <!-- <i class="fa fa-search-plus book-ticket-fa" aria-hidden="true" style="color:#6363e8;"></i> -->
                     <span class="book-ticket-text rtl-font-size-20">اختر الفعالية وقم بالحجز </span>
                     </div>
                  </div>
               </div>

               <div
                  class="card-steps grid gap-x-7 1xl:grid-cols-3 xl:grid-cols-3 xlg:grid-cols-3 xmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 z-30 relative" >
                  <div class="slide-book-ticekt">
                     <div class="book-ticket-card-overlay">
                        <img src="{{asset('/images/payment-book.png')}}" class="book-image rtl-mr-30" >
                     
                     <span class="book-ticket-text rtl-font-size-20">ادفع بسهولة وامان </span>
                     </div>
                  </div>
               </div>

               <div
                  class="card-steps grid gap-x-7 1xl:grid-cols-3 xl:grid-cols-3 xlg:grid-cols-3 xmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 z-30 relative" >
                  <div class="slide-book-ticekt">
                     <div class="book-ticket-card-overlay">
                     <img src="{{asset('/images/retreat.png')}}" class="book-image  rtl-mr-30" >
                     <!-- <i class="fa fa-ticket book-ticket-fa" aria-hidden="true" style="color:#caca64;"></i> -->
                     <span class="book-ticket-text rtl-font-size-20">احصل على تذكرتك</span>
                     </div>
                  </div>
               </div>
               <!-- <div
                  class="card-steps grid gap-x-7 1xl:grid-cols-3 xl:grid-cols-3 xlg:grid-cols-3 xmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 z-30 relative" >
                  <div class="slide-book-ticekt">
                     <div class="book-ticket-card-overlay">
                     <i class="fa fa-sign-in book-ticket-fa" aria-hidden="true" style="color:#6363e8;"></i>
                     <span class="book-ticket-text">Join</span>
                     </div>
                  </div>
               </div> -->
               </div>
            </div>
         </div>
</div>
@else
<div class="book-ticket container">
         <div class="info">
           
            <p class="info-line-one text-color-primary info-header" > How do I order my ticket?</p>
            <p class="info-line-one text-color-primary"  style="font-size: 20px;" > 100% safe.. .ask us and stay comfortable </p>
                 
            </div>
            <br>
            <div class="ticket-book-limti">
            <div class="flex grid xl:grid-cols-3  lg:grid-cols-2 xxsm:grid-cols-2  sm-ml-0 sm-mt-0 first-div-booking" >
               <div
                  class="card-steps grid gap-x-7 1xl:grid-cols-3 xl:grid-cols-3 xlg:grid-cols-3 xmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 z-30 relative" >
                  <div class="slide-book-ticekt">
                     <div class="book-ticket-card-overlay">
                        <img src="{{asset('/images/ticket-book.png')}}" class="book-image" >
                     <!-- <i class="fa fa-search-plus book-ticket-fa" aria-hidden="true" style="color:#6363e8;"></i> -->
                     <span class="book-ticket-text rtl-font-size-20">Pick Seats</span>
                     </div>
                  </div>
               </div>

               <div
                  class="card-steps grid gap-x-7 1xl:grid-cols-3 xl:grid-cols-3 xlg:grid-cols-3 xmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 z-30 relative" >
                  <div class="slide-book-ticekt">
                     <div class="book-ticket-card-overlay">
                        <img src="{{asset('/images/payment-book.png')}}" class="book-image" >
                     
                     <span class="book-ticket-text rtl-font-size-20">Pay</span>
                     </div>
                  </div>
               </div>

               <div
                  class="card-steps grid gap-x-7 1xl:grid-cols-3 xl:grid-cols-3 xlg:grid-cols-3 xmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 z-30 relative" >
                  <div class="slide-book-ticekt">
                     <div class="book-ticket-card-overlay">
                     <img src="{{asset('/images/retreat.png')}}" class="book-image" >
                     <!-- <i class="fa fa-ticket book-ticket-fa" aria-hidden="true" style="color:#caca64;"></i> -->
                     <span class="book-ticket-text rtl-font-size-20">Ticket</span>
                     </div>
                  </div>
               </div>
               <!-- <div
                  class="card-steps grid gap-x-7 1xl:grid-cols-3 xl:grid-cols-3 xlg:grid-cols-3 xmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 z-30 relative" >
                  <div class="slide-book-ticekt">
                     <div class="book-ticket-card-overlay">
                     <i class="fa fa-sign-in book-ticket-fa" aria-hidden="true" style="color:#6363e8;"></i>
                     <span class="book-ticket-text">Join</span>
                     </div>
                  </div>
               </div> -->
               </div>
            </div>
         </div>
</div>

 @endif

@endsection
