@extends('front.master', ['activePage' => 'event'])
@section('title', __('Event Details'))
@php
$gmapkey = \App\Models\Setting::find(1)->map_key;
@endphp
@section('content')


@php
$minPrice = $data->paid_ticket->min('price');
@endphp

<div class="container mt-32 ">
   <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
      <div class="col-span-12 md:col-span-8 ">
         <h2 class="font-medium">
            @if (session('direction') == 'rtl')
            {{ $data->name_arabic }}
            @else
            {{ $data->name }}
            @endif
         </h2>
         <div class="">
            <p class="flex gap-2  mt-4">
               <svg width="12" height="17" viewBox="0 0 12 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                     d="M8.5 6C8.5 7.40625 7.375 8.5 6 8.5C4.59375 8.5 3.5 7.40625 3.5 6C3.5 4.625 4.59375 3.5 6 3.5C7.375 3.5 8.5 4.625 8.5 6ZM6 7.5C6.8125 7.5 7.5 6.84375 7.5 6C7.5 5.1875 6.8125 4.5 6 4.5C5.15625 4.5 4.5 5.1875 4.5 6C4.5 6.84375 5.15625 7.5 6 7.5ZM12 6C12 8.75 8.34375 13.5938 6.71875 15.625C6.34375 16.0938 5.625 16.0938 5.25 15.625C3.625 13.5938 0 8.75 0 6C0 2.6875 2.65625 0 6 0C9.3125 0 12 2.6875 12 6ZM6 1C3.21875 1 1 3.25 1 6C1 6.5 1.15625 7.15625 1.5 8C1.84375 8.8125 2.3125 9.6875 2.875 10.5625C3.9375 12.2812 5.1875 13.9375 6 14.9375C6.78125 13.9375 8.03125 12.2812 9.09375 10.5625C9.65625 9.6875 10.125 8.8125 10.4688 8C10.8125 7.15625 11 6.5 11 6C11 3.25 8.75 1 6 1Z"
                     fill="#A986BF" />
               </svg>
               @if ($data->type == 'online')
               <span> {{ __('Online Event') }}</span>
               @else
               <a target="_blank" href="@if($data->address_url) {{$data->address_url}} @else # @endif"><span class="h5">{{ $data->address }}</span></a>
               @endif
            </p>
            <p class="flex gap-2  mt-4">
               <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                     d="M3.5 0C3.75 0 4 0.25 4 0.5V2H10V0.5C10 0.25 10.2188 0 10.5 0C10.75 0 11 0.25 11 0.5V2H12C13.0938 2 14 2.90625 14 4V6.03125C13.8125 6.03125 13.6562 6 13.5 6C13.3125 6 13.1562 6.03125 13 6.03125V6H1V14C1 14.5625 1.4375 15 2 15H9.25C9.5625 15.4062 9.90625 15.7188 10.3125 16H2C0.875 16 0 15.125 0 14V4C0 2.90625 0.875 2 2 2H3V0.5C3 0.25 3.21875 0 3.5 0ZM12 3H2C1.4375 3 1 3.46875 1 4V5H13V4C13 3.46875 12.5312 3 12 3ZM13.4688 9C13.75 9 13.9688 9.25 13.9688 9.5V11H15C15.25 11 15.5 11.25 15.5 11.5C15.5 11.7812 15.25 12 15 12H13.4688C13.2188 12 12.9688 11.7812 12.9688 11.5V9.5C12.9688 9.25 13.2188 9 13.4688 9ZM9 11.5C9 9.03125 11 7 13.5 7C15.9688 7 18 9.03125 18 11.5C18 14 15.9688 16 13.5 16C11 16 9 14 9 11.5ZM13.5 15C15.4062 15 17 13.4375 17 11.5C17 9.59375 15.4062 8 13.5 8C11.5625 8 10 9.59375 10 11.5C10 13.4375 11.5625 15 13.5 15Z"
                     fill="#A986BF" />
               </svg>
               <span class="h5">
                  @if($data->is_repeat == 1 )
                  @if (session('direction') == 'rtl')
                  {{ Carbon\Carbon::now()->locale('ar')->translatedFormat('d M Y') }}
                  {{ Carbon\Carbon::parse($data->start_time)->locale('ar')->translatedFormat('h:i A') }}
                  @else
                  {{ Carbon\Carbon::now()->format('d M Y') }}
                  {{ Carbon\Carbon::parse($data->start_time)->translatedFormat('h:i A') }}
                  @endif

                  @else
                  {{ Carbon\Carbon::parse($data->end_time)->format('d') }}
                  @if (session('direction') == 'rtl')
                  {{ Carbon\Carbon::parse($data->end_time)->locale('ar')->translatedFormat('M Y') }}
                  {{ Carbon\Carbon::parse($data->end_time)->locale('ar')->translatedFormat('h:i A') }}
                  @else
                  {{ Carbon\Carbon::parse($data->end_time)->format('M Y') }}
                  {{ Carbon\Carbon::parse($data->end_time)->translatedFormat('h:i A') }}
                  @endif
                  @endif
               </span>
            </p>
         </div>
         <div class="mt-4 flex gap-2 flex-wrap">
            @php
            $tagsString = $data->tags;
            $tagsArray = array_filter(explode(',', $tagsString));
            $data->tags = $tagsArray;
            @endphp

            @if(!empty($data->tags))
            @foreach($data->tags as $tag)
            <div class="flex gap-1 items-center f-bri h7 bg-primary_color_8 bg-opacity-50 py-1 px-3 rounded-5xl w-fit">
               <svg width="12" height="11" viewBox="0 0 12 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M0.75 1.375C0.75 0.765625 1.24219 0.25 1.875 0.273438H5.36719C5.76562 0.273438 6.14062 0.414062 6.42188 0.695312L10.5469 4.82031C11.1328 5.40625 11.1328 6.36719 10.5469 6.95312L7.42969 10.0703C6.84375 10.6562 5.88281 10.6562 5.29688 10.0703L1.17188 5.94531C0.890625 5.66406 0.75 5.28906 0.75 4.89062V1.375ZM3.375 3.625C3.77344 3.625 4.125 3.29688 4.125 2.875C4.125 2.47656 3.77344 2.125 3.375 2.125C2.95312 2.125 2.625 2.47656 2.625 2.875C2.625 3.29688 2.95312 3.625 3.375 3.625Z" fill="#723995" />
               </svg>
               {{ $tag }}
            </div>
            @endforeach
            @endif

         </div>
      </div>
      <div class="col-span-12 md:col-span-4 bg-light rounded-xl  md:mt-0 mt-6 p-22-32">
         <div class="flex flex-col items-center justify-center h-full	py-4">
            <h5 class="text-gray_6">Tickets starting at</h5>
            <span class="h4 text-dark font-medium">{{ __($currency) }} {{$minPrice}}</span>
            <a href="#tickets_section" class="mt-4 rounded-full bg-primary_color_8 p-12-24 flex items-center gap-2 cursor-pointer btn-hover-primary">
               <span class="z-20">{{ __('Get Tickets') }}</span>
               <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                     d="M9.90625 1.24609L16.0938 7.15234C16.2695 7.32812 16.375 7.53906 16.375 7.78516C16.375 7.99609 16.2695 8.20703 16.0938 8.38281L9.90625 14.2891C9.58984 14.6055 9.02734 14.6055 8.71094 14.2539C8.39453 13.9375 8.39453 13.375 8.74609 13.0586L13.4219 8.62891H1.46875C0.976562 8.62891 0.625 8.24219 0.625 7.78516C0.625 7.29297 0.976562 6.94141 1.46875 6.94141H13.4219L8.74609 2.47656C8.39453 2.16016 8.39453 1.59766 8.71094 1.28125C9.02734 0.929688 9.55469 0.929688 9.90625 1.24609Z"
                     fill="#A986BF" />
               </svg>
            </a>
         </div>
      </div>
   </div>
