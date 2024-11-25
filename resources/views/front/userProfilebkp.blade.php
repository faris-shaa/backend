@extends('front.master', ['activePage' => 'Profile'])
@section('title', __('My Profile'))
@section('content')
@php
$user = Auth::guard('appuser')->user();
@endphp



<div class="container mt-12 md:mt-16">
    <div class="md:grid   md:grid-cols-12 gap-4">
        <div class="mb-4 col-span-4 bg-light bg-opacity-5 rounded-2xl border border-primary_color_o10_1 p-2 md:p-32-32 ">
            <div class="mb-4">
                <div class="relative mb-4 w-fit image-overlay">
                    <img src="{{ asset('images/upload/' . $user->image) }}" alt=""
                        class="bg-cover object-cover rounded-full h-40 w-40 ">
                    <div class="absolute  rtl-image-upload-update-profile absolute bottom-0 left-1/2  transform -translate-x-1/2 z-40 bottom-5">
                        <form method="post" action="#" id="imageUploadForm" enctype="multipart/form-data"
                            style="display: none">
                            @csrf
                            <input type="file" name="image" id="imgUpload" class="hide">
                        </form>
                        <span id="OpenImgUpload" class="z-30 relative">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.666 4.20836H15.0327C14.8386 4.2107 14.6479 4.1579 14.4827 4.05611C14.3175 3.95433 14.1845 3.80772 14.0993 3.63336L13.3493 2.14169C13.1605 1.75979 12.8683 1.43858 12.5058 1.21464C12.1434 0.990702 11.7254 0.87303 11.2993 0.875025H6.69935C6.27332 0.87303 5.85529 0.990702 5.49287 1.21464C5.13044 1.43858 4.83817 1.75979 4.64935 2.14169L3.89935 3.63336C3.81417 3.80772 3.68123 3.95433 3.51601 4.05611C3.35079 4.1579 3.16006 4.2107 2.96602 4.20836H2.33268C1.72489 4.20836 1.142 4.4498 0.712229 4.87957C0.282458 5.30934 0.0410156 5.89224 0.0410156 6.50002V14.8334C0.0410156 15.4411 0.282458 16.024 0.712229 16.4538C1.142 16.8836 1.72489 17.125 2.33268 17.125H15.666C16.2738 17.125 16.8567 16.8836 17.2865 16.4538C17.7162 16.024 17.9577 15.4411 17.9577 14.8334V6.50002C17.9577 5.89224 17.7162 5.30934 17.2865 4.87957C16.8567 4.4498 16.2738 4.20836 15.666 4.20836ZM8.99935 14.2084C8.13406 14.2084 7.2882 13.9518 6.56873 13.471C5.84926 12.9903 5.28851 12.307 4.95738 11.5076C4.62624 10.7082 4.5396 9.8285 4.70841 8.97984C4.87722 8.13117 5.2939 7.35162 5.90576 6.73977C6.51761 6.12791 7.29716 5.71123 8.14583 5.54242C8.9945 5.37361 9.87416 5.46025 10.6736 5.79139C11.473 6.12252 12.1563 6.68327 12.637 7.40274C13.1178 8.1222 13.3743 8.96806 13.3743 9.83336C13.3721 10.993 12.9105 12.1045 12.0905 12.9245C11.2705 13.7445 10.159 14.2062 8.99935 14.2084Z" fill="white" />
                                <path d="M9 12.9583C10.7259 12.9583 12.125 11.5591 12.125 9.83325C12.125 8.10736 10.7259 6.70825 9 6.70825C7.27411 6.70825 5.875 8.10736 5.875 9.83325C5.875 11.5591 7.27411 12.9583 9 12.9583Z" fill="white" />
                            </svg>
                        </span>
                    </div>
                </div>
                <h3 class="mt-2">{{$user->name}}</h3>
            </div>
            <div class="">
                <div class="flex flex-col py-4 border-t-1  border-b-1 border-dashed border-gray_6">
                    <button class="tab-button text-gray_b5  py-3 rounded-6xl focus:outline-none  text-h6 flex items-center gap-1 text-start" data-tab="tab3">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_2044_11430)">
                                <path d="M18.225 7.4813V4.95005C18.225 3.83162 17.3184 2.92505 16.2 2.92505H2.025C0.906631 2.92505 0 3.83162 0 4.95005V7.4813C0.838793 7.4813 1.51875 8.16126 1.51875 9.00005C1.51875 9.83884 0.838793 10.5188 0 10.5188V13.05C0 14.1684 0.906631 15.075 2.025 15.075H16.2C17.3184 15.075 18.225 14.1684 18.225 13.05V10.5188C17.3862 10.5188 16.7063 9.83884 16.7063 9.00005C16.7063 8.16126 17.3862 7.4813 18.225 7.4813ZM17.2125 11.3199V13.05C17.2125 13.6083 16.7583 14.0625 16.2 14.0625H2.025C1.4667 14.0625 1.0125 13.6083 1.0125 13.05V11.3199C1.90578 10.9286 2.53125 10.036 2.53125 9.00005C2.53125 7.96407 1.90578 7.07146 1.0125 6.68016V4.95005C1.0125 4.39175 1.4667 3.93755 2.025 3.93755H16.2C16.7583 3.93755 17.2125 4.39175 17.2125 4.95005V6.68016C16.3193 7.07146 15.6938 7.96407 15.6938 9.00005C15.6938 10.036 16.3193 10.9286 17.2125 11.3199ZM13.1625 5.96255H5.0625C4.50335 5.96255 4.05 6.41583 4.05 6.97505V11.025C4.05 11.5842 4.50335 12.0375 5.0625 12.0375H13.1625C13.7217 12.0375 14.175 11.5842 14.175 11.025V6.97505C14.175 6.41583 13.7217 5.96255 13.1625 5.96255ZM13.1625 11.025H5.0625V6.97505H13.1625V11.025Z" fill="#B5B7C8" />
                            </g>
                            <defs>
                                <clipPath id="clip0_2044_11430">
                                    <rect width="18" height="18" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                        {{__('My tickets')}} </button>
                    <button class="tab-button text-gray_b5  py-3 rounded-6xl focus:outline-none  text-h6 flex items-center gap-1 text-start" data-tab="tab5">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.5 0H4.5C3.25965 0 2.25 1.00909 2.25 2.25V17.4375C2.25 17.643 2.36208 17.8319 2.54222 17.9308C2.72239 18.0286 2.94103 18.0209 3.11463 17.9121L9 14.1669L14.8854 17.9121C14.9777 17.9703 15.082 18 15.1875 18C15.2809 18 15.3732 17.9769 15.4578 17.9308C15.6379 17.8319 15.75 17.643 15.75 17.4375V2.25C15.75 1.00909 14.7403 0 13.5 0ZM14.625 16.413L9.30213 13.0254C9.20985 12.9666 9.10547 12.9375 9 12.9375C8.89453 12.9375 8.79015 12.9666 8.69787 13.0254L3.375 16.413V2.25C3.375 1.62981 3.87928 1.125 4.5 1.125H13.5C14.1207 1.125 14.625 1.62981 14.625 2.25V16.413Z" fill="#B5B7C8" />
                        </svg>{{__('My Favorites')}}</button>
                </div>
                <div class="flex flex-col mt-4">
                    <button class="tab-button flex items-center justify-center gap-1 text-white px-2 py-3 rounded-md focus:outline-none  text-h6 bg-primary_color_a9" data-tab="tab1">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 0.5625C4.34007 0.5625 0.5625 4.34007 0.5625 9C0.5625 13.6599 4.34007 17.4375 9 17.4375C13.6599 17.4375 17.4375 13.6599 17.4375 9C17.4375 4.34007 13.6599 0.5625 9 0.5625ZM9 16.3125C7.18952 16.3125 5.53409 15.6473 4.25563 14.5536C4.82593 13.2718 6.10935 12.375 7.60053 12.375H10.3995C11.8907 12.375 13.1741 13.2718 13.7444 14.5536C12.4659 15.6473 10.8105 16.3125 9 16.3125ZM14.5741 13.7219C13.7583 12.2509 12.201 11.25 10.3995 11.25H7.60053C5.79913 11.25 4.24188 12.251 3.42594 13.7219C2.34394 12.4466 1.6875 10.7996 1.6875 9C1.6875 4.96789 4.96789 1.6875 9 1.6875C13.0321 1.6875 16.3125 4.96789 16.3125 9C16.3125 10.7996 15.6561 12.4465 14.5741 13.7219ZM9 4.5C7.44666 4.5 6.1875 5.75916 6.1875 7.3125C6.1875 8.86577 7.44666 10.125 9 10.125C10.5533 10.125 11.8125 8.86577 11.8125 7.3125C11.8125 5.75916 10.5533 4.5 9 4.5ZM9 9C8.06952 9 7.3125 8.24298 7.3125 7.3125C7.3125 6.38202 8.06952 5.625 9 5.625C9.93048 5.625 10.6875 6.38202 10.6875 7.3125C10.6875 8.24298 9.93048 9 9 9Z" fill="white" />
                        </svg>
                        {{__('Profile settings')}}</button>
                    <div class="p-3 ">
                        <button class="tab-button  px-2 py-3  focus:outline-none  text-h6 bg-primary_color_8 text-white   rounded-md  w-full mt-9" data-tab="tab2">{{__('Log out')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-8 bg-light bg-opacity-5 rounded-2xl border border-primary_color_o10_1 p-2 md:p-32-32 ">
            <div class="tab-content ">
                <div id="tab3" class="tab-pane hidden  ">
                    <p class="text-h5 lg:text-h3  mb-4"> {{__('My tickets')}}</p>
                    <div class="mb-4 flex border rounded-5xl border-primary_color_a11 justify-center w-fit mx-auto p-1 gap-1">
                        <button class="tab-button2 flex items-center justify-center gap-1 text-white px-2 py-3 rounded-5xl focus:outline-none  text-h6 active" data-tab="tab8">
                            {{__('Upcoming Event')}}</button>
                        <button class="tab-button2 flex items-center justify-center gap-1 text-white px-2 py-3 rounded-5xl focus:outline-none  text-h6 " data-tab="tab9">
                            {{__('Past Events')}}</button>
                    </div>
                    <div class="tab-content2 ">
                        <div id="tab8" class="tab-pane2  hidden active">
                        @if(isset($ticket['upcoming']->event))

                            @forelse ($ticket['upcoming']->event as $item)
                            <div class="grid grid-col-1 md:grid-cols-2  xl:grid-cols-3 gap-4 mt-4">
                                <div class="bg-light bg-opacity-5 rounded-2xl border border-primary_color_o10_1 overflow-hidden">
                                    <div class="h-60 md:h-32 overlay-tiket">
                                        <img class="w-full h-full object-cover" src="{{ url('images/upload/' . $item['event']['image']) }}" alt="">
                                    </div>
                                    <div class="px-1">
                                        <h5 class="font-medium">
                                            {{ $item['event']['name']}}
                                        </h5>
                                        <div class="mt-2  mb-7 flex items-center justify-between">
                                            <div class="">
                                                <p class="flex gap-1  items-center">
                                                    <svg width="11" height="14" viewBox="0 0 11 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M7.5 5.6C7.5 6.725 6.6 7.6 5.5 7.6C4.375 7.6 3.5 6.725 3.5 5.6C3.5 4.5 4.375 3.6 5.5 3.6C6.6 3.6 7.5 4.5 7.5 5.6ZM5.5 6.8C6.15 6.8 6.7 6.275 6.7 5.6C6.7 4.95 6.15 4.4 5.5 4.4C4.825 4.4 4.3 4.95 4.3 5.6C4.3 6.275 4.825 6.8 5.5 6.8ZM10.3 5.6C10.3 7.8 7.375 11.675 6.075 13.3C5.775 13.675 5.2 13.675 4.9 13.3C3.6 11.675 0.7 7.8 0.7 5.6C0.7 2.95 2.825 0.8 5.5 0.8C8.15 0.8 10.3 2.95 10.3 5.6ZM5.5 1.6C3.275 1.6 1.5 3.4 1.5 5.6C1.5 6 1.625 6.525 1.9 7.2C2.175 7.85 2.55 8.55 3 9.25C3.85 10.625 4.85 11.95 5.5 12.75C6.125 11.95 7.125 10.625 7.975 9.25C8.425 8.55 8.8 7.85 9.075 7.2C9.35 6.525 9.5 6 9.5 5.6C9.5 3.4 7.7 1.6 5.5 1.6Z" fill="#A986BF" />
                                                    </svg>
                                                    <a target="_blank" href=" # "><span class="text-h8"> {{$item['event']['address']}}</span></a>
                                                </p>
                                                <p class="flex gap-1    items-center">
                                                    <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M3.1 0.8C3.3 0.8 3.5 1 3.5 1.2V2.4H8.3V1.2C8.3 1 8.475 0.8 8.7 0.8C8.9 0.8 9.1 1 9.1 1.2V2.4H9.9C10.775 2.4 11.5 3.125 11.5 4V5.625C11.35 5.625 11.225 5.6 11.1 5.6C10.95 5.6 10.825 5.625 10.7 5.625V5.6H1.1V12C1.1 12.45 1.45 12.8 1.9 12.8H7.7C7.95 13.125 8.225 13.375 8.55 13.6H1.9C1 13.6 0.3 12.9 0.3 12V4C0.3 3.125 1 2.4 1.9 2.4H2.7V1.2C2.7 1 2.875 0.8 3.1 0.8ZM9.9 3.2H1.9C1.45 3.2 1.1 3.575 1.1 4V4.8H10.7V4C10.7 3.575 10.325 3.2 9.9 3.2ZM11.075 8C11.3 8 11.475 8.2 11.475 8.4V9.6H12.3C12.5 9.6 12.7 9.8 12.7 10C12.7 10.225 12.5 10.4 12.3 10.4H11.075C10.875 10.4 10.675 10.225 10.675 10V8.4C10.675 8.2 10.875 8 11.075 8ZM7.5 10C7.5 8.025 9.1 6.4 11.1 6.4C13.075 6.4 14.7 8.025 14.7 10C14.7 12 13.075 13.6 11.1 13.6C9.1 13.6 7.5 12 7.5 10ZM11.1 12.8C12.625 12.8 13.9 11.55 13.9 10C13.9 8.475 12.625 7.2 11.1 7.2C9.55 7.2 8.3 8.475 8.3 10C8.3 11.55 9.55 12.8 11.1 12.8Z" fill="#A986BF" />
                                                    </svg>
                                                    <span class="text-h8 ">
                                                        {{ Carbon\Carbon::parse($item['event']['start_time'])->format('d M Y') }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div>
                                                <a href="" class="bg-gray_35 rounded-lg text-light text-h8 flex items-center gap-1  p-4-16">
                                                    <svg width="9" height="12" viewBox="0 0 9 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M4.5 0.5C2.29102 0.5 0.5 2.34699 0.5 4.625C0.5 6.28811 1.06185 6.7527 4.08919 11.2782C4.18848 11.4261 4.34423 11.5 4.5 11.5C4.65577 11.5 4.81152 11.4261 4.91081 11.2782C7.93815 6.7527 8.5 6.28811 8.5 4.625C8.5 2.34699 6.70898 0.5 4.5 0.5ZM4.5 10.6818C1.58192 6.33041 1.16667 5.99933 1.16667 4.625C1.16667 2.72958 2.66198 1.1875 4.5 1.1875C6.33802 1.1875 7.83333 2.72958 7.83333 4.625C7.83333 5.9974 7.42846 6.31526 4.5 10.6818ZM4.5 2.90625C3.57954 2.90625 2.83333 3.67573 2.83333 4.625C2.83333 5.57427 3.57954 6.34375 4.5 6.34375C5.4205 6.34375 6.16667 5.57427 6.16667 4.625C6.16667 3.67573 5.4205 2.90625 4.5 2.90625ZM4.5 5.65625C3.9486 5.65625 3.5 5.19363 3.5 4.625C3.5 4.05637 3.9486 3.59375 4.5 3.59375C5.0514 3.59375 5.5 4.05637 5.5 4.625C5.5 5.19363 5.0514 5.65625 4.5 5.65625Z" fill="#C4ACD3" />
                                                    </svg>
                                                    {{__('Map')}}</a>
                                            </div>
                                        </div>
                                        <div class="py-2 border-t-1 border-dashed border-gray_fb round-tiket">
                                            <div class="bg-primary_color_2   rounded-lg w-32 h-32 mx-auto ">
                                                @foreach ($item['ticket_qr_code'] as $qr )
                                                <img class="w-full h-full object-cover" src="{{$qr}}" alt="">
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div
                                class=" text-center">
                                <svg class="mx-auto" width="128" height="120" viewBox="0 0 128 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.2766 14.0379C10.4503 13.4008 9.47195 13.0909 8.50501 13.0909C7.16908 13.0909 5.84495 13.6759 4.96014 14.7939C3.42508 16.7288 3.76483 19.5265 5.72195 21.0414L6.73287 21.8247C6.80973 21.8204 6.88673 21.8182 6.96377 21.8182C7.83402 21.8182 8.71455 22.0996 9.45823 22.6781L109.358 100.779C109.879 101.186 110.273 101.696 110.533 102.256L116.721 107.051C118.69 108.572 121.514 108.224 123.038 106.295C124.573 104.361 124.233 101.563 122.276 100.048L119.726 98.0716C123.216 95.6312 125.499 91.5822 125.499 87V70.125C119.907 70.125 115.374 65.5919 115.374 60C115.374 54.408 119.907 49.875 125.499 49.875V33C125.499 25.5438 119.455 19.5 111.999 19.5H18.3256L11.2766 14.0379ZM95.867 100.5L87.233 93.75H17.499C13.777 93.75 10.749 90.722 10.749 87V75.4655C16.7042 72.8568 20.874 66.9065 20.874 60C20.874 53.0935 16.7042 47.1427 10.749 44.534V33.9551L4.56478 29.1203C4.19675 30.349 3.99902 31.6514 3.99902 33V49.875C9.59098 49.875 14.124 54.408 14.124 60C14.124 65.5919 9.59098 70.125 3.99902 70.125V87C3.99902 94.4558 10.0432 100.5 17.499 100.5H95.867ZM30.999 49.7865V73.5C30.999 77.2277 34.0213 80.25 37.749 80.25H69.9651L61.3311 73.5H37.749V55.0636L30.999 49.7865ZM53.1702 46.5L88.0148 73.5H91.749V46.5H53.1702ZM98.499 73.5C98.499 75.8959 97.2507 78.0004 95.3688 79.1983L113.825 93.4992C116.663 92.701 118.749 90.0896 118.749 87V75.4655C112.794 72.8568 108.624 66.9065 108.624 60C108.624 53.0935 112.794 47.1427 118.749 44.534V33C118.749 29.278 115.721 26.25 111.999 26.25H27.0368L44.4591 39.75H91.749C95.4771 39.75 98.499 42.7719 98.499 46.5V73.5Z" fill="#312C35" />
                                </svg>
                                <p class="text-h6 mb-1 mt-4">
                                    {{ __('No tickets found') }}
                                </p>
                                <p class="text-h7 text-gray_6">{{__("It seems you haven't purchased any tickets yet. Once you do, your tickets will be displayed here.")}}</p>
                            </div>
                            @endforelse
                            @endif
                        </div>
                        <div id="tab9" class="tab-pane2  hidden ">
                             @if(isset($ticket['past']->event))

                            @forelse ($ticket['past']->event as $item)
                            @if($item['event']['start_time'] < Carbon\Carbon::now())
                            <div class="grid grid-col-1 md:grid-cols-2  xl:grid-cols-3 gap-4 mt-4">
                                <div class="bg-light bg-opacity-5 rounded-2xl border border-primary_color_o10_1 overflow-hidden">
                                    <div class="h-60 md:h-32 overlay-tiket">
                                        <img class="w-full h-full object-cover" src="{{ url('images/upload/' . $item['event']['image']) }}" alt="">
                                    </div>
                                    <div class="px-1">
                                        <h5 class="font-medium">
                                            {{ $item['event']['name']}}11
                                        </h5>
                                        <div class="mt-2  mb-7 flex items-center justify-between">
                                            <div class="">
                                                <p class="flex gap-1  items-center">
                                                    <svg width="11" height="14" viewBox="0 0 11 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M7.5 5.6C7.5 6.725 6.6 7.6 5.5 7.6C4.375 7.6 3.5 6.725 3.5 5.6C3.5 4.5 4.375 3.6 5.5 3.6C6.6 3.6 7.5 4.5 7.5 5.6ZM5.5 6.8C6.15 6.8 6.7 6.275 6.7 5.6C6.7 4.95 6.15 4.4 5.5 4.4C4.825 4.4 4.3 4.95 4.3 5.6C4.3 6.275 4.825 6.8 5.5 6.8ZM10.3 5.6C10.3 7.8 7.375 11.675 6.075 13.3C5.775 13.675 5.2 13.675 4.9 13.3C3.6 11.675 0.7 7.8 0.7 5.6C0.7 2.95 2.825 0.8 5.5 0.8C8.15 0.8 10.3 2.95 10.3 5.6ZM5.5 1.6C3.275 1.6 1.5 3.4 1.5 5.6C1.5 6 1.625 6.525 1.9 7.2C2.175 7.85 2.55 8.55 3 9.25C3.85 10.625 4.85 11.95 5.5 12.75C6.125 11.95 7.125 10.625 7.975 9.25C8.425 8.55 8.8 7.85 9.075 7.2C9.35 6.525 9.5 6 9.5 5.6C9.5 3.4 7.7 1.6 5.5 1.6Z" fill="#A986BF" />
                                                    </svg>
                                                    <a target="_blank" href=" # "><span class="text-h8"> {{$item['event']['address']}}</span></a>
                                                </p>
                                                <p class="flex gap-1    items-center">
                                                    <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M3.1 0.8C3.3 0.8 3.5 1 3.5 1.2V2.4H8.3V1.2C8.3 1 8.475 0.8 8.7 0.8C8.9 0.8 9.1 1 9.1 1.2V2.4H9.9C10.775 2.4 11.5 3.125 11.5 4V5.625C11.35 5.625 11.225 5.6 11.1 5.6C10.95 5.6 10.825 5.625 10.7 5.625V5.6H1.1V12C1.1 12.45 1.45 12.8 1.9 12.8H7.7C7.95 13.125 8.225 13.375 8.55 13.6H1.9C1 13.6 0.3 12.9 0.3 12V4C0.3 3.125 1 2.4 1.9 2.4H2.7V1.2C2.7 1 2.875 0.8 3.1 0.8ZM9.9 3.2H1.9C1.45 3.2 1.1 3.575 1.1 4V4.8H10.7V4C10.7 3.575 10.325 3.2 9.9 3.2ZM11.075 8C11.3 8 11.475 8.2 11.475 8.4V9.6H12.3C12.5 9.6 12.7 9.8 12.7 10C12.7 10.225 12.5 10.4 12.3 10.4H11.075C10.875 10.4 10.675 10.225 10.675 10V8.4C10.675 8.2 10.875 8 11.075 8ZM7.5 10C7.5 8.025 9.1 6.4 11.1 6.4C13.075 6.4 14.7 8.025 14.7 10C14.7 12 13.075 13.6 11.1 13.6C9.1 13.6 7.5 12 7.5 10ZM11.1 12.8C12.625 12.8 13.9 11.55 13.9 10C13.9 8.475 12.625 7.2 11.1 7.2C9.55 7.2 8.3 8.475 8.3 10C8.3 11.55 9.55 12.8 11.1 12.8Z" fill="#A986BF" />
                                                    </svg>
                                                    <span class="text-h8 ">
                                                        {{ Carbon\Carbon::parse($item['event']['start_time'])->format('d M Y') }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div>
                                                <a href="" class="bg-gray_35 rounded-lg text-light text-h8 flex items-center gap-1  p-4-16">
                                                    <svg width="9" height="12" viewBox="0 0 9 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M4.5 0.5C2.29102 0.5 0.5 2.34699 0.5 4.625C0.5 6.28811 1.06185 6.7527 4.08919 11.2782C4.18848 11.4261 4.34423 11.5 4.5 11.5C4.65577 11.5 4.81152 11.4261 4.91081 11.2782C7.93815 6.7527 8.5 6.28811 8.5 4.625C8.5 2.34699 6.70898 0.5 4.5 0.5ZM4.5 10.6818C1.58192 6.33041 1.16667 5.99933 1.16667 4.625C1.16667 2.72958 2.66198 1.1875 4.5 1.1875C6.33802 1.1875 7.83333 2.72958 7.83333 4.625C7.83333 5.9974 7.42846 6.31526 4.5 10.6818ZM4.5 2.90625C3.57954 2.90625 2.83333 3.67573 2.83333 4.625C2.83333 5.57427 3.57954 6.34375 4.5 6.34375C5.4205 6.34375 6.16667 5.57427 6.16667 4.625C6.16667 3.67573 5.4205 2.90625 4.5 2.90625ZM4.5 5.65625C3.9486 5.65625 3.5 5.19363 3.5 4.625C3.5 4.05637 3.9486 3.59375 4.5 3.59375C5.0514 3.59375 5.5 4.05637 5.5 4.625C5.5 5.19363 5.0514 5.65625 4.5 5.65625Z" fill="#C4ACD3" />
                                                    </svg>
                                                    {{__('Map')}}</a>
                                            </div>
                                        </div>
                                        <div class="py-2 border-t-1 border-dashed border-gray_fb round-tiket">
                                            <div class="bg-primary_color_2   rounded-lg w-32 h-32 mx-auto ">
                                                @foreach ($item['ticket_qr_code'] as $qr )
                                                <img class="w-full h-full object-cover" src="{{$qr}}" alt="">
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             @endif
                            @empty
                            <div
                                class=" text-center">
                                <svg class="mx-auto" width="128" height="120" viewBox="0 0 128 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.2766 14.0379C10.4503 13.4008 9.47195 13.0909 8.50501 13.0909C7.16908 13.0909 5.84495 13.6759 4.96014 14.7939C3.42508 16.7288 3.76483 19.5265 5.72195 21.0414L6.73287 21.8247C6.80973 21.8204 6.88673 21.8182 6.96377 21.8182C7.83402 21.8182 8.71455 22.0996 9.45823 22.6781L109.358 100.779C109.879 101.186 110.273 101.696 110.533 102.256L116.721 107.051C118.69 108.572 121.514 108.224 123.038 106.295C124.573 104.361 124.233 101.563 122.276 100.048L119.726 98.0716C123.216 95.6312 125.499 91.5822 125.499 87V70.125C119.907 70.125 115.374 65.5919 115.374 60C115.374 54.408 119.907 49.875 125.499 49.875V33C125.499 25.5438 119.455 19.5 111.999 19.5H18.3256L11.2766 14.0379ZM95.867 100.5L87.233 93.75H17.499C13.777 93.75 10.749 90.722 10.749 87V75.4655C16.7042 72.8568 20.874 66.9065 20.874 60C20.874 53.0935 16.7042 47.1427 10.749 44.534V33.9551L4.56478 29.1203C4.19675 30.349 3.99902 31.6514 3.99902 33V49.875C9.59098 49.875 14.124 54.408 14.124 60C14.124 65.5919 9.59098 70.125 3.99902 70.125V87C3.99902 94.4558 10.0432 100.5 17.499 100.5H95.867ZM30.999 49.7865V73.5C30.999 77.2277 34.0213 80.25 37.749 80.25H69.9651L61.3311 73.5H37.749V55.0636L30.999 49.7865ZM53.1702 46.5L88.0148 73.5H91.749V46.5H53.1702ZM98.499 73.5C98.499 75.8959 97.2507 78.0004 95.3688 79.1983L113.825 93.4992C116.663 92.701 118.749 90.0896 118.749 87V75.4655C112.794 72.8568 108.624 66.9065 108.624 60C108.624 53.0935 112.794 47.1427 118.749 44.534V33C118.749 29.278 115.721 26.25 111.999 26.25H27.0368L44.4591 39.75H91.749C95.4771 39.75 98.499 42.7719 98.499 46.5V73.5Z" fill="#312C35" />
                                </svg>
                                <p class="text-h6 mb-1 mt-4">
                                    {{ __('No tickets found') }}
                                </p>
                                <p class="text-h7 text-gray_6">{{__("It seems you haven't purchased any tickets yet. Once you do, your tickets will be displayed here.")}}</p>
                            </div>
                           
                            @endforelse
                            @endif
                            
                        </div>
                    </div>
                </div>
                <div id="tab1" class="tab-pane hidden  ">
                    <p class="text-h5 lg:text-h3 mb-4"> {{__('Profile settings')}}</p>
                    <div class="mb-4 flex border rounded-5xl border-primary_color_a11 justify-center md:w-fit mx-auto md:p-1 gap-1 w-full">
                        <button class="tab-button2 flex items-center justify-center gap-1 text-white px-3 md:px-2 py-3 rounded-5xl focus:outline-none  md:text-h6 text-h7 active flex-1 md:flex-none" data-tab="tab6">
                            {{__('Profile settings')}}</button>
                        <button class="tab-button2 flex items-center justify-center gap-1 text-white px-3 md:px-2 py-3 rounded-5xl focus:outline-none  md:text-h6 text-h7  flex-1 md:flex-none" data-tab="tab7">
                            {{__('Change password')}} </button>
                    </div>
                    <div class="tab-content2 ">
                        <div id="tab6" class="tab-pane2  hidden ">
                            <form action="{{ url('update_user_profile') }}" method="post">
                                @csrf
                                <div class="flex gap-2 flex-col md:flex-row mb-4">
                                    <div class="flex-1">
                                        <label for="fname"
                                            class="mb-1 block md:text-h5 text-h6">{{ __('First Name') }}</label>
                                        <input required type="text" name="name" id="" value="{{ $user->name }}"
                                            class="bg-dark_1 p-3 md:p-16-16 w-full focus:border-primary_color_6 outline-0 rounded-lg border border-primary_color_o10_1"
                                            placeholder="Deo">
                                    </div>
                                    <div class="flex-1">
                                        <label for="lname"
                                            class="mb-1 block md:text-h5 text-h6">{{ __('Last Name') }}</label>
                                        <input type="text" name="last_name" id="" value="{{ $user->last_name }}"
                                            class="bg-dark_1 p-3 md:p-16-16 w-full focus:border-primary_color_6 outline-0 rounded-lg border border-primary_color_o10_1"
                                            placeholder="Deo">
                                    </div>
                                </div>
                                <div class="flex gap-2 flex-col md:flex-row mb-4">
                                    <div class="flex-1">
                                        <label for="number"
                                            class="mb-1 block md:text-h5 text-h6">{{ __('Contact Number') }}</label>
                                        <input type="text" name="phone" id="" value="{{ $user->phone }}"
                                            class="bg-dark_1 p-3 md:p-16-16 w-full focus:border-primary_color_6 outline-0 rounded-lg border border-primary_color_o10_1"
                                            placeholder="john@gmail.com">
                                    </div>
                                    <div class="flex-1">
                                        <label for="email"
                                            class="mb-1 block md:text-h5 text-h6">{{ __('Email address') }}</label>
                                        <input type="email" name="email" id="" value="{{ $user->email }}" disabled
                                            class="bg-dark_1 p-3 md:p-16-16 w-full focus:border-primary_color_6 outline-0 rounded-lg border border-primary_color_o10_1"
                                            placeholder="john@gmail.com">
                                    </div>
                                </div>
                                <div class="flex gap-2 flex-col md:flex-row mb-4">
                                    <div class=" flex-1">
                                        <label for="bio"
                                            class="mb-1 block md:text-h5 text-h6">{{ __('Address') }}</label>
                                        <textarea id="message" rows="4" name="address"
                                            class="bg-dark_1 p-3 md:p-16-16 w-full focus:border-primary_color_6 outline-0 rounded-lg border border-primary_color_o10_1  md:text-h5 text-h6"
                                            placeholder="{{ __('Type your address') }}">{{ $user->address }}</textarea>
                                    </div>
                                    <div class=" flex-1">
                                        <label for="bio"
                                            class="mb-1 block md:text-h5 text-h6">{{ __('Bio') }}</label>
                                        <textarea id="message" rows="4" name="bio"
                                            class="bg-dark_1 p-3 md:p-16-16 w-full focus:border-primary_color_6 outline-0 rounded-lg border border-primary_color_o10_1"
                                            placeholder="{{ __('Type your bio') }}">{{ $user->bio }}</textarea>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button
                                        class="bg-primary_color_8 text-white  px-5 py-2 rounded-6xl w-full md:w-56">{{ __('Save Change') }}</button>
                                </div>
                            </form>
                        </div>
                        <div id="tab7" class="tab-pane2 hidden text-center">
                            <h3 class=" text-h5 lg:text-h3 text-primary_color_5 mt-2"> {{__('Change password?')}} </h3>
                            <p class=" mt-1 mb-8 text-h6 lg:text-h5"> {{__('Click down to change your password.')}}</p>
                            <button class=" text-white px-2 py-3 rounded-5xl focus:outline-none text-h5 rounded-5xl bg-primary_color_8  ">{{__('Reset password now')}}</button>
                            <div class="mt-4 p-2 respo border border-yelow rounded-lg flex items-center gap-2">
                                <div>
                                    <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.3" d="M13.9535 30.0759L7.45102 24.7634C6.89499 24.3143 6.44621 23.7468 6.1374 23.1022C5.82859 22.4576 5.66755 21.7522 5.66602 21.0375V10.625C5.66654 9.7 5.9342 8.7948 6.43683 8.01825C6.93946 7.24169 7.65566 6.62681 8.49935 6.24752L15.016 3.27252C15.6429 2.9858 16.3242 2.8374 17.0135 2.8374C17.7029 2.8374 18.3841 2.9858 19.011 3.27252L25.4993 6.24752C26.343 6.62681 27.0592 7.24169 27.5619 8.01825C28.0645 8.7948 28.3322 9.7 28.3327 10.625V21.0375C28.3329 21.7511 28.174 22.4558 27.8677 23.1003C27.5614 23.7448 27.1153 24.3129 26.5618 24.7634L20.0594 30.0759C19.2007 30.7865 18.121 31.1753 17.0064 31.1753C15.8918 31.1753 14.8122 30.7865 13.9535 30.0759Z" fill="#C59A00" />
                                        <path d="M16.0209 20.6255L21.7074 15.0886C21.8984 14.8973 22.0058 14.638 22.0058 14.3676C22.0058 14.0972 21.8984 13.8379 21.7074 13.6466C21.5161 13.4555 21.2567 13.3482 20.9864 13.3482C20.716 13.3482 20.4566 13.4555 20.2653 13.6466L15.3271 18.4896L13.9667 17.1292C13.8875 17.001 13.781 16.8919 13.6547 16.8098C13.5284 16.7277 13.3855 16.6745 13.2362 16.6542C13.0869 16.6338 12.935 16.6468 12.7913 16.6921C12.6476 16.7375 12.5158 16.8141 12.4052 16.9165C12.2947 17.0188 12.2082 17.1444 12.1519 17.2842C12.0957 17.4239 12.0711 17.5744 12.0799 17.7248C12.0887 17.8752 12.1307 18.0218 12.2029 18.154C12.2751 18.2863 12.3757 18.4009 12.4974 18.4896L14.5788 20.6255C14.6744 20.7237 14.7886 20.802 14.9147 20.8557C15.0408 20.9094 15.1764 20.9375 15.3135 20.9384C15.4462 20.9354 15.577 20.9063 15.6984 20.8526C15.8198 20.7989 15.9294 20.7217 16.0209 20.6255Z" fill="#C59A00" />
                                    </svg>
                                </div>
                                <div class="text-start">
                                    <h5 class="text-gray_b5 font-bold  text-h6 md:text-h5"> {{__('Check your email')}}
                                    </h5>
                                    <p class="text-h6 text-gray_b5 mt-1">{{__('An email has been sent to you with detailed instructions on how to reset your password.')}}</p>
                                </div>
                            </div>
                            <div class="md:text-h4 text-h5 mt-4"> <a href="">{{__('Resend code')}}</a> <span class="text-primary_color_6">00:45</span></div>
                        </div>
                    </div>
                </div>
                <div id="tab2" class="tab-pane hidden  ">
                    <p class="text-h5 lg:text-h3  mb-4"> {{__('Password')}}</p>
                </div>
                
                <div id="tab5" class="tab-pane hidden  ">
                    <p class="text-h5 lg:text-h3  mb-4"> {{__('Favorites')}}</p>
                    <div
                        class=" text-center">
                        <svg class="mx-auto" width="128" height="120" viewBox="0 0 128 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.2766 14.0379C10.4503 13.4008 9.47195 13.0909 8.50501 13.0909C7.16908 13.0909 5.84495 13.6759 4.96014 14.7939C3.42508 16.7288 3.76483 19.5265 5.72195 21.0414L6.73287 21.8247C6.80973 21.8204 6.88673 21.8182 6.96377 21.8182C7.83402 21.8182 8.71455 22.0996 9.45823 22.6781L109.358 100.779C109.879 101.186 110.273 101.696 110.533 102.256L116.721 107.051C118.69 108.572 121.514 108.224 123.038 106.295C124.573 104.361 124.233 101.563 122.276 100.048L119.726 98.0716C123.216 95.6312 125.499 91.5822 125.499 87V70.125C119.907 70.125 115.374 65.5919 115.374 60C115.374 54.408 119.907 49.875 125.499 49.875V33C125.499 25.5438 119.455 19.5 111.999 19.5H18.3256L11.2766 14.0379ZM95.867 100.5L87.233 93.75H17.499C13.777 93.75 10.749 90.722 10.749 87V75.4655C16.7042 72.8568 20.874 66.9065 20.874 60C20.874 53.0935 16.7042 47.1427 10.749 44.534V33.9551L4.56478 29.1203C4.19675 30.349 3.99902 31.6514 3.99902 33V49.875C9.59098 49.875 14.124 54.408 14.124 60C14.124 65.5919 9.59098 70.125 3.99902 70.125V87C3.99902 94.4558 10.0432 100.5 17.499 100.5H95.867ZM30.999 49.7865V73.5C30.999 77.2277 34.0213 80.25 37.749 80.25H69.9651L61.3311 73.5H37.749V55.0636L30.999 49.7865ZM53.1702 46.5L88.0148 73.5H91.749V46.5H53.1702ZM98.499 73.5C98.499 75.8959 97.2507 78.0004 95.3688 79.1983L113.825 93.4992C116.663 92.701 118.749 90.0896 118.749 87V75.4655C112.794 72.8568 108.624 66.9065 108.624 60C108.624 53.0935 112.794 47.1427 118.749 44.534V33C118.749 29.278 115.721 26.25 111.999 26.25H27.0368L44.4591 39.75H91.749C95.4771 39.75 98.499 42.7719 98.499 46.5V73.5Z" fill="#312C35" />
                        </svg>
                        <p class="text-h6 mb-1 mt-4">
                            {{ __('No tickets found') }}
                        </p>
                        <p class="text-h7 text-gray_6">{{__("It seems you haven't purchased any tickets yet. Once you do, your tickets will be displayed here.")}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabPanes = document.querySelectorAll('.tab-pane');
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');

                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));

                button.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Activate the first tab by default
        tabButtons[3].classList.add('active');
        tabPanes[0].classList.add('active');
    });

    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons2 = document.querySelectorAll('.tab-button2');
        const tabPanes2 = document.querySelectorAll('.tab-pane2');
        tabButtons2.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                console.log(document.getElementById(tabId));
                tabButtons2.forEach(btn => btn.classList.remove('active'));
                tabPanes2.forEach(pane => pane.classList.remove('active'));

                button.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });
        // tabButtons2[3].classList.add('active');
        tabPanes2[0].classList.add('active');

    });
</script>


@endsection