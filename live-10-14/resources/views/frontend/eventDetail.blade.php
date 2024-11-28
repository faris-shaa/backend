@extends('frontend.master', ['activePage' => 'event'])
@section('title', __('Event Details'))
@php
$gmapkey = \App\Models\Setting::find(1)->map_key;
@endphp
@section('content')
{{-- content --}}

<style type="text/css">

.time-slot-container {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  justify-content: space-between;
}

.time-slot-btn {
  flex: 1 0 45%; /* 45% width, allowing for two buttons per row */
  padding: 10px;
  border: 1px solid #733995;
  border-radius: 0.375rem;
  background-color: #fff;
  color: black;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.time-slot-btn:hover {
  background-color: #733995;
  color: #fff;
}

.time-slot-btn.active {
  background-color: #733995;
  color: white;
  border-color: #0056b3;
}

   .form-group {
  margin-bottom: 1rem; /* Space below the form group */
}

.form-group label {
  display: block; /* Labels should take up the full width */
  margin-bottom: 0.5rem; /* Space below the label */
  font-weight: 500; /* Slightly bold the label */
}

.form-group input,
.form-group select,
.form-group textarea {
  display: block;
  width: 100%; /* Ensure inputs take up the full width */
  padding: 0.375rem 0.75rem; /* Padding inside the input */
  font-size: 1rem; /* Standard text size */
  line-height: 1.5; /* Maintain line height for readability */
  color: #495057; /* Text color */
  background-color: #fff; /* Input background */
  background-clip: padding-box;
  border: 1px solid #ced4da; /* Border color */
  border-radius: 0.375rem; /* Rounded corners */
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #80bdff; /* Border color on focus */
  outline: 0; /* Remove default outline */
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Shadow on focus */
}

.modal {
  display: none;
  position: fixed;
  z-index: 1050;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  background-color: rgba(0, 0, 0, 0.5); /* Background overlay */
}

.modal.show {
  display: block;
  padding-right: 17px;
  overflow-x: hidden;
  overflow-y: auto;
}

.modal-dialog {
  position: relative;
  margin: 1.75rem auto;
  max-width: 500px;
  pointer-events: none;
}

.modal-content {
  position: relative;
  display: flex;
  flex-direction: column;
  background-color: #fff;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 0.3rem;
  outline: 0;
  pointer-events: auto;
}

.modal-header,
.modal-footer {
  display: flex;
  flex-shrink: 0;
  align-items: center;
  justify-content: space-between;
  padding: 1rem;
  border-bottom: 1px solid #e9ecef;
}

.modal-header {
  border-bottom: none;
}

.modal-footer {
  border-top: 1px solid #e9ecef;
}

.modal-title {
  margin-bottom: 0;
  line-height: 1.5;
  font-size: 1.25rem;
}

.modal-body {
  position: relative;
  flex: 1 1 auto;
  padding: 1rem;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1040;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal.fade .modal-dialog {
  transition: transform 0.3s ease-out;
}

.modal.fade .modal-dialog {
  transform: translateY(-50px);
}

.modal.show .modal-dialog {
  transform: translateY(0);
}

/* Close button */
.btn-close {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
}

.btn-close:before {
  content: "\00d7";
  font-size: 1.5rem;
  color: #000;
}

.modal-backdrop.show {
  opacity: 0.5;
}

   .paragraph-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}
/* Popup Styling */
.img-popup {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background: rgba(255, 255, 255, .5);
  display: flex;
  justify-content: center;
  align-items: center;
  display: none;
}
.time-slot
{
       display: flex;
    padding: 10px;
}

.time-button
{
     background: #22bf22;
    border-radius: 20px;
    padding: 9px;
    margin-right: 50px;
    color: white;
}
.active-time-button{
   background: green;
}
.more-text {
    display: none; /* Hide the additional text initially */
}

.img-popup img {
  max-width: 900px;
  width: 100%;
  opacity: 0;
  transform: translateY(-100px);
  -webkit-transform: translateY(-100px);
  -moz-transform: translateY(-100px);
  -ms-transform: translateY(-100px);
  -o-transform: translateY(-100px);
}

.close-btn {
  width: 35px;
  height: 30px;
  display: flex;
  justify-content: center;
  flex-direction: column;
  position: absolute;
  top: 20px;
  right: 20px;
  cursor: pointer;
}

.close-btn .bar {
  height: 4px;
  background: #333;
}

.close-btn .bar:nth-child(1) {
  transform: rotate(45deg);
}

.close-btn .bar:nth-child(2) {
  transform: translateY(-4px) rotate(-45deg);
}

.opened {
  display: flex;
}

.opened img {
  animation: animatepopup 1s ease-in-out .8s;
  -webkit-animation: animatepopup .3s ease-in-out forwards;
}
.deactive-button
{
   background: gray;
  color: white;
  border: gray;
  pointer-events: none;
}

 input[type=number]::-webkit-inner-spin-button,
  input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  /* Hide arrows in Firefox */
  input[type=number] {
    -moz-appearance: textfield;
  }

@keyframes animatepopup {

  to {
    opacity: 1;
    transform: translateY(0);
    -webkit-transform: translateY(0);
    -moz-transform: translateY(0);
    -ms-transform: translateY(0);
    -o-transform: translateY(0);
  }

}

@media screen and (max-width: 880px) {

  .container .container__img-holder:nth-child(3n+1) {
    margin-left: 16px;
  }
  .sm-justify-center-flex
  {
   justify-content: center;
   display: flex;
  }
  .sm-ml-20
  {
   margin-left: 20px;
  }
  .sm-mt-20
  {
   margin-top: 20px;
  }

}
</style>
<link rel="stylesheet" type="text/css" href="{{asset('/frontend/css/table.css')}}">
<div class="pb-20 bg-scroll min-h-screen" style="background:linear-gradient(to top, #eae9e9, white)">
   {{-- scroll --}}
   <div class="mr-4 flex justify-end z-30">
      <a type="button" href="{{ url('#') }}"
         class="scroll-up-button bg-primary rounded-full p-4 fixed z-20  2xl:mt-[49%] xl:mt-[59%] xlg:mt-[68%] lg:mt-[75%] xxmd:mt-[83%] md:mt-[90%]
         xmd:mt-[90%] sm:mt-[117%] msm:mt-[125%] xsm:mt-[160%]">
      <img src="{{ asset('images/downarrow.png') }}" alt="" class="w-3 h-3 z-20">
      </a>
   </div>
   <div
      class="mt-5 3xl:mx-52 2xl:mx-28 1xl:mx-28 xl:mx-36 xlg:mx-32 lg:mx-36 xxmd:mx-24 xmd:mx-32 md:mx-28 sm:mx-20 msm:mx-16 xsm:mx-10 xxsm:mx-5 z-10 relative">
      <div class="flex sm:space-x-6 msm:space-x-0 xxsm:space-x-0 xxmd:flex-row xmd:flex-col xxsm:flex-col">
         <div class="xxmd:w-2/3 xmd:w-full xxsm:w-full">
            <div class="sm-justify-center-flex">
               @if (Auth::guard('appuser')->user())
               <div
                  class="rtl-right-3p shadow-2xl rounded-lg w-10 h-10 text-center absolute bg-white top-8 xxmd:right-[38%] xmd:right-6 md:right-6 sm:right-6 xxsm:right-6">
                  @if (Str::contains($appUser->favorite, $data->id))
                  <a href="javascript:void(0);" class="like"
                     onclick="addFavorite('{{ $data->id }}','{{ 'event' }}')"><img
                     src="{{ url('images/heart-fill.svg') }}" alt=""
                     class="object-cover bg-cover fillLike bg-white-light p-2 rounded-lg"></a>
                  @else
                  <a href="javascript:void(0);" class="like"
                     onclick="addFavorite('{{ $data->id }}','{{ 'event' }}')"><img
                     src="{{ url('images/heart.svg') }}" alt=""
                     class="object-cover bg-cover fillLike bg-white-light p-2 rounded-lg"></a>
                  @endif
               </div>
               @endif
               <img src="{{ url('images/upload/' . $data->image) }}" class="w-full h-96 object-cover sm-height-140 height-300 rtl-ml-20 event-detail-image sm-ml-0"
                  id="eventimage" alt="not found">
            </div>
            <div class="mt-8 pb-5 bg-white shadow-lg rounded-md box-shadow-style mt-30px rtl-ml-20 sm-ml-0">
               <div
                  class="flex justify-between p-4 lg:flex-wrap sm:flex-wrap msm:flex-wrap xxsm:flex-wrap xlg:flex-nowrap">
                  <div class="">
                     <p class="font-poppins font-semibold text-3xl leading-9 text-black">
                        @if (session('direction') == 'rtl')
                        {{ $data->name_arabic }} 
                        @else
                        {{ $data->name }} 
                        @endif
                     </p>
                     @if ($data->rate > 1)
                     <div class="flex space-x-2 pt-3 ">
                        @for ($i = 1; $i <= $data->rate; $i++)
                        <img src="{{ asset('images/star-fill.png') }}" alt="">
                        @endfor
                        @for ($i = 5; $i > $data->rate; $i--)
                        <img src="{{ asset('images/star.png') }}" alt="">
                        @endfor
                     </div>
                     @endif
                  </div>
                  <a
                     href="{{ route('organizationDetails', ['id' => $data->organization->id]) }}" class="sm-mt-20">
                     <div class="flex msm:flex-wrap xxsm:flex-wrap">
                        @if (session('direction') == 'rtl')
                        <div class="ml-3">
                           <p class="font-poppins font-normal text-xs leading-4 text-gray-100">
                              {{ __('Organised by') }}
                           </p>
                           <p class="font-poppins font-normal text-base leading-6 text-gray">
                              {{  $data->organization->organization_name }}
                           </p>
                        </div>
                        <div class="">
                           <img src="{{ url('images/upload/' . $data->organization->image) }}"
                              class="w-10 h-10 bg-cover object-cover rtl-ml-10" alt="">
                        </div>
                        @else
                        <div class="">
                           <img src="{{ url('images/upload/' . $data->organization->image) }}"
                              class="w-10 h-10 bg-cover object-cover rtl-ml-10" alt="">
                        </div>
                        <div class="sm-ml-20">
                           <p class="font-poppins font-normal text-xs leading-4 text-gray-100">
                              {{ __('Organised by') }}
                           </p>
                           <p class="font-poppins font-normal text-base leading-6 text-gray">
                              {{  $data->organization->organization_name }}
                           </p>
                        </div>
                        @endif
                     </div>
                  </a>
               </div>
               <div class="px-4">
                  <div class="pt-4 flex space-x-6 md:flex-nowrap sm:flex-wrap xxsm:flex-wrap ">
                     <img src="{{ asset('images/calender-icon.png') }}" alt=""
                        class="bg-success-light rounded-md p-2 w-10 rtl-ml-10">
                     <!-- <div class="flex space-x-2 ">
                        <p class="font-poppins font-bold text-4xl leading-10 text-black sm-ft-18 rtl-ml-10 ">
                           {{ Carbon\Carbon::parse($data->start_time)->format('d') }}
                        </p>
                        <p class="font-poppins font-semibold text-2xl leading-8 text-gray-200 pt-2 sm-ft-18 sm-pt-4">
                           @if (session('direction') == 'rtl')
                           {{ Carbon\Carbon::parse($data->start_time)->locale('ar')->translatedFormat('M y') }}
                           @else
                           {{ Carbon\Carbon::parse($data->start_time)->format('M y') }}
                           @endif
                        </p>
                     </div> -->
                     <div class="flex space-x-2">
                        <p class="font-poppins font-bold text-4xl leading-10 text-black sm-ft-18 rtl-ml-10">
                           
                        </p>
                        <p class="font-poppins font-semibold text-2xl leading-8 text-gray-200 pt-2 sm-ft-18 sm-pt-4">

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
                           
   
                           
                        </p>
                     </div>
                  </div>
                  <div class="pt-4 flex space-x-6 md:flex-nowrap sm:flex-wrap xxsm:flex-wrap">
                     <img src="{{ asset('images/location-icon.png') }}" alt=""
                        class="p-2 w-auto h-10 rounded-md bg-blue-light rtl-ml-10">
                     <div class="">
                        <p class="font-poppins font-normal text-lg leading-7 text-gray sm-max-width-200 " style="margin-top:10px; ">
                           @if ($data->type == 'online')
                           {{ __('Online Event') }}
                           @else
                           <a  style="color: #6969e7;" target="_blank" href="@if($data->address_url) {{$data->address_url}} @else # @endif">{{ $data->address }}</a>
                           
                           @endif
                        </p>
                     </div>
                  </div>
                  @if($data->people)
                  <div class="pt-4 flex space-x-6 sm:flex-wrap xxsm:flex-wrap">
                     <img src="{{ asset('images/user-icon.png') }}" alt=""
                        class="p-2 rounded-md bg-warning-light rtl-ml-10">
                     <div class="">
                        <p class="font-poppins font-normal text-lg leading-7 text-gray rtl-font-size-30">
                           {{ $data->people }}
                        </p>
                     </div>
                  </div>
                  @endif
               </div>
            </div>
             

            <div class="mt-10 bg-white shadow-lg rounded-md box-shadow-style sm-mb-30px rtl-ml-20 sm-ml-0">
            <div class="p-4">
      <h3 class="text-primary_color_6 font-medium">{{ __('About Event') }}</h3>
      <div class="h4 mt-4 max-h-96 paragraph-3">
         @if (session('direction') == 'rtl')
         {!! strip_tags($data->description_arabic) !!}
         @else
         {!! $data->description !!}
            @endif
   </div>
      <span class="flex items-center gap-2 mt-3 py-1 cursor-pointer">
         <span class="h4 showMore"><span class="more">Show more ...</span> <span class="less hidden">Show less ...</span></span>
      </span>
</div>
   </div>

         </div>
         <div class="xxmd:w-1/3 xmd:w-full xxsm:w-full ">
            <div class="p-4 bg-white shadow-lg rounded-md box-shadow-style sm-mb-30px">
               <p class="font-poppins font-semibold text-2xl leading-8 text-black pb-3">{{ __('Image Gallery') }}
               </p>
                @if($data->id ==  56 || $data->id == 57 )
                  <div id="eventimage1" class=" hover:cursor-pointer container__img-holder"><img
                     src="{{ url('/frontend/images/stadium.png') }}"
                     class="1xl:w-40 1xl:h-24 xlg:h-16 xl:h-20 lg:w-[90%] lg:h-10 xxmd:w-full xxmd:h-32 xmd:w-[90%] msm:w-[90%] xxsm:w-full rounded-md object-cover bg-cover"
                     alt="{{ 'Event Image' }}" style="height: 500px; width: 550px; object-fit: fill;">
                  </div>

                  <div class="img-popup">
                    <img src="" alt="Popup Image">
                    <div class="close-btn">
                      <div class="bar"></div>
                      <div class="bar"></div>
                    </div>
                  </div>
                  @endif
               <div
                  class="grid lg:grid-cols-2 gap-y-5 xxmd:grid-cols-1 xmd:grid-cols-2 sm:grid-cols-2 msm:grid-cols-2 xxsm:grid-cols-1">
                  <div id="eventimage" class=" hover:cursor-pointer"
                     onclick="imagegallery('{{ $data->image }}')">
                     <img src="{{ url('images/upload/' . $data->image) }}"
                        class="1xl:w-40 1xl:h-24 xlg:h-16 xl:h-20 lg:w-[90%] lg:h-10 xxmd:w-full xxmd:h-32 xmd:w-[90%] msm:w-[90%] xxsm:w-full rounded-md object-cover bg-cover"
                        alt="">
                  </div>
                 
                  @foreach ($images as $item)
                  @if (strlen($item) > 0)
                 <!--  <div id="thumbwrap">
                     <a class="thumb" href="#"><img class="1xl:w-40 1xl:h-24 xlg:h-16 xl:h-20 lg:w-[90%] lg:h-10 xxmd:w-full xxmd:h-32 xmd:w-[90%] msm:w-[90%] xxsm:w-full rounded-md object-cover bg-cover"  src="{{ url('images/upload/' . $item) }}" alt=""><span><img style="height: 400px; width:700px !important; " src="{{ url('images/upload/' . $item) }}" alt=""></span></a>
               </div> -->
                  <div id="eventimage1" class=" hover:cursor-pointer"
                     onclick="imagegallery('{{ $item }}')"><img
                     src="{{ url('images/upload/' . $item) }}"
                     class="1xl:w-40 1xl:h-24 xlg:h-16 xl:h-20 lg:w-[90%] lg:h-10 xxmd:w-full xxmd:h-32 xmd:w-[90%] msm:w-[90%] xxsm:w-full rounded-md object-cover bg-cover"
                     alt="{{ 'Event Image' }}">
                  </div>
                  @endif
                  @endforeach
               </div>
            </div>
            @if ($data->type == 'offline')
            <div class="p-4 bg-white shadow-lg rounded-md xlg:mt-10 lg:mt-20 box-shadow-style ">
               <p class="font-poppins font-semibold text-2xl leading-8 text-black pb-3">{{ __('Location') }}
               </p>
               <div id="map" style="width:100%;height:400px;">
               </div>
				<a href="https://maps.google.com/maps?daddr={{$data->lat}},{{$data->lang}}&amp;ll=">Open in Google Maps</a>
               <!--  -->
               <!-- <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAa_afkQFDGTdyguRi_NY0iPlRzUO2fO6E&sensor=false"></script>
               <script language="javascript" type="text/javascript">
                  var map;
                  var geocoder;
                  function InitializeMap() {
                  
                      var latlng = new google.maps.LatLng(<?php echo $data->lat; ?>, <?php echo $data->lang; ?>);
                      var myOptions =
                      {
                          zoom: 13,
                          center: latlng,
                          mapTypeId: google.maps.MapTypeId.ROADMAP,
                          disableDefaultUI: true
                      };
                      map = new google.maps.Map(document.getElementById("map"), myOptions);
                  }
                  
                  function FindLocaiton() {
                      geocoder = new google.maps.Geocoder();
                      InitializeMap();
                  
                      var address = document.getElementById("addressinput").value;
                      geocoder.geocode({ 'address': address }, function (results, status) {
                          if (status == google.maps.GeocoderStatus.OK) {
                              map.setCenter(results[0].geometry.location);
                              var marker = new google.maps.Marker({
                                  map: map,
                                  position: results[0].geometry.location
                              });
                  
                          }
                          else {
                              alert("Geocode was not successful for the following reason: " + status);
                          }
                      });
                  
                  }
                  
                  
                  function Button1_onclick() {
                      FindLocaiton();
                  }
                  
                  window.onload = InitializeMap;
                  
               </script> -->
               <table>
                  <tr>
                     <td colspan ="2">
                     </td>
                  </tr>
               </table>
               <!--  -->
            </div>
            @endif
         </div>
      </div>
      {{-- tickets --}}
      <div class="bg-white shadow-lg rounded-md p-4 mt-10 box-shadow-style" id="tickets">
         <div class="flex justify-between">
            <p class="font-poopins font-semibold  text-3xl leading-9 text-black">{{ __('Tickets') }}</p>
         </div>
         <!-- <div class="time-slot">
            @if($data->is_repeat == 1)
            @foreach($data->time_slots as $time)
            <div class="time-button time-button-{{$time->id}}" onclick="timeButton({{$time->id}})"><h1>{{$time->start_time}} - {{$time->end_time}}</h1></div>
            @endforeach
            @endif
         </div> -->
         <div
            class="grid xl:grid-cols-4 xlg:grid-cols-3 xxmd:grid-cols-2 sm:grid-cols-2 msm:grid-cols-1 xxsm:grid-cols-1 pt-5 gap-5">
            @if (count($data->paid_ticket) != 0)
            @foreach ($data->paid_ticket as $item)
            <div class="relative rounded-lg border border-gray-light p-5 ">
               <div class="!h-auto mb-5" style="height: auto;margin-bottom:80px;">
                  <div class="flex justify-center">
                     <!-- <p
                        class="font-poppins font-medium text-sm leading-4 text-danger text-center rounded-full bg-danger-light w-16 py-1">
                        {{ __('Paid') }}
                     </p> -->
                  </div>
                  <p class="font-poppins font-medium text-xl leading-7 text-primary text-center py-4">
                     {{ $item->name }}
                  </p>
                  <div class="flex justify-center space-x-2">
                     @if (session('direction') == 'rtl')
                     <p class="font-poppins font-medium text-5xl leading-10 text-black text-center rtl-ml-10">
                        {{ $item->price }}
                     </p>
                     <span
                        class="font-poppins font-medium text-2xl leading-8 text-center text-black pt-1 ">{{ __($currency) }}</span>
                     @else
                     <p class="font-poppins font-medium text-5xl leading-10 text-black text-center">
                        {{ $item->price }}
                     </p>
                     <span
                        class="font-poppins font-medium text-2xl leading-8 text-center text-black pt-1">{{ __($currency) }}</span>
                     @endif
                  </div>
                  {{-- when tickets are available --}}
                 <!--  <div class="py-4">
                     @if ($item->available_qty <= 0)
                     <p
                        class="font-poppins font-normal text-lg leading-7 text-danger text-center rounded-full bg-danger-light py-2">
                        {{ __('No Available tickets') }}
                     </p>
                     @else
                     <p
                        class="font-poppins font-normal text-lg leading-7 text-success text-center bg-success-light rounded-full py-2">
                        {{ $item->available_qty }}&nbsp{{ __('Available tickets') }}
                     </p>
                     @endif
                  </div> -->
                  <p class="font-poppins font-normal text-base leading-6 text-gray text-left" style="    margin-top: 20px;">
                     {{ $item->description }}
                  </p>
                  <p class="font-poppins font-normal text-base leading-6 text-gray text-left">
                     {{ __('Ticket Sale starts onwards') }}
                  </p>
                  <p class="font-poppins font-normal text-base leading-6 text-gray text-left">
                     {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }} {{__('till')}}
                     {{ Carbon\Carbon::parse($item->end_time)->format('d M Y') }}
                  </p> <form method="GET" action="{{ url('/checkout/' . @$item->id) }}">
                   <div class="quant ">
                     <input type="hidden" class="available-{{@$item->id}}" value="{{$item->available_qty }}" name="available" id="available">
                     <input type="hidden" value="{{ $item->ticket_per_order }}" name="tpo" id="tpo">
                      @if($data->is_repeat == 0)
                     <input type="hidden" value="0" name="event_time_id" id="event_time_id">
                     <input type="hidden"  name="event_date" id="event_date" value="2024-09-24">
                    

                     <div>
                      <p
                              class="font-poppins font-medium text-base leading-7 text-black  pt-10 rtl-sm-text-right quantity-text " style="margin-left: 100px;">
                              {{ __('Quantity') }}
                           </p>
                           <div
                              class="flex flex-row h-10 w-full rounded-lg relative bg-transparent mt-1 pro-qty quantity-count" style="margin-left: 80px;">
                              <button data-mdb-ripple="true" id="dec-{{ $data->id }}"
                                 data-mdb-ripple-color="light" data-action="decrement" type="button"
                                 class="border-l dec qtybtn  border-t border-b border-primary bg-primary-light text-primary hover:text-black-700 h-8 w-9 cursor-pointer">
                              <span class="m-auto text-2xl font-thin">−</span>
                              </button>
                              <div class="text-center">
                                 <input type="number" id="quantity" readonly name="quantity"
                                    value="0"
                                    class="bg-primary-light outline-none focus:outline-none text-center w-8 font-semibold text-md
                                    hover:text-black focus:text-black md:text-basecursor-default flex items-center text-primary h-8 number-input-{{@$item->id}} quantity-input"
                                    name="custom-input-number" value="0">
                              </div>
                              <button data-mdb-ripple="true" data-mdb-ripple-color="light"
                                 data-action="increment" id="inc-{{ $data->id }}" type="button"
                                 class="border-r inc qtybtn border-t border-b border-primary bg-primary-light text-primary hover:text-black-700 h-8 w-9 cursor-pointer increment-button" data-ticekt-id="{{@$item->id}}" onclick="buttonActive('{{@$item->id}}')">
                              <span class="m-auto text-2xl font-thin">+</span>
                              </button>
                           </div>
                           <div class="font-poppins font-medium text-base leading-7 text-danger" id="quantityMsg"></div>
                  </div>
                   @endif
               </div>
               <div class="absolute bottom-5" style="width: 89%">
                  @if ($item->available_qty <= 0)
                  <div class="mt-7  w-full border border-primary rounded-lg flex justify-center">
                     <a href="#"
                        class="font-poppins font-medium text-base leading-6 text-primary  py-3">{{ __('Sold Out') }}</a>
                  </div>
                  @else
                 
                  <!-- <input type="hidden" id="quantity"  name="quantity" value="1"> -->
                  <!-- <a type="button"
                     href="{{ url('/checkout/' . @$item->id) }}"
                     class=" text-primary text-center font-poppins font-medium text-base leading-7 w-full  py-3 mt-7 border border-primary rounded-lg flex justify-center primary-color-button" >{{ __('Book Now') }}
                  <i class="fa-solid fa-arrow-right arrow-image w-3 h-3 mt-1.5 ml-2 rtl-mt-10"></i>
                  </a> -->
                  @if($data->is_repeat == 0)
                  <button href="{{ url('/checkout/' . @$item->id) }}"
                     class=" text-primary text-center font-poppins font-medium text-base leading-7 w-full  py-3 mt-7 border border-primary rounded-lg flex justify-center primary-color-button ticket-id-{{@$item->id}} quant-button  deactive-button" >
                     {{ __('Book Now') }}
                  <i class="fa-solid fa-arrow-right arrow-image w-3 h-3 mt-1.5 ml-2 rtl-mt-10"></i>
                  </button>
                  @else
                  <a 
                     class=" text-primary text-center font-poppins font-medium text-base leading-7 w-full  py-3 mt-7 border border-primary rounded-lg flex justify-center primary-color-button ticket-id-{{@$item->id}} quant-button  repeat-book-button" ticket-id="{{@$item->id}}" onclick="openModel('{{$item->id}}')" >
                     {{ __('Book Now') }}
                  <i class="fa-solid fa-arrow-right arrow-image w-3 h-3 mt-1.5 ml-2 rtl-mt-10"></i>
               </a>
               @endif
                  </form>
                  @endif
               </div>
               </div>
            </div>
            @endforeach
            @endif
            @if (count($data->free_ticket) != 0)
            @foreach ($data->free_ticket as $item)
            <div class="rounded-lg border border-gray-light p-5">
               <div class="flex justify-center">
                  <p
                     class="font-poppins font-medium text-sm leading-4 text-primary text-center rounded-full bg-primary-light w-16 py-1">
                     {{ __('free') }}
                  </p>
               </div>
               <p class="font-poppins font-medium text-xl leading-7 text-primary text-center py-4">
                  {{ $item->name }}
               </p>
               <div class="flex justify-center space-x-2">
                  <span
                     class="font-poppins font-medium text-2xl leading-8 text-center text-black pt-1"></span>
                  <p class="font-poppins font-medium text-5xl leading-10 text-black text-center">
                     {{ __('Free') }}
                  </p>
               </div>
               {{-- when tickets are available --}}
               <div class="py-4">
                  @if ($item->available_qty <= 0)
                  <p
                     class="font-poppins font-normal text-lg leading-7 text-danger text-center rounded-full bg-danger-light py-2">
                     {{ __('No Available tickets') }}
                  </p>
                  @else
                  <p
                     class="font-poppins font-normal text-lg leading-7 text-success text-center bg-success-light rounded-full py-2">
                     {{ $item->available_qty . ' Available tickets' }}
                  </p>
                  @endif
               </div>
               <p class="font-poppins font-normal text-base leading-6 text-gray text-left">
                  {{ $item->description }}
               </p>
               <p class="font-poppins font-normal text-base leading-6 text-gray text-left">
                  {{ __('Ticket Date') }}
               </p>
               <p class="font-poppins font-normal text-base leading-6 text-gray text-left">
                  {{ Carbon\Carbon::parse($item->start_time)->format('d M Y') }} -
                  {{ Carbon\Carbon::parse($item->end_time)->format('d M Y') }}
               </p>
               @if ($item->available_qty <= 0)
               <div class="mt-7  w-full border border-primary rounded-lg flex justify-center">
                  <a href="#"
                     class="font-poppins font-medium text-base leading-6 text-primary  py-3">{{ __('Sold Out') }}</a>
               </div>
               @else
               <a type="button"
                  href="{{ url('/checkout/' . @$item->id) }}"
                  class=" text-primary text-center font-poppins font-medium text-base leading-7 w-full  py-3 mt-7 border border-primary rounded-lg flex justify-center">{{ __('Book Now') }}
               <i class="fa-solid fa-arrow-right w-3 h-3 mt-1.5 ml-2"></i>
               </a>
               @endif
            </div>
            @endforeach
            @endif
            @if (count($data->free_ticket) == 0 && count($data->paid_ticket) == 0)
            <div class="mx-auro w-full">
               <div class="px-5">
                  <img src="{{ url('frontend/images/empty.png') }}">
                  <h6 class="font-poopins  font-light  text-3xl leading-9 text-black px-5">
                     {{ __('No Tickets found') }}!
                  </h6>
               </div>
            </div>
            @endif
         </div>
      </div>
   <!--    @if(!is_null($ticket_detail) && count($ticket_detail) > 0 )
      {{-- Available Tickets  --}}
      <div class="bg-white shadow-lg rounded-md p-4 mt-10 box-shadow-style" id="tickets">
         <div class="flex justify-between">
            <p class="font-poopins font-semibold  text-3xl leading-9 text-black">{{ __('Available Tickets') }}</p>
         </div>
         <div class="wrapper mt-20">
  
  <table class="c-table rtl-font-22">
  <thead class="c-table__header">
    <tr>
      <th class="c-table__col-label">{{ __('Location') }}</th>
      <th class="c-table__col-label">{{ __('Available') }}</th>
      <th class="c-table__col-label">{{ __('Ticket Price') }}</th>
      <th class="c-table__col-label">{{ __('Buy') }}</th>
    </tr>
  </thead>
  <tbody class="c-table__body">

   @foreach($ticket_detail as $detail)

          <?php 
          $check_ticket = 0 ; 
   if(count($data->paid_ticket) != 0)
   {
      $ticket = array(); 
      foreach ($data->paid_ticket as  $item) {
         if(Str::contains($item->name, $detail->location))
         {
          $check_ticket = 1 ;
         }
      }  
   }
   ?>
    <tr>
      <td class="c-table__cell width-250">{{$detail->location}}</td>
      <td class="c-table__cell">@if($check_ticket){{$item->available_qty}} @else 0 @endif</td>
      <td class="c-table__cell">{{$detail->price}} {{ __($currency) }}</td>
      <td class="c-table__cell">

         @if($check_ticket)
         <a  class="px-10 py-3 sign-in-button-css text-center font-poppins font-normal text-base leading-6 rounded-md bold rtl-font-22" href="#tickets" style=" font-size: 14px;">
                  {{ __('Buy') }}
                  </a>
                  @else
                  <a  class="px-10 py-3 sign-in-button-css text-center font-poppins font-normal text-base leading-6 rounded-md bold rtl-font-22" style="background: grey !important; color: black; font-size: 14px;">
                  {{ __('Sold Out') }}
                  </a>
                  @endif
               </td>


    </tr>
   @endforeach 
  </tbody>