</div>

<div class="container mt-16">
   @php
   $data->gallery = explode(',', $data->gallery);
   @endphp
   <div class="swiper event_details_swiper">
      <div class="swiper-wrapper">
         @if(is_array($data->gallery) && count($data->gallery) > 1)
         @foreach($data->gallery as $image)
         <div class="swiper-slide">
            <img src="{{ url('images/upload/' . $image) }}" alt="">
         </div>
         @endforeach
         @else
         @for ($i = 0; $i < 4; $i++)
            <div class="swiper-slide">
            <img src="{{ url('images/upload/' . $data->image) }}" alt="Default Image">
      </div>
      @endfor
      @endif
   </div>
   <!-- <div class="swiper-pagination"></div> -->
</div>
</div>


<div class="container mt-32">
   <div class="grid grid-cols-1 sm:grid-cols-2  gap-4">
      <div class="bg-light bg-opacity-5 rounded-2xl border border-primary_color_o10_1  p-32-24">
         <div class="flex gap-2 items-center">
            <svg width="26" height="21" viewBox="0 0 26 21" fill="none" xmlns="http://www.w3.org/2000/svg">
               <path
                  d="M11.2031 12.375C14.9531 12.375 18 15.4219 18 19.1719C18 19.9141 17.375 20.5 16.6328 20.5H1.82812C1.08594 20.5 0.5 19.9141 0.5 19.1719C0.5 15.4219 3.50781 12.375 7.25781 12.375H11.2031ZM16.6328 19.25C16.6719 19.25 16.75 19.2109 16.75 19.1719C16.75 16.125 14.25 13.625 11.2031 13.625H7.25781C4.21094 13.625 1.75 16.125 1.75 19.1719C1.75 19.2109 1.78906 19.25 1.82812 19.25H16.6328ZM9.25 10.5C6.47656 10.5 4.25 8.27344 4.25 5.5C4.25 2.76562 6.47656 0.5 9.25 0.5C11.9844 0.5 14.25 2.76562 14.25 5.5C14.25 8.27344 11.9844 10.5 9.25 10.5ZM9.25 1.75C7.17969 1.75 5.5 3.46875 5.5 5.5C5.5 7.57031 7.17969 9.25 9.25 9.25C11.2812 9.25 13 7.57031 13 5.5C13 3.46875 11.2812 1.75 9.25 1.75ZM15.1484 9.91406C14.875 9.75781 14.7578 9.36719 14.9531 9.09375C15.1094 8.78125 15.5 8.66406 15.8125 8.85938C16.2812 9.13281 16.7891 9.25 17.375 9.25C19.0938 9.25 20.5 7.88281 20.5 6.125C20.5 4.40625 19.0938 3 17.375 3C16.9844 3 16.6328 3.07812 16.3203 3.19531C16.0078 3.3125 15.6172 3.15625 15.5 2.80469C15.3828 2.49219 15.5781 2.14062 15.8906 2.02344C16.3594 1.86719 16.8672 1.75 17.375 1.75C19.7578 1.75 21.75 3.74219 21.75 6.125C21.75 8.54688 19.7578 10.5 17.375 10.5C16.5938 10.5 15.8125 10.3047 15.1484 9.91406ZM19.6406 13C22.8828 13 25.5 15.6172 25.5 18.8594C25.5 19.7578 24.7188 20.5 23.8203 20.5H19.875C19.5234 20.5 19.25 20.2266 19.25 19.875C19.25 19.5625 19.5234 19.25 19.875 19.25H23.8203C24.0547 19.25 24.25 19.0938 24.25 18.8594C24.25 16.3203 22.1797 14.25 19.6406 14.25H18C17.6484 14.25 17.375 13.9766 17.375 13.625C17.375 13.3125 17.6484 13 18 13H19.6406Z"
                  fill="#A986BF" />
            </svg>
            <h3 class="text-primary_color_6 font-medium">Audience</h3>
         </div>
         <p class="h4 mt-4">Suitable for audience<br> aged 12 and above</p>
      </div>
      <div class="bg-light bg-opacity-5 rounded-2xl border border-primary_color_o10_1  p-32-24">
         <div class="flex gap-2 items-center">
            <svg width="16" height="21" viewBox="0 0 16 21" fill="none" xmlns="http://www.w3.org/2000/svg">
               <path
                  d="M11.125 8C11.125 9.75781 9.71875 11.125 8 11.125C6.24219 11.125 4.875 9.75781 4.875 8C4.875 6.28125 6.24219 4.875 8 4.875C9.71875 4.875 11.125 6.28125 11.125 8ZM8 9.875C9.01562 9.875 9.875 9.05469 9.875 8C9.875 6.98438 9.01562 6.125 8 6.125C6.94531 6.125 6.125 6.98438 6.125 8C6.125 9.05469 6.94531 9.875 8 9.875ZM15.5 8C15.5 11.4375 10.9297 17.4922 8.89844 20.0312C8.42969 20.6172 7.53125 20.6172 7.0625 20.0312C5.03125 17.4922 0.5 11.4375 0.5 8C0.5 3.85938 3.82031 0.5 8 0.5C12.1406 0.5 15.5 3.85938 15.5 8ZM8 1.75C4.52344 1.75 1.75 4.5625 1.75 8C1.75 8.625 1.94531 9.44531 2.375 10.5C2.80469 11.5156 3.39062 12.6094 4.09375 13.7031C5.42188 15.8516 6.98438 17.9219 8 19.1719C8.97656 17.9219 10.5391 15.8516 11.8672 13.7031C12.5703 12.6094 13.1562 11.5156 13.5859 10.5C14.0156 9.44531 14.25 8.625 14.25 8C14.25 4.5625 11.4375 1.75 8 1.75Z"
                  fill="#A986BF" />
            </svg>
            <h3 class="text-primary_color_6 font-medium">{{ __('Location') }}</h3>
         </div>
         <p class="h4 mt-4 font-light"> @if (session('direction') == 'rtl')
            {{ $data->name_arabic }}
            @else
            {{ $data->name }}
            @endif
         </p>
         <a class="flex items-center gap-2 mt-3 py-1 cursor-pointer " onclick="openModal()">
            <span class="h4" id="openMapBtn">Show map</span>
            <svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
               <path
                  d="M7.28125 8.71875L1.28125 2.71875C0.875 2.34375 0.875 1.6875 1.28125 1.3125C1.65625 0.90625 2.3125 0.90625 2.6875 1.3125L8 6.59375L13.2812 1.3125C13.6562 0.90625 14.3125 0.90625 14.6875 1.3125C15.0938 1.6875 15.0938 2.34375 14.6875 2.71875L8.6875 8.71875C8.3125 9.125 7.65625 9.125 7.28125 8.71875Z"
                  fill="#E0D3E8" />
            </svg>
         </a>
         <div id="mapModal" class="modal" style="display: none;">
            <div class="modal-content">
               <span class="closeMap"><i class="fa-regular fa-circle-xmark fa-2xl my-6"></i></span>
               <div id="map" style="height: 400px; width: 700px; "></div>
            </div>
            <a class="py-3" target="_blank" href="https://maps.google.com/maps?daddr={{$data->lat}},{{$data->lang}}&amp;ll=">
               <i class="fa-solid fa-location-arrow mx-2 "></i>Open in Google Maps</a>
         </div>
      </div>
   </div>
   <div class="bg-light bg-opacity-5 rounded-2xl border border-primary_color_o10_1  p-32-24 mt-8">
      <h3 class="text-primary_color_6 font-medium">{{ __('About Event') }}</h3>
      <p class="h4 mt-4 paragraph-3 max-h-96 overflow-x-auto ">
         @if (session('direction') == 'rtl')
         {!! strip_tags($data->description_arabic) !!}
         @else
         {!! $data->description !!}
         @endif
      </p>
      <span class="flex items-center gap-2 mt-3 py-1 cursor-pointer">
         <span class="h4 showMore"><span class="more">Show more ...</span> <span class="less hidden">Show less ...</span></span>
      </span>
   </div>
   <div class="bg-light bg-opacity-5 rounded-2xl border border-primary_color_o10_1  p-32-24 mt-8">
      <div class="flex items-center   justify-between cursor-pointer" onclick="toggleAccordion('Terms_conditions')">
         <h3 class="text-primary_color_6 font-medium">Terms and conditions</h3>
         <svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
               d="M7.28125 8.71875L1.28125 2.71875C0.875 2.34375 0.875 1.6875 1.28125 1.3125C1.65625 0.90625 2.3125 0.90625 2.6875 1.3125L8 6.59375L13.2812 1.3125C13.6562 0.90625 14.3125 0.90625 14.6875 1.3125C15.0938 1.6875 15.0938 2.34375 14.6875 2.71875L8.6875 8.71875C8.3125 9.125 7.65625 9.125 7.28125 8.71875Z"
               fill="#E0D3E8" />
         </svg>
      </div>
      <div id="Terms_conditions" class="hidden h4 mt-4">
         <p>Content for Terms and conditions.</p>
      </div>
   </div>
