@extends('frontend.master', ['activePage' => 'event'])
@section('title', __('All Events'))
@section('content')
<style>
   .control-slider
   {
   position: absolute;
   top: 22%;
   width: 105% !important;
   display: flex;
   justify-content: space-between;
   transform: translateY(-50%);
   pointer-events: none;
   z-index: 999;
   left: -1.3em;
   }
   .slide{
      height:230px !important;
   }
   .container-slider{
      padding-bottom: 0px !important;
      overflow-x: auto !important;
   }
   .slider-div-height
   {
      height: 300px;
   }
   @media (max-width: 767px) {
      .slider-div-height
   {
      height: auto;
   }  
      }
</style>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
<link rel="stylesheet" href="{{ url('/frontend/css/event-card-style.css') }}">
<link rel="stylesheet" href="{{asset('frontend/css/slider-custom.css')}}">
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<div class="pb-20 bg-scroll min-h-screen min-height-1000" style="background:linear-gradient(to top, #eae9e9, white); min-height:3000px ">
   {{-- scroll --}}
   <div
      class="mt-5 3xl:mx-52 2xl:mx-28 1xl:mx-28 xl:mx-36 xlg:mx-32 lg:mx-36 xxmd:mx-24 xmd:mx-32 md:mx-28 sm:mx-20 msm:mx-16 xsm:mx-10 xxsm:mx-5 z-10 relative">
      <div
         class="absolute bg-blue blur-3xl opacity-10 s:bg-opacity-10 3xl:w-[370px] 3xl:h-[370px] 2xl:w-[300px] 2xl:h-[300px] 1xl:w-[300px] xmd:w-[300px] xmd:h-[300px] sm:w-[200px] sm:h-[300px] xxsm:w-[300px] xxsm:h-[300px] rounded-full -mt-5 2xl:-ml-20 1xl:-ml-20 sm:ml-2 xxsm:-ml-7">
      </div>
      <div class="flex justify-start pt-5 z-10">
         <p
            class="font-poppins font-semibold md:text-5xl xxsm:text-2xl xsm:text-2xl sm:text-2xl text-blue leading-10 ">
            {{ __('Events') }}
         </p>
         &nbsp;&nbsp;
         <p
            class="font-poppins font-medium md:text-2xl xxsm:text-xl xsm:text-xl sm:text-xl text-blue leading-10 pt-3">
            ( {{ $events->count() }} )
         </p>
      </div>
      <div class="mb-4 pt-4">
         <ul class="flex flex-wrap -mb-px text-lg font-medium text-center events xmd:space-y-0 md:space-y-2 sm:space-y-2 xxsm:space-y-2"
            id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
            <li class="mr-2 ">
               <button
                  class="inline-block p-4 px-6 py-3 rounded-md z-20 font-poppins shadow-md focus:outline-none relative"
                  id="all_events" data-tabs-target="#events" type="button" role="tab" aria-controls="events"
                  aria-selected="false">{{ __('All Events') }}</button>
            </li>
            <li class="mr-2">
               <button
                  class="inline-block z-20 px-5 py-3 rounded-md font-poppins shadow-md focus:outline-none relative"
                  id="online_events" data-tabs-target="#online" type="button" role="tab"
                  aria-controls="online"
                  aria-selected="false">{{ __('Online Events') }}({{ $onlinecount }})</button>
            </li>
            <li class="mr-2">
               <button
                  class="inline-block z-20 px-5 py-3 rounded-md font-poppins shadow-md focus:outline-none relative"
                  id="venue_events" data-tabs-target="#venue" type="button" role="tab" aria-controls="venue"
                  aria-selected="false">{{ __('Venue Events') }}({{ $offlinecount }})</button>
            </li>
         </ul>
      </div>
      
      @if (count($events) == 0)
      <div class="font-poppins font-medium text-lg leading-4 text-black mt-10  capitalize">
         {{ __('There are no events added yet') }}
      </div>
      @endif
      <div id="myTabContent">
         <div class="hidden" id="events" role="tabpanel" aria-labelledby="all_events">
            <!-- slider code -->
            <!-- <div class="container-slider">
         <div class="slider">
            @foreach ($events as $item)
            <div class="slide">
               <a href="{{ url('event/' . $item->id . '/' . Str::slug($item->name)) }}" class="card_event">
                  <img src="{{ url('images/upload/' . $item->image) }}" class="card__image" alt="" />
                  <div class="card__overlay">
                     <div class="card__header">
                        <div class="card__header-text">
                           <h3 class="card__title">{{ $item->name }}</h3>
                           <span class="card__status">
                           <i class="fa fa-calendar" aria-hidden="true"></i>
                           @if (session('direction') == 'rtl')
                           {{ Carbon\Carbon::parse($item->start_time)->locale('ar')->translatedFormat('d F Y') }} -
                           {{ Carbon\Carbon::parse($item->end_time)->locale('ar')->translatedFormat('d F Y') }}
                           @else
                           {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }} -
                           {{ Carbon\Carbon::parse($item->end_time)->format('d M Y') }}
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
            @if ($loop->iteration == 4)
            @break
            @endif
            @endforeach
         </div> -->
         <!-- <div class="control-slider">
            <i class="fas fa-chevron-left opacity-100" id="arrow-left"></i>
            <i class="fas fa-chevron-right opacity-100" id="arrow-right"></i>
         </div> -->
         <div
               class="grid gap-x-7 1xl:grid-cols-3 xl:grid-cols-3 xlg:grid-cols-3 xmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 z-30 relative slider-div-height" > 
               @foreach ($events as $item)
               
               <div class="slide">
               <a href="{{ url('event/' . $item->id . '/' . Str::slug($item->name)) }}" class="card_event">
                  <img src="{{ url('images/upload/' . $item->image) }}" class="card__image" alt="" />
                  <div class="card__overlay">
                     <div class="card__header">
                        <div class="card__header-text">
                           <h3 class="card__title">{{ $item->name }}</h3>
                           <span class="card__status">
                           <i class="fa fa-calendar" aria-hidden="true"></i>
                           @if (session('direction') == 'rtl')
                           {{ Carbon\Carbon::parse($item->start_time)->locale('ar')->translatedFormat('d F Y') }} -
                           {{ Carbon\Carbon::parse($item->end_time)->locale('ar')->translatedFormat('d F Y') }}
                           @else
                           {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }} -
                           {{ Carbon\Carbon::parse($item->end_time)->format('d M Y') }}
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
               
               @endforeach
            </div>
      </div>
            <!-- <div class="container-slider">
               <div class="slider">
                  @foreach ($events as $item)
                  <div class="slide">
                     <a href="{{ url('event/' . $item->id . '/' . Str::slug($item->name)) }}" class="card_event" >
                        <img src="{{ url('images/upload/' . $item->image) }}" class="card__image" alt="" />
                        <div class="card__overlay">
                           <div class="card__header">
                              <div class="card__header-text">
                                 <h3 class="card__title">{{ $item->name }}</h3>
                                 <span class="card__status">
                                 <i class="fa fa-calendar" aria-hidden="true"></i>
                                 @if (session('direction') == 'rtl')
                                 {{ Carbon\Carbon::parse($item->start_time)->locale('ar')->translatedFormat('d F Y') }} -
                                 {{ Carbon\Carbon::parse($item->end_time)->locale('ar')->translatedFormat('d F Y') }}
                                 @else
                                 {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }} -
                                 {{ Carbon\Carbon::parse($item->end_time)->format('d M Y') }}
                                 @endif
                                 </span>
                              </div>
                              <button type="submit" class="event-button-ticket  text-success border border-success">
                              {{ __('Ticket') }}
                              </button>
                              @if (Auth::guard('appuser')->user())
                              @if (Str::contains($user->favorite, $item->id))
                              <button class="event-button-ticket like-button"  >
                              <img src="{{ url('images/heart-fill.svg') }}" alt="" class="">
                              </button>
                              @else
                              <button class="event-button-ticket like-button"  >
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
                  @endforeach
               </div>
               <div class="control-slider" style="top: 60%;">
                  <i class="fas fa-chevron-left opacity-100" id="arrow-left"></i>
                  <i class="fas fa-chevron-right opacity-100" id="arrow-right"></i>
               </div>
            </div> -->
            <!-- slider code -->
         </div>
         <div class="hidden" id="online" role="tabpanel" aria-labelledby="online_events" >
            <div
               class="grid gap-x-7 1xl:grid-cols-3 xl:grid-cols-3 xlg:grid-cols-3 xmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 z-30 relative" style="height:300px"> 
               @foreach ($events as $item)
               @if ($item->type == 'online')
               <div class="slide">
               <a href="{{ url('event/' . $item->id . '/' . Str::slug($item->name)) }}" class="card_event">
                  <img src="{{ url('images/upload/' . $item->image) }}" class="card__image" alt="" />
                  <div class="card__overlay">
                     <div class="card__header">
                        <div class="card__header-text">
                           <h3 class="card__title">{{ $item->name }}</h3>
                           <span class="card__status">
                           <i class="fa fa-calendar" aria-hidden="true"></i>
                           @if (session('direction') == 'rtl')
                           {{ Carbon\Carbon::parse($item->start_time)->locale('ar')->translatedFormat('d F Y') }} -
                           {{ Carbon\Carbon::parse($item->end_time)->locale('ar')->translatedFormat('d F Y') }}
                           @else
                           {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }} -
                           {{ Carbon\Carbon::parse($item->end_time)->format('d M Y') }}
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
               @endif
               @endforeach
            </div>
         </div>
         <div class="hidden" id="venue" role="tabpanel" aria-labelledby="venue_events" style="margin-bottom:300px;">
            <div
               class="grid gap-x-7 1xl:grid-cols-3 xl:grid-cols-3 xlg:grid-cols-3 xmd:grid-cols-2 xxmd:gap-y-7 xmd:gap-y-7 xxsm:gap-y-7 sm:grid-cols-1 sm:gap-y-7 msm:grid-cols-1 xxsm:grid-cols-1 justify-between pt-10 z-30 relative" style="height:300px">
               @foreach ($events as $item)
               @if ($item->type == 'offline')
               <div class="slide">
               <a href="{{ url('event/' . $item->id . '/' . Str::slug($item->name)) }}" class="card_event">
                  <img src="{{ url('images/upload/' . $item->image) }}" class="card__image" alt="" />
                  <div class="card__overlay">
                     <div class="card__header">
                        <div class="card__header-text">
                           <h3 class="card__title">{{ $item->name }}</h3>
                           <span class="card__status">
                           <i class="fa fa-calendar" aria-hidden="true"></i>
                           @if (session('direction') == 'rtl')
                           {{ Carbon\Carbon::parse($item->start_time)->locale('ar')->translatedFormat('d F Y') }} -
                           {{ Carbon\Carbon::parse($item->end_time)->locale('ar')->translatedFormat('d F Y') }}
                           @else
                           {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }} -
                           {{ Carbon\Carbon::parse($item->end_time)->format('d M Y') }}
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
               @endif
               @endforeach
            </div>
         </div>
      </div>
   </div>
</div>

@endsection