</table>
</div>  
      </div>

      {{-- Available Tickets --}}
      @endif -->
      {{-- review --}}
      <div class="bg-white shadow-lg rounded-md p-4 mt-10 box-shadow-style">
         <div class="flex">
            <p class="font-poppins font-semibold text-2xl leading-7 text-black">{{ __('Reviews') }}</p>
            &nbsp;
            <p class="font-poppins font-medium text-base leading-8 text-black">({{ count($data->review) }})</p>
         </div>
         @if (count($data->review) != 0)
         @foreach ($data->review as $item)
         <div>
            <div class="flex justify-between mt-4 sm:flex-wrap xxsm:flex-wrap">
               <div class="flex sm:flex-wrap xxsm:flex-wrap">
                  <div class="">
                     @php
                     $user = \App\Models\AppUser::find($item->user_id);
                     @endphp
                     <img src="{{ asset('images/upload/' . $user->image) }}"
                        class="w-10 h-10 bg-cover object-cover rtl-ml-10"  alt="">
                  </div>
                  <div class="ml-3 ">
                     <p class="font-poppins font-medium text-lg leading-6 text-black-100">
                        {{ $user->name }}
                     </p>
                  </div>
               </div>
               <div class="flex">
                  <p class="font-poppins font-medium text-base leading-4 text-gray-200 pt-1 mr-3 rtl-ml-10">
                     {{ __('Rating : ' . $item->rate) }}
                  </p>
                  <div class="flex space-x-1">
                     @for ($i = 1; $i <= $item->rate; $i++)
                     <img src="{{ asset('images/star-fill.png') }}"
                        class="h-5 w-5 bg-cover object-cover" alt="">
                     @endfor
                  </div>
               </div>
            </div>
            <div class="ml-12 mt-4">
               <p class="font-poppins font-normal text-base leading-6 text-gray">
                  {{ $item->message }}
               </p>
            </div>
         </div>
         @endforeach
         @else
         @endif
      </div>
      {{-- Report Event --}}
      <div class="bg-white shadow-lg rounded-md p-4 mt-10 box-shadow-style">
         <p class="font-poppins font-semibold text-2xl leading-8 text-black">{{ __('Report Event') }}</p>
         <form class="form-a" method="post" action="{{ url('report-event') }}">
            @csrf
            <div class="">
               <div class="grid md:grid-cols-2 sm:grid-cols-1 xxsm:grid-cols-1 mt-5 gap-3">
                  <div class=" ">
                     <label for="name"
                        class="font-poppins font-normal text-lg leading-7 text-gray-100 pb-2">{{ __('Name') }} </label>
                     <input type="text" name="name"
                        class="focus:outline-none text-base leading-4 font-poppins font-normal text-gray-100 block p-3 rounded-md z-20
                        border border-gray-light w-full"
                        placeholder="{{ __('Name *') }}">
                  </div>
                  <div class="">
                     <label for="name"
                        class="font-poppins font-normal text-lg leading-7 text-gray-100 pb-2">{{ __('Email address') }}</label>
                     <input type="text" name="email"
                        class="focus:outline-none text-base leading-4 font-poppins font-normal text-gray-100 block p-3 rounded-md z-20
                        border border-gray-light w-full"
                        placeholder="{{ __('Email *') }}">
                  </div>
               </div>
               <div class="grid md:grid-cols-2 sm:grid-cols-1 xxsm:grid-cols-1 mt-5 gap-3">
                  <div class="w-full">
                     <label for="report_reason"
                        class="font-poppins font-normal text-lg leading-7 text-gray-100 pb-2">{{ __('Report Reason') }}</label>
                     <select id="report_reason" name="reason"
                        class="w-full focus:outline-none text-base leading-4 font-poppins font-normal text-gray-100 block p-3 rounded-md z-20
                        border border-gray-light">
                        <option class="font-poppins font-normal text-base leading-4 text-gray-100" selected
                           disabled>
                           {{ __('Select Reason') }}
                        </option>
                        <option class="font-poppins font-normal text-base leading-4 text-gray-100"
                           value="Canceled Event">
                           {{ __('Canceled Event') }}
                        </option>
                        <option class="font-poppins font-normal text-base leading-4 text-gray-100"
                           value="Copyright or Trademark Infringement">
                           {{ __('Copyright or Trademark Infringement') }}
                        </option>
                        <option class="font-poppins font-normal text-base leading-4 text-gray-100"
                           value="Fraudulent of Unauthorized Event">
                           {{ __('Fraudulent of Unauthorized Event') }}
                        </option>
                        <option class="font-poppins font-normal text-base leading-4 text-gray-100"
                           value="Offensive or Illegal Event">
                           {{ __('Offensive or Illegal Event') }}
                        </option>
                        <option class="font-poppins font-normal text-base leading-4 text-gray-100"
                           value="Spam">
                           {{ __('Spam') }}
                        </option>
                        <option class="font-poppins font-normal text-base leading-4 text-gray-100"
                           value="Other">
                           {{ __('Other') }}
                        </option>
                     </select>
                  </div>
               </div>
               <div class="w-full mt-5">
                  <textarea id="message" rows="4" required name="message"
                     class="block p-2.5 w-full focus:outline-none text-base leading-4 font-poppins font-normal text-gray-100
                     border border-gray-light rounded-md"
                     placeholder="{{ __('Describe your message...') }}"></textarea>
               </div>
               <input type="hidden" name="event_id" id="event_id" value="{{ $data->id }}">
               <div class="mt-5 flex justify-end">
                  <button
                     class="bg-primary text-white text-right font-poppins font-medium text-lg leading-7 px-5 py-2 rounded-md">{{ __('Send Message') }}</button>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>

