@extends('front.master', ['activePage' => 'event'])
@section('title', __('Event Details'))
@php
    $gmapkey = \App\Models\Setting::find(1)->map_key;
@endphp

@section("content")

    <div class="container flex flex-col items-center justify-center" id="organizerProfile">
        <div class="organization-profile">
            <!-- Profile Image with Gradient Border -->
            <div class="profile-image-wrapper">
                <img
                        src="{{ $organizer["imagePath"]  }}"
                        class="profile-image"
                        alt="{{ $organizer['organization_name'] }}"
                >
            </div>

            <!-- Content Section -->
            <div class="content">
                <h3 class="organizer-name">
                    {{ $organizer['organization_name'] }}
                </h3>
                <p class="description">{{ $organizer['organization_name'] }}</p>
            </div>
        </div>

        <div class="flex flex-row gap-5 md:gap-7 mb-3 justify-center">
            <a href="">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.7188 9.25C17.7188 13.6094 14.5195 17.2305 10.3359 17.8633V11.7812H12.375L12.7617 9.25H10.3359V7.63281C10.3359 6.92969 10.6875 6.26172 11.7773 6.26172H12.8672V4.11719C12.8672 4.11719 11.8828 3.94141 10.8984 3.94141C8.92969 3.94141 7.62891 5.17188 7.62891 7.35156V9.25H5.41406V11.7812H7.62891V17.8633C3.44531 17.2305 0.28125 13.6094 0.28125 9.25C0.28125 4.43359 4.18359 0.53125 9 0.53125C13.8164 0.53125 17.7188 4.43359 17.7188 9.25Z"
                          fill="#A986BF"></path>
                </svg>
            </a>
            <a href="">
                <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.4844 2.69531C14.9609 4.17188 15.875 6.10547 15.875 8.21484C15.875 12.5039 12.2891 16.0195 7.96484 16.0195C6.66406 16.0195 5.39844 15.668 4.23828 15.0703L0.125 16.125L1.21484 12.082C0.546875 10.9219 0.160156 9.58594 0.160156 8.17969C0.160156 3.89062 3.67578 0.375 7.96484 0.375C10.0742 0.375 12.043 1.21875 13.4844 2.69531ZM7.96484 14.6836C11.5508 14.6836 14.5391 11.7656 14.5391 8.21484C14.5391 6.45703 13.8008 4.83984 12.5703 3.60938C11.3398 2.37891 9.72266 1.71094 8 1.71094C4.41406 1.71094 1.49609 4.62891 1.49609 8.17969C1.49609 9.41016 1.84766 10.6055 2.48047 11.6602L2.65625 11.9062L1.98828 14.2969L4.44922 13.6289L4.66016 13.7695C5.67969 14.3672 6.80469 14.6836 7.96484 14.6836ZM11.5508 9.83203C11.7266 9.9375 11.8672 9.97266 11.9023 10.0781C11.9727 10.1484 11.9727 10.5352 11.7969 10.9922C11.6211 11.4492 10.8477 11.8711 10.4961 11.9062C9.86328 12.0117 9.37109 11.9766 8.14062 11.4141C6.17188 10.5703 4.90625 8.60156 4.80078 8.49609C4.69531 8.35547 4.02734 7.44141 4.02734 6.45703C4.02734 5.50781 4.51953 5.05078 4.69531 4.83984C4.87109 4.62891 5.08203 4.59375 5.22266 4.59375C5.32812 4.59375 5.46875 4.59375 5.57422 4.59375C5.71484 4.59375 5.85547 4.55859 6.03125 4.94531C6.17188 5.33203 6.59375 6.28125 6.62891 6.38672C6.66406 6.49219 6.69922 6.59766 6.62891 6.73828C6.27734 7.47656 5.85547 7.44141 6.06641 7.79297C6.83984 9.09375 7.57812 9.55078 8.73828 10.1133C8.91406 10.2188 9.01953 10.1836 9.16016 10.0781C9.26562 9.9375 9.65234 9.48047 9.75781 9.30469C9.89844 9.09375 10.0391 9.12891 10.2148 9.19922C10.3906 9.26953 11.3398 9.72656 11.5508 9.83203Z"
                          fill="#A986BF"></path>
                </svg>
            </a>
            <a href="">
                <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.1367 4.59375C16.1367 4.76953 16.1367 4.91016 16.1367 5.08594C16.1367 9.97266 12.4453 15.5625 5.66016 15.5625C3.55078 15.5625 1.61719 14.9648 0 13.9102C0.28125 13.9453 0.5625 13.9805 0.878906 13.9805C2.60156 13.9805 4.18359 13.3828 5.44922 12.3984C3.83203 12.3633 2.46094 11.3086 2.00391 9.83203C2.25 9.86719 2.46094 9.90234 2.70703 9.90234C3.02344 9.90234 3.375 9.83203 3.65625 9.76172C1.96875 9.41016 0.703125 7.93359 0.703125 6.14062V6.10547C1.19531 6.38672 1.79297 6.52734 2.39062 6.5625C1.37109 5.89453 0.738281 4.76953 0.738281 3.50391C0.738281 2.80078 0.914062 2.16797 1.23047 1.64062C3.05859 3.85547 5.80078 5.33203 8.85938 5.50781C8.78906 5.22656 8.75391 4.94531 8.75391 4.66406C8.75391 2.625 10.4062 0.972656 12.4453 0.972656C13.5 0.972656 14.4492 1.39453 15.1523 2.13281C15.9609 1.95703 16.7695 1.64062 17.4727 1.21875C17.1914 2.09766 16.6289 2.80078 15.8555 3.25781C16.5938 3.1875 17.332 2.97656 17.9648 2.69531C17.4727 3.43359 16.8398 4.06641 16.1367 4.59375Z"
                          fill="#A986BF"></path>
                </svg>
            </a>
            <a href="">
                <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 4.20703C10.2148 4.20703 12.043 6.03516 12.043 8.25C12.043 10.5 10.2148 12.293 8 12.293C5.75 12.293 3.95703 10.5 3.95703 8.25C3.95703 6.03516 5.75 4.20703 8 4.20703ZM8 10.8867C9.44141 10.8867 10.6016 9.72656 10.6016 8.25C10.6016 6.80859 9.44141 5.64844 8 5.64844C6.52344 5.64844 5.36328 6.80859 5.36328 8.25C5.36328 9.72656 6.55859 10.8867 8 10.8867ZM13.1328 4.06641C13.1328 3.53906 12.7109 3.11719 12.1836 3.11719C11.6562 3.11719 11.2344 3.53906 11.2344 4.06641C11.2344 4.59375 11.6562 5.01562 12.1836 5.01562C12.7109 5.01562 13.1328 4.59375 13.1328 4.06641ZM15.8047 5.01562C15.875 6.31641 15.875 10.2188 15.8047 11.5195C15.7344 12.7852 15.4531 13.875 14.5391 14.8242C13.625 15.7383 12.5 16.0195 11.2344 16.0898C9.93359 16.1602 6.03125 16.1602 4.73047 16.0898C3.46484 16.0195 2.375 15.7383 1.42578 14.8242C0.511719 13.875 0.230469 12.7852 0.160156 11.5195C0.0898438 10.2188 0.0898438 6.31641 0.160156 5.01562C0.230469 3.75 0.511719 2.625 1.42578 1.71094C2.375 0.796875 3.46484 0.515625 4.73047 0.445312C6.03125 0.375 9.93359 0.375 11.2344 0.445312C12.5 0.515625 13.625 0.796875 14.5391 1.71094C15.4531 2.625 15.7344 3.75 15.8047 5.01562ZM14.1172 12.8906C14.5391 11.8711 14.4336 9.41016 14.4336 8.25C14.4336 7.125 14.5391 4.66406 14.1172 3.60938C13.8359 2.94141 13.3086 2.37891 12.6406 2.13281C11.5859 1.71094 9.125 1.81641 8 1.81641C6.83984 1.81641 4.37891 1.71094 3.35938 2.13281C2.65625 2.41406 2.12891 2.94141 1.84766 3.60938C1.42578 4.66406 1.53125 7.125 1.53125 8.25C1.53125 9.41016 1.42578 11.8711 1.84766 12.8906C2.12891 13.5938 2.65625 14.1211 3.35938 14.4023C4.37891 14.8242 6.83984 14.7188 8 14.7188C9.125 14.7188 11.5859 14.8242 12.6406 14.4023C13.3086 14.1211 13.8711 13.5938 14.1172 12.8906Z"
                          fill="#A986BF"></path>
                </svg>
            </a>
        </div>
    </div>

    <div class="container mt-9 md:mt-40 xl:mt-m133 hidden lg:block " id="UpcomingEventsSection">
        <div class="flex justify-between flex-wrap gap-y-4">
            <h2 class="text-h5 lg:text-h2 text-primary_color_6 lg:text-white font-medium">{{__('Upcoming Events')}}</h2>
        </div>
        <div class="mt-4 hidden text-center" id="empty_upcoming_search">
            <svg class="mx-auto" width="128" height="120" viewBox="0 0 128 120" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M11.2766 14.0379C10.4503 13.4008 9.47195 13.0909 8.50501 13.0909C7.16908 13.0909 5.84495 13.6759 4.96014 14.7939C3.42508 16.7288 3.76483 19.5265 5.72195 21.0414L6.73287 21.8247C6.80973 21.8204 6.88673 21.8182 6.96377 21.8182C7.83402 21.8182 8.71455 22.0996 9.45823 22.6781L109.358 100.779C109.879 101.186 110.273 101.696 110.533 102.256L116.721 107.051C118.69 108.572 121.514 108.224 123.038 106.295C124.573 104.361 124.233 101.563 122.276 100.048L119.726 98.0716C123.216 95.6312 125.499 91.5822 125.499 87V70.125C119.907 70.125 115.374 65.5919 115.374 60C115.374 54.408 119.907 49.875 125.499 49.875V33C125.499 25.5438 119.455 19.5 111.999 19.5H18.3256L11.2766 14.0379ZM95.867 100.5L87.233 93.75H17.499C13.777 93.75 10.749 90.722 10.749 87V75.4655C16.7042 72.8568 20.874 66.9065 20.874 60C20.874 53.0935 16.7042 47.1427 10.749 44.534V33.9551L4.56478 29.1203C4.19675 30.349 3.99902 31.6514 3.99902 33V49.875C9.59098 49.875 14.124 54.408 14.124 60C14.124 65.5919 9.59098 70.125 3.99902 70.125V87C3.99902 94.4558 10.0432 100.5 17.499 100.5H95.867ZM30.999 49.7865V73.5C30.999 77.2277 34.0213 80.25 37.749 80.25H69.9651L61.3311 73.5H37.749V55.0636L30.999 49.7865ZM53.1702 46.5L88.0148 73.5H91.749V46.5H53.1702ZM98.499 73.5C98.499 75.8959 97.2507 78.0004 95.3688 79.1983L113.825 93.4992C116.663 92.701 118.749 90.0896 118.749 87V75.4655C112.794 72.8568 108.624 66.9065 108.624 60C108.624 53.0935 112.794 47.1427 118.749 44.534V33C118.749 29.278 115.721 26.25 111.999 26.25H27.0368L44.4591 39.75H91.749C95.4771 39.75 98.499 42.7719 98.499 46.5V73.5Z"
                      fill="#312C35"></path>
            </svg>
            <p class="text-h6 mb-1 mt-4">{{__('No events found')}} </p>
        </div>
        <div class="spinner spinner_upcoming_events hidden mb-2" id="spinner" style="display: block;">
            <div><span></span><span></span><span></span></div>
        </div>
        <div class="mt-16 grid grid-cols-2 lg:grid-cols-3 gap-2 xl:gap-x-g32 xl:gap-y-g32 upcomingEventsCon"
             id="upcomingEventsCon">
        </div>
        <div class="flex justify-center">
            <button id="load_more_upcoming"
                    onclick="loadMoreUpComping()"
                    data-page="1"
                    class="font-medium mt-16  rounded-5xl border border-light  bg-gray_f text-center    py-2 px-4 lg:px-p32    lg:w-48 f-bri l leading-5  inline-block">
                {{__('Load More')}}</button>
        </div>
    </div>

    <div class="container mt-9 md:mt-40 xl:mt-m133 hidden lg:block" id="RecentGallerySection">
        <div class="flex justify-between flex-wrap gap-y-4">
            <h2 class="text-h5 lg:text-h2 text-primary_color_6 lg:text-white font-medium">{{__('Recent events gallery')}}</h2>
        </div>
        <div class="mt-4 {{ $recentGalleries->count() ? 'hidden' : '' }} text-center" id="empty_recent_gallery">
            <svg class="mx-auto" width="128" height="120" viewBox="0 0 128 120" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M11.2766 14.0379C10.4503 13.4008 9.47195 13.0909 8.50501 13.0909C7.16908 13.0909 5.84495 13.6759 4.96014 14.7939C3.42508 16.7288 3.76483 19.5265 5.72195 21.0414L6.73287 21.8247C6.80973 21.8204 6.88673 21.8182 6.96377 21.8182C7.83402 21.8182 8.71455 22.0996 9.45823 22.6781L109.358 100.779C109.879 101.186 110.273 101.696 110.533 102.256L116.721 107.051C118.69 108.572 121.514 108.224 123.038 106.295C124.573 104.361 124.233 101.563 122.276 100.048L119.726 98.0716C123.216 95.6312 125.499 91.5822 125.499 87V70.125C119.907 70.125 115.374 65.5919 115.374 60C115.374 54.408 119.907 49.875 125.499 49.875V33C125.499 25.5438 119.455 19.5 111.999 19.5H18.3256L11.2766 14.0379ZM95.867 100.5L87.233 93.75H17.499C13.777 93.75 10.749 90.722 10.749 87V75.4655C16.7042 72.8568 20.874 66.9065 20.874 60C20.874 53.0935 16.7042 47.1427 10.749 44.534V33.9551L4.56478 29.1203C4.19675 30.349 3.99902 31.6514 3.99902 33V49.875C9.59098 49.875 14.124 54.408 14.124 60C14.124 65.5919 9.59098 70.125 3.99902 70.125V87C3.99902 94.4558 10.0432 100.5 17.499 100.5H95.867ZM30.999 49.7865V73.5C30.999 77.2277 34.0213 80.25 37.749 80.25H69.9651L61.3311 73.5H37.749V55.0636L30.999 49.7865ZM53.1702 46.5L88.0148 73.5H91.749V46.5H53.1702ZM98.499 73.5C98.499 75.8959 97.2507 78.0004 95.3688 79.1983L113.825 93.4992C116.663 92.701 118.749 90.0896 118.749 87V75.4655C112.794 72.8568 108.624 66.9065 108.624 60C108.624 53.0935 112.794 47.1427 118.749 44.534V33C118.749 29.278 115.721 26.25 111.999 26.25H27.0368L44.4591 39.75H91.749C95.4771 39.75 98.499 42.7719 98.499 46.5V73.5Z"
                      fill="#312C35"></path>
            </svg>
            <p class="text-h6 mb-1 mt-4">{{__('No events found')}} </p>
        </div>
        <div class="spinner  spinner_recent_gallery hidden mb-2" id="spinner" style="display: none;">
            <div><span></span><span></span><span></span></div>
        </div>
        <div class="mt-16 gap-2 xl:gap-x-g32 xl:gap-y-g32 recentEventsCon swiper-container"
             id="recentEventsCon">
            <div class="swiper-wrapper">
                @foreach($recentGalleries as $gallery)
                    @foreach(explode(",", $gallery["gallery"]) as $img)
                        <div class="ticket-wahlist h-full swiper-slide bg-light hover:bg-primary_color_o25_9 bg-opacity-5 rounded-2xl border border-primary_color_o10_1 hover:border-gray_9 overflow-hidden">
                            <div class="h-100 md:h-48">
                                <img src="{{$gallery["imagePath"]. $img }}"
                                     loading="lazy"
                                     class='w-full h-full object-cover' alt="{{ $gallery["eventName"] }}"/>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

            <div class="swiper-scrollbar"></div>
        </div>
    </div>


    <div class="container mt-9 md:mt-40 xl:mt-m133 hidden lg:block " id="PreviousEventsSection">
        <div class="flex justify-between flex-wrap gap-y-4">
            <h2 class="text-h5 lg:text-h2 text-primary_color_6 lg:text-white font-medium">{{__('Previous Events')}}</h2>
        </div>
        <div class="mt-4 hidden text-center" id="empty_previous_search">
            <svg class="mx-auto" width="128" height="120" viewBox="0 0 128 120" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M11.2766 14.0379C10.4503 13.4008 9.47195 13.0909 8.50501 13.0909C7.16908 13.0909 5.84495 13.6759 4.96014 14.7939C3.42508 16.7288 3.76483 19.5265 5.72195 21.0414L6.73287 21.8247C6.80973 21.8204 6.88673 21.8182 6.96377 21.8182C7.83402 21.8182 8.71455 22.0996 9.45823 22.6781L109.358 100.779C109.879 101.186 110.273 101.696 110.533 102.256L116.721 107.051C118.69 108.572 121.514 108.224 123.038 106.295C124.573 104.361 124.233 101.563 122.276 100.048L119.726 98.0716C123.216 95.6312 125.499 91.5822 125.499 87V70.125C119.907 70.125 115.374 65.5919 115.374 60C115.374 54.408 119.907 49.875 125.499 49.875V33C125.499 25.5438 119.455 19.5 111.999 19.5H18.3256L11.2766 14.0379ZM95.867 100.5L87.233 93.75H17.499C13.777 93.75 10.749 90.722 10.749 87V75.4655C16.7042 72.8568 20.874 66.9065 20.874 60C20.874 53.0935 16.7042 47.1427 10.749 44.534V33.9551L4.56478 29.1203C4.19675 30.349 3.99902 31.6514 3.99902 33V49.875C9.59098 49.875 14.124 54.408 14.124 60C14.124 65.5919 9.59098 70.125 3.99902 70.125V87C3.99902 94.4558 10.0432 100.5 17.499 100.5H95.867ZM30.999 49.7865V73.5C30.999 77.2277 34.0213 80.25 37.749 80.25H69.9651L61.3311 73.5H37.749V55.0636L30.999 49.7865ZM53.1702 46.5L88.0148 73.5H91.749V46.5H53.1702ZM98.499 73.5C98.499 75.8959 97.2507 78.0004 95.3688 79.1983L113.825 93.4992C116.663 92.701 118.749 90.0896 118.749 87V75.4655C112.794 72.8568 108.624 66.9065 108.624 60C108.624 53.0935 112.794 47.1427 118.749 44.534V33C118.749 29.278 115.721 26.25 111.999 26.25H27.0368L44.4591 39.75H91.749C95.4771 39.75 98.499 42.7719 98.499 46.5V73.5Z"
                      fill="#312C35"></path>
            </svg>
            <p class="text-h6 mb-1 mt-4">{{__('No events found')}} </p>
        </div>
        <div class="spinner spinner_previous_events hidden mb-2" id="spinner" style="display: block;">
            <div><span></span><span></span><span></span></div>
        </div>
        <div class="mt-16 grid grid-cols-2 lg:grid-cols-3 gap-2 xl:gap-x-g32 xl:gap-y-g32 previousEventsCon"
             id="PreviousEventsCon">
        </div>
        <div class="flex justify-center">
            <button id="load_more_previous"
                    onclick="loadMorePrevious()"
                    data-page="1"
                    class="font-medium mt-16  rounded-5xl border border-light  bg-gray_f text-center    py-2 px-4 lg:px-p32    lg:w-48 f-bri l leading-5  inline-block">
                {{__('Load More')}}</button>
        </div>
    </div>

    <div class="container mt-12 md:mt-32">
        <div class="bg-light bg-opacity-5 rounded-2xl border border-primary_color_o10_1  p-2  md:p-32-24 mt-4 lg:mt-8"
             id="aboutOrganizer">
            <h3 class="text-primary_color_6 font-medium text-h4 lg:text-h3">{{ __('About') . " ". $organizer["organization_name"] }}</h3>
            <p class="h5 lg:h4 mt-4 paragraph-3 max-h-96 overflow-x-auto ">
                {{ $organizer["organization_name"] }}
            </p>
        </div>
    </div>