</div>

<div class="container mt-32" id="tickets_section">
   <div class="mb-16">
      <h2 class="font-medium">Tickets options</h2>
      <p class="h4 mt-2 text-gray_9">Choose your ticket and quantity.</p>
   </div>
</div>
@if (count($data->paid_ticket) != 0)
<form id="tickets" class="mb-32" method="GET" action="/checkout/{{$data->id}}">
   <div class="container ">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-16">
         @foreach ($data->paid_ticket as $item)
         <div class="bg-primary_color_12  rounded-2xl border border-primary_color_o25_8  p-32-24 text-center">
            <input value="{{ $item->id }}" type="hidden" name="ids" id="id-{{ $item->id }}">
            <input type="hidden" name="quantities" value="0" id="quantity-{{ $item->id }}">

            <h3 class="font-bold"> {{ $item->name }} </h3>
            <div class="text-gray_9 h6 my-4 flex flex-col">
               <span>5 Days</span>
               <span>{{ __('Ticket Sale starts onwards') }}</span>
               <span>
                  {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }} {{__('till')}}
                  {{ Carbon\Carbon::parse($item->end_time)->format('d M Y') }}
               </span>
            </div>
            <div class="f-bri">
               <span class="h4">{{ __($currency) }}</span>
               <span class="h1 font-medium"> {{ $item->price }}</span>
            </div>
            <div class="flex mt-4 items-center justify-center gap-4">
               <button type="button" class="disable decrement opacity-25" data-ticket-price="{{ $item->price }}" data-ticket-id="{{ $item->id }}">
                  <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <rect x="1" y="0.5" width="31" height="31" rx="7.5" fill="#FBF9FD" stroke="#723995" />
                     <path d="M9.5 16H23.5" stroke="#723995" stroke-width="2" stroke-linecap="round" />
                  </svg>
               </button>
               <div class="count" class="f-bri h3 opacity-25">0</div>
               <button type="button" class="increment " data-ticket-price="{{ $item->price }}" data-ticket-id="{{ $item->id }}">
                  <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <rect x="1" y="0.5" width="31" height="31" rx="7.5" fill="#FBF9FD" stroke="#723995" />
                     <path d="M9.5 16H23.5" stroke="#723995" stroke-width="2" stroke-linecap="round" />
                     <path d="M16.5 9L16.5 23" stroke="#723995" stroke-width="2" stroke-linecap="round" />
                  </svg>
               </button>
            </div>
         </div>
         @endforeach
      </div>
   </div>
   <div class="container mt-16">
      <div class=" bg-light rounded-2xl   ">
         <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-12 gap-4  items-center p-22-32">
            <div class="col-span-12 md:col-span-12 lg:col-span-8 xl:col-span-6 ">
               <div class="grid grid-cols-1 md:grid-cols-3  gap-4">
                  <!-- <div class="col-span-1 md:col-span-1  border-line">
                     <h4 class="w-fit md:m-auto">
                        <span class="block text-gray_6 font-light mb-1">Type</span>
                        <span class="block text-dark font-medium">Mixed</span>
                     </h4>
                  </div> -->
                  <div class="col-span-1 md:col-span-1  border-line">
                     <h4 class="w-fit md:m-auto">
                        <span class="block text-gray_6 font-light mb-1">Quantity</span>
                        <span class="block text-dark font-medium"><span class="tickets-quantity">0</span> tickets</span>
                     </h4>
                  </div>
                  <div class="col-span-1 md:col-span-1 ">
                     <h4 class="w-fit md:m-auto">
                        <span class="block text-gray_6 font-light mb-1">Total</span>
                        <span class="block text-dark font-medium">{{ __($currency) }} <span class="tickets-price">0</span> </span>
                     </h4>
                  </div>
               </div>
            </div>
            <div class="col-span-12 md:col-span-12 lg:col-span-4 xl:col-span-6 ">
            <input type="hidden" id="ticket-ids" name="ticket_ids">
            <input type="hidden" id="ticket-quantities" name="ticket_quantities">
               <button
                  class="rounded-full bg-primary_color_8 p-12-24 flex items-center gap-2  mr-auto lg:mr-0 ml-auto btn-hover-primary">
                  <span class="z-10">Buy Tickets</span>
                  <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <path
                        d="M9.90625 1.24609L16.0938 7.15234C16.2695 7.32812 16.375 7.53906 16.375 7.78516C16.375 7.99609 16.2695 8.20703 16.0938 8.38281L9.90625 14.2891C9.58984 14.6055 9.02734 14.6055 8.71094 14.2539C8.39453 13.9375 8.39453 13.375 8.74609 13.0586L13.4219 8.62891H1.46875C0.976562 8.62891 0.625 8.24219 0.625 7.78516C0.625 7.29297 0.976562 6.94141 1.46875 6.94141H13.4219L8.74609 2.47656C8.39453 2.16016 8.39453 1.59766 8.71094 1.28125C9.02734 0.929688 9.55469 0.929688 9.90625 1.24609Z"
                        fill="#A986BF"></path>
                  </svg>
               </button>
            </div>
         </div>
      </div>
   </div>