<input type="hidden" id="selected_ticket_id" name="">
<div class="modal fade" id="bookingModal{{@$item->id}}" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bookingModalLabel">Select Date & Time</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Calendar -->
        <div class="form-group">
          <label for="booking-date-{{@$item->id}}">Select Date</label>
          <input type="text" id="booking-date-{{@$item->id}}" event-id="{{$data->id}}" class="form-control booking-date">
        </div>
        
        <div class="form-group">
          <label for="time-slot" class="form-label">Select Time Slot</label>
          <div id="time-slot" class="time-slot-container">
            <!-- Time Slot Buttons will be dynamically added here -->
          </div>
        </div>
        <div >
                      <p
                              class="font-poppins font-medium text-base leading-7 text-black 
                               rtl-sm-text-right  " >
                              {{ __('Quantity') }}
                           </p>
                           <div
                              class="flex flex-row h-10 w-full rounded-lg relative bg-transparent mt-1 pro-qty " >
                              <button data-mdb-ripple="true" id="dec-{{ $data->id }}"
                                 data-mdb-ripple-color="light" data-action="decrement" type="button"
                                 class="border-l dec qtybtn  border-t border-b border-primary bg-primary-light text-primary hover:text-black-700 h-8 w-9 cursor-pointer">
                              <span class="m-auto text-2xl font-thin">−</span>
                              </button>
                              <div class="text-center">
                                 <input type="number" id="quantity" readonly name="quantity"
                                    value="0"
                                    class="bg-primary-light outline-none focus:outline-none text-center w-8 font-semibold text-md
                                    hover:text-black focus:text-black md:text-basecursor-default flex items-center text-primary h-8 number-input-{{@$item->id}} quantity-input modal-quantity"
                                    name="custom-input-number" value="0">
                              </div>
                              <button data-mdb-ripple="true" data-mdb-ripple-color="light"
                                 data-action="increment" id="inc-{{ $data->id }}" type="button"
                                 class="border-r inc qtybtn border-t border-b border-primary bg-primary-light text-primary hover:text-black-700 h-8 w-9 cursor-pointer increment-button" data-ticekt-id="{{@$item->id}}" >
                              <span class="m-auto text-2xl font-thin">+</span>
                              </button>
                           </div>
                           <div class="font-poppins font-medium text-base leading-7 text-danger" id="quantityMsg"></div>
                  </div>
      </div>
      
      <div class="modal-footer"> 
        <button type="button" style="width:30%" data-bs-dismiss="modal"
          class="text-primary text-center font-poppins font-medium text-base leading-7 py-3 border border-primary rounded-lg flex justify-center primary-color-button ticket-id-{{@$item->id}} quant-button ">
          Close 
        </button>
        <button href="{{ url('/checkout/' . @$item->id) }}" style="width:30%" id="confirm-time-btn"
          class="text-primary text-center font-poppins font-medium text-base leading-7 py-3 border border-primary rounded-lg flex justify-center primary-color-button ticket-id-{{@$item->id}} quant-button">
          {{ __('Book Now') }}
          <i class="fa-solid fa-arrow-right arrow-image w-3 h-3 mt-1.5 ml-2 rtl-mt-10"></i>
        </button>
      </div>
    </div>
  </div>