@endsection

@push("after-styles")
    <style>
        .organization-profile {
            display: flex;
            flex-direction: column; /* Stack image and content */
            justify-content: center;
            align-items: center;
            text-align: center; /* Center align the content */
            background-color: #121212; /* Optional background */
            padding: 2rem; /* Space around the profile */
            gap: 1rem; /* Spacing between image and content */
        }

        /* Profile Image with Gradient Border */
        .organization-profile .profile-image-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 15%; /* Adjust for dynamic size */
            aspect-ratio: 1/1; /* Keeps it circular */
            border-radius: 50%; /* Circular border */
            background: conic-gradient(
                    #d7723d, #b3a248, #e6732f, #b95bbb
            ); /* Instagram gradient */
            padding: 0.6rem; /* Thickness of the gradient border */
            position: relative;
        }

        /* Inner Circle Background */
        .organization-profile .profile-image-wrapper::before {
            content: "";
            position: absolute;
            width: 94%; /* Slightly smaller to create the inner gap */
            height: 94%;
            background-color: #000; /* Inner circle background */
            border-radius: 50%;
            z-index: 1;
        }

        /* Profile Image */
        .organization-profile .profile-image {
            width: 99%; /* Adjust dynamically */
            aspect-ratio: 1/1; /* Circular ratio */
            border-radius: 50%; /* Makes image circular */
            object-fit: cover;
            z-index: 2; /* Place image above pseudo-element */
        }

        .organization-profile .content {
            width: 100%;
        }

        .organization-profile .organizer-name {
            font-size: 1.8rem;
            font-weight: bold;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Description */
        .organization-profile .description {
            font-size: 1rem;
            color: #d1d5db; /* Light gray color */
            max-width: 50%; /* Limit the paragraph width for readability */
            line-height: 1.5;
            margin: 0.5rem auto 0 auto;
        }

        body {
            overflow-x: hidden;
        }
    </style>
@endpush
@push("after-scripts")
    <script>
        let upcomingLimit = 6;
        let previousLimit = 6;

        const fetchOrganizerUpcomingEvent = async () => {
            var ovals = {};
            let page = $("#load_more_upcoming").data('page');
            ovals["user_id"] = "{{ $organizer["id"] }}";
            ovals["limit"] = upcomingLimit;
            $Ajax("fetchOrganizerUpComingEvent", ovals, page);
            setTimeout(initializeEventSwiper, 1000);
        }

        const loadMoreUpComping = async () => {
            await fetchOrganizerUpcomingEvent();
        }

        const fetchOrganizerPreviousEvent = async () => {
            var ovals = {};
            let page = $("#load_more_previous").data('page');
            ovals["user_id"] = "{{ $organizer["id"] }}";
            ovals["limit"] = previousLimit;
            $Ajax("fetchOrganizerUpPreviousEvent", ovals, page);
            setTimeout(initializeEventSwiper, 1000);
        }

        const loadMorePrevious = async () => {
            await fetchOrganizerPreviousEvent();
        }


        $(document).ready(async () => {
            await fetchOrganizerUpcomingEvent();
            await fetchOrganizerPreviousEvent();
        });

        const initializeEventSwiper = () => {
            let swiperEvent = new Swiper(".swiper-event", {
                slidesPerView: 1,
                loop: true,
                autoplay: {
                    delay: 1200,
                    disableOnInteraction: false,
                },
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            initializeEventSwiper();
        });
    </script>
    <script>

        const swiperee = new Swiper('.swiper-container', {
            loop: true, // Enable looping
            scrollbar: {
                el: '.swiper-scrollbar',
                draggable: true,
            },
            slidesPerView: 2,
            spaceBetween: 20,
            autoplay: {
                delay: 1200,
                disableOnInteraction: false,
            },
        });
    </script>

@endpush