</form>
@else
<div class="container mb-32">
   <div class="bg-primary_color_12  rounded-2xl border border-primary_color_o25_8  p-32-24 text-center">
      <h3 class="font-bold"> No tickets available. </h3>
   </div>
</div>
@endif


<div id="error-modal" class="fixed inset-0 flex items-center justify-center bg-dark bg-opacity-75 hidden z-30  close-modal">
   <div class="bg-dark bg-opacity-50 rounded-2xl border border-primary_color_o10_1  p-32-24 relative">
      <p>Please select at least one product.</p>
      <span id="close-modal" class="absolute -top-50 left-0 cursor-pointer"><i class="fa-regular fa-circle-xmark fa-2xl my-6"></i></span>
   </div>
</div>




<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script script>
   $('#tickets').submit(function(event) {
      var hasProductSelected = false;
      $('input[name^="quantities"]').each(function() {
         if ($(this).val() > 0) {
            hasProductSelected = true;
            return false;
         }
      });
      if (!hasProductSelected) {
         $('#error-modal').removeClass('hidden');
         setTimeout(function() {
            $('#error-modal').addClass('hidden');
         }, 1500);
         event.preventDefault();
      }
   });
   $('.close-modal').click(function() {
      $('#error-modal').addClass('hidden');
   });


   $(document).ready(function() {
      $('.increment').click(function() {
         var productId = $(this).data('ticket-id');
         var quantityInput = $('#quantity-' + productId);
         var currentQuantity = parseInt(quantityInput.val());
         quantityInput.val(currentQuantity + 1);
      });

      $('.decrement').click(function() {
         var productId = $(this).data('ticket-id');
         var quantityInput = $('#quantity-' + productId);
         var currentQuantity = parseInt(quantityInput.val());
         if (currentQuantity > 0) {
            quantityInput.val(currentQuantity - 1);
         }
      });
   });

   $(document).ready(function() {
      $('.increment').click(function() {
         $(this).siblings('.decrement').removeClass("disable");
         $(this).siblings('.decrement').removeClass("opacity-25");
         $(this).siblings('.count').removeClass("opacity-25");
         let countElement = $(this).siblings('.count');
         let count = parseInt(countElement.text());
         countElement.text(count + 1);

         let ticketId = $(this).data('ticket-id');
         let ticketPrice = $(this).data('ticket-price');
         handeltickets(ticketId, count + 1, ticketPrice)
      });

      $('.decrement').click(function() {
         let countElement = $(this).siblings('.count');
         let count = parseInt(countElement.text());
         if (count > 0) {
            countElement.text(count - 1);
         }
         if (count == 1) {
            $(this).addClass("opacity-25");
            $(this).addClass("disable");
            $(this).siblings('.count').addClass('opacity-25')
         }

         let ticketId = $(this).data('ticket-id');
         let ticketPrice = $(this).data('ticket-price');

         handeltickets(ticketId, count - 1, ticketPrice)
      });
   });

   let tickets = {};