</div>

 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- 
<script>
   
   function initMap() {
       var map = new google.maps.Map(document.getElementById('map'), {
           center: {
               lat: {{ $data->lat }},
               lng: {{ $data->lang }}
           },
           zoom: 13
       });
       let marker = new google.maps.Marker({
           position: {
               lat: {{ $data->lat }},
               lng: {{ $data->lang }}
           },
           map: map
       });
   }


</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAa_afkQFDGTdyguRi_NY0iPlRzUO2fO6E&loading=async&callback=initMap"></script> -->







<script>

$(document).ready(function() {
      $('.showMore').click(function() {
         $('.h4.mt-4.max-h-96').toggleClass('paragraph-3');
         $('.showMore .more').toggleClass('hidden')
         $('.showMore .less').toggleClass('hidden')
      });
   });

function timeButton(id  ,date , ticket_id)
{  
   var date = $(".booking-date").val();
   var ticket_id = $('#selected_ticket_id').val();
         console.log("available-id-up" , id ,  date, ticket_id  )
   $("#event_time_id").val(id);
   var event_id = $("#event_id").val();
   var event_date = date;

    $.ajax({
            url: "{{ url('/event-time/check') }}", // Laravel route
            type: "POST",
            data: {"event_time_id" :id , "date" :date  , "event_id" : event_id  },
            success: function(response) {
                console.log("ticket" ,response.ticket ,  response.ticket[ticket_id], ticket_id  )
               Object.keys(response.ticket).forEach(function(key) {
                   console.log("available-id" , response.ticket[ticket_id] , ticket_id  )
                    $("#available").val(response.ticket[ticket_id]);
                    

                  });
             /*  response.ticket.forEach(ticketsupdate);
               function ticketsupdate(item, index) {
                  $("#available-"+item).val(index);
                 
               }*/
            }

        });
   
}
   
    function buttonActive (id)
   {
      $(".quant-button").addClass('deactive-button')
      $(".ticket-id-"+id).removeClass('deactive-button')

      const numberInputs = document.querySelectorAll('.quantity-input');
      
       numberInputs.forEach(input => {

               if(!input.classList.contains('number-input-'+id))
               {
                  input.value = 0;
                }
            });
      
      

   }


   
   /*function buttonActive (id)
   {
      $(".ticket-id-"+id).removeClass('deactive-button')
   }*/
   $(document).ready(function() {

  // required elements
  var imgPopup = $('.img-popup');
  var imgCont  = $('.container__img-holder');
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
	
	function isMobile() {
      return /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
  }
        function initMap() {
            const lat = parseFloat("{{ $data->lat }}");
            const lng = parseFloat("{{ $data->lang }}");
            console.log('Initializing map with coordinates:', lat, lng);

            // Check if the map container exists
            const mapElement = document.getElementById('map');
            if (!mapElement) {
                console.error('Map container element not found');
                return;
            }

             var map = new google.maps.Map(mapElement, {
                center: { lat: lat, lng: lng },
                zoom: 14
            });

            let marker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map
            });
        }
        // Check if the Google Maps script is loaded correctly
        window.addEventListener('load', () => {
            console.log('Page fully loaded');
            if (typeof google === 'undefined' || !google.maps) {
                console.error('Google Maps script did not load correctly');
            }
        });
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $gmapkey }}&callback=initMap"></script>