let ticketsTotal = 0;
let ticketsPrice = 0;

function handeltickets(ticketId, count, ticketPrice) {
    // Update the tickets object with the new ticket info
    tickets[ticketId] = {
        quantity: count,
        price: ticketPrice
    };

    // Recalculate total quantity and price
    ticketsTotal = 0;
    ticketsPrice = 0;
    let ids = [];
    let quantities = [];

    for (let id in tickets) {
        ticketsTotal += tickets[id].quantity;
        ticketsPrice += tickets[id].quantity * tickets[id].price;

        // Collect ids and quantities
        ids.push(id);
        quantities.push(tickets[id].quantity);
    }

    // Update the hidden inputs if they exist
    let idsInput = document.getElementById('ticket-ids');
    let quantitiesInput = document.getElementById('ticket-quantities');

    if (idsInput) {
        idsInput.value = ids.join(',');
    }

    if (quantitiesInput) {
        quantitiesInput.value = quantities.join(',');
    }

    // Update the display
    let priceDisplay = document.querySelector('.tickets-price');
    let quantityDisplay = document.querySelector('.tickets-quantity');

    if (priceDisplay) {
        priceDisplay.textContent = ticketsPrice.toFixed(2);
    }

    if (quantityDisplay) {
        quantityDisplay.textContent = ticketsTotal;
    }

    // Log the results for debugging
    console.log('Total Quantity:', ticketsTotal);
    console.log('Total Price:', ticketsPrice);
}



   function buttonActive(id) {
      $(".quant-button").addClass('deactive-button')
      $(".ticket-id-" + id).removeClass('deactive-button')
      const numberInputs = document.querySelectorAll('.quantity-input');
      numberInputs.forEach(input => {
         if (!input.classList.contains('number-input-' + id)) {
            input.value = 0;
         }
      });
   }


   $(document).ready(function() {

      // required elements
      var imgPopup = $('.img-popup');
      var imgCont = $('.container__img-holder');
      var popupImage = $('.img-popup img');
      var closeBtn = $('.close-btn');

      // handle events
      imgCont.on('click', function() {
         var img_src = $(this).children('img').attr('src');
         imgPopup.children('img').attr('src', img_src);
         imgPopup.addClass('opened');
      });

      $(imgPopup, closeBtn).on('click', function() {
         imgPopup.removeClass('opened');
         imgPopup.children('img').attr('src', '');
      });

      popupImage.on('click', function(e) {
         e.stopPropagation();
      });

   });

   $(document).ready(function() {
      $('.showMore').click(function() {
         $('p').toggleClass('paragraph-3');
         $('.showMore .more').toggleClass('hidden')
         $('.showMore .less').toggleClass('hidden')
      });
   });

   function toggleAccordion(id) {
      var element = document.getElementById(id);
      element.classList.toggle('hidden');
   }

   function openModal() {
      document.getElementById('locationModal').classList.remove('hidden');
   }

   function closeModal() {
      document.getElementById('locationModal').classList.add('hidden');
   }


   var modal = document.getElementById("mapModal");

   var closeMap = document.querySelector(".closeMap");

   closeMap.onclick = function() {
      modal.style.display = "none";
   }

   window.onclick = function(event) {
      if (event.target == modal) {
         modal.style.display = "none";
      }
   }

   const lat = parseFloat("{{ $data->lat }}");
   const lng = parseFloat("{{ $data->lang }}");

   function openMapModal() {
      modal.style.display = "flex";
      var map = new google.maps.Map(document.getElementById('map'), {
         center: {
            lat: lat,
            lng: lng
         },
         zoom: 15,
      });

      var marker = new google.maps.Marker({
         position: {
            lat: lat,
            lng: lng
         },
         map: map,

      });
   }

   document.getElementById("openMapBtn").onclick = openMapModal;
</script>

<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $gmapkey }}"></script>



@endsection