<!-- <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: {{ $data->lat }},
                    lng: {{ $data->lang }}  
                },
                zoom: 14
            });
            let marker = new google.maps.Marker({
                position: {
                    lat: {{ $data->lat }},
                    lng: {{ $data->lang }}  
                },
                map: map
            });
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $gmapkey }}&callback=initMap"></script> -->

    <script>
 
    // On clicking the "Book Now" button, open the modal
    function openModel (id) {
      getTimeOnBook(id)
      $('#selected_ticket_id').val(id);
        $('#bookingModal{{@$item->id}}').modal('show');

        // Initialize flatpickr for date picking with disabled past dates
        $('#booking-date-{{@$item->id}}').flatpickr({
            dateFormat: 'Y-m-d',
            minDate: "today" // Disables past dates
        });

        // Initialize flatpickr for time picking
        $('#booking-time-{{@$item->id}}').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i',
            time_24hr: true,
        });
    };
  
</script> 
<script>
   
  $(document).ready(function() {
    // Handle time slot selection 
      $(document).on('click', '.time-slot-btn', function() {

      // Remove active class from all buttons
      $('.time-slot-btn').removeClass('active');
      
      // Add active class to the clicked button
      $(this).addClass('active');
      
      // Save the selected time (optional, depending on what you want to do next)
      var selectedTime = $(this).data('time');
      
    });

    // Handle confirm button click (you can use this to submit the form or make an AJAX request)
    
      $(document).on('click', '#confirm-time-btn', function() {
      var selectedTime = $('.time-slot-btn.active').data('time');
      var timeSlotId = $('.time-slot-btn.active').data('time-id');
      var ticketId = $('.time-slot-btn.active').data('ticket-id');
      var selectedDate = $('.time-slot-btn.active').data('date');
      if (!selectedTime) {
        alert('Please select a time slot!');
      } else {
         var quantity = $(".modal-quantity").val();
         var event_time_id = timeSlotId;
         var event_date = selectedDate;
         console.log(quantity , event_date , event_time_id , ticketId);
         window.location.href = "https://ticketby.co/checkout/"+ticketId+"?quantity="+quantity+"&event_time_id="+event_time_id+"&event_date="+selectedDate
        

        // Guruji add your ajax here

        // Perform further actions, like submitting the selected time via AJAX
      }
    });
  });

  function getTimeOnBook(ticket_id)
  {
   
   var event_id = $("#event_id").val();
    // Initialize date picker
    var today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
    $('#booking-date-{{@$item->id}}').val(today); // Set the date picker to today's date
    getTimeSlots(today, event_id , ticket_id); 
  }
  $(document).ready(function() {
   var event_id = $("#event_id").val();
    // Initialize date picker
    var today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
    $('#booking-date-{{@$item->id}}').val(today); // Set the date picker to today's date
    
    // Fetch time slots for today's date on page load
    var itemId = $('#booking-date-{{@$item->id}}').attr('id').split('-')[2]; // Get item ID
    
    
    getTimeSlots(today, event_id , itemId); // Fetch time slots for today's date

    $('#booking-date-{{@$item->id}}').on('change', function() {
        var selectedDate = $(this).val();
        var itemId = $(this).attr('id').split('-')[2]; // Get item ID
        var ticket_id = $('#selected_ticket_id').val();

        getTimeSlots(selectedDate, event_id , ticket_id); // Fetch time slots 
    });
});

// Function to fetch time slots
function getTimeSlots(selectedDate, event_id ,itemId) {
   console.log(event_id , itemId);
    $.ajax({
        url: '/get-time-slots', // Your API endpoint
        type: 'GET',
        data: {
            date: selectedDate,
            item_id: event_id,
            ticket_id : itemId
        },
        success: function(response) {
            $('#time-slot').empty(); // Clear existing slots
            if (response.length > 0) {
                response.forEach(function(slot) {
                    $('#time-slot').append('<button class="time-slot-btn time-button-'+slot.id+'"   onclick="timeButton('+slot.id+' , '+selectedDate+' , '+itemId+')" data-ticket-id="'+itemId+'"  data-time-id="'+slot.id+'" data-date="'+selectedDate+'" data-time="'+slot.start_time+'-'+slot.end_time+'">'+slot.start_time+'-'+slot.end_time+'</button>');
                });
            } else {
                $('#time-slot').append('<p>No time slots available</p>');
            }
        },
        error: function() {
            alert('Error fetching time slots. Please try again.');
        }
    });
}


</script>
@endsection