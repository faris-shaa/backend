@php
    $admin = \App\Models\User::find(1);
    if (\App\Models\Category::find(1)) {
        $category = \App\Models\category::where('status', 1)->orderBy('id',"DESC")->get();
    }
    $user = Auth::guard('appuser')->user();
    $logo = \App\Models\Setting::find(1)->logo;
    $wallet = \App\Models\PaymentSetting::first()->wallet;
@endphp
<style type="text/css">
.dev {
  max-width: 64rem;
  margin: 0 auto;
}

%whatsapp {
  --wab-background: hsla(129, 62%, 44%,1);
  --wab-background-focus: hsla(129, 62%, 50%,1);
  --wab-color: white;
}

.wab-icon {
  
  position: fixed;
  bottom: 1rem;
  left: 1rem;
  z-index: 9999;
  
  &__image {
    width: 50px;
    height: 50px;
  }
}

.wab-button {
  
  @extend %whatsapp; 
  
  background-color: var(--wab-background);
  color: var(--wab-color);
  border: none;
  padding: 0.5rem 1rem;
  font-size: 1rem;
  border-radius: 0.25em;
  cursor: pointer;
  
  &:hover,
  &:focus-within {
    background-color: var(--wab-background-focus);
  }
}


  </style>
<!-- component -->
<input type="hidden" id="lang" value="{{ session('locale') == null ? 'English' : session('locale') }}">
<div class="font-sans w-full m-0 ">
    <div class="bg-primary shadow xxsm:max-md:hidden header">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-6">
                <div class="d-f">
                    {{-- App Logo --}}
                    <a href="{{ url('/') }}" class="object-cover ">
                        <!-- <img src="{{ $logo ? url('images/upload/' . $logo) : asset('/images/logo.png') }}"
                        class="object-scale-down h-10 " alt="Logo"> -->
                    <img src="https://ticketby.co/frontend/images/icon-white.png"
                        class="object-scale-down h-12 ipad-size-logo" alt="Logo">
                    </a>
                    <!-- @if (session('direction') == 'rtl')
                    <div class="mr-20">
                        <form action="{{ url('user/search_event') }}" method="post">
                            @csrf
                            <div class="relative">
                                
                                <button style="margin-top: 10px; position: absolute; left: 12px;" class="search-btn-rtl">  <img src="{{ asset('images/search.svg') }}" class="w-5 h-5" alt=""></button>
                                <input type="search" id="default-search" name="search"
                                    class="input-header-rtl block p-2 pl-10 text-white bg-white border border-gray-light text-left font-poppins font-normal
                            text-base leading-6 rounded-md mx-1 focus:outline-none xxsm:w-full sm:w-48 lg:w-50" style="width:10rem;"
                                    placeholder="{{ __('Search..') }}" required>
                            </div>
                        </form>
                    </div>
                    @endif -->
                </div>
               
                <div class="sm:flex sm:items-center mr-50 rtl-mr-0">
                    <ul class="navbar-nav flex space-x-4 xxsm:max-xl:hidden">
                        <li class="xxsm:max-lg:hidden nav-item {{ $activePage == 'home' ? 'active' : '' }} mr-5px rtl-ml-10" >
                            <a href="{{ url('/') }}"
                                class="nav-link md:px-1 capitalize font-poppins font-normal text-base rtl-lg-text-big leading-6 text-white">{{ __('Home') }}</a>
                        </li>
                        <li class="xxsm:max-lg:hidden nav-item {{ Request::is('all-events') ? 'active' : '' }} mr-5px">
                            <a href="{{ url('/all-events') }}"
                                class="nav-link md:px-1 capitalize font-poppins font-normal text-base rtl-lg-text-big leading-6 text-white">{{ __('Events') }}</a>
                        </li>
                        <li class="xxsm:max-lg:hidden nav-item {{ Request::is('all-category') ? 'active' : '' }} mr-5px">
                            <div class="relative inline-block text-left">
                                <a href="#"
                                    class="nav-link md:px-1 capitalize font-poppins font-normal text-base rtl-lg-text-big  leading-6 text-white flex focus:outline-none categories"
                                    id="">{{ __('Categories') }} 
                                    <i class="fa-solid fa-chevron-down w-3 h-3 mt-1.5 ml-2 category-arrow-image mt-255  rtl-mr-t-10" ></i>
                                    
                                    <!-- <img src="{{ asset('images/dropdown.png') }}" alt=""
                                        class=" ml-1 h-2 w-3 mt-2"> -->
                                </a>
                                <div
                                    class="categoriesClass hidden rigin-top-left absolute left-0 w-44 rounded-md shadow-2xl z-30">
                                    <div class="rounded-md bg-white shadow-lg p-3">
                                        <div class="">
                                            @php
                                                if (!isset($catactive)) {
                                                    $catactive = null;
                                                }
                                            @endphp
                                            @if (isset($category))
                                                @foreach ($category as $item)
                                                    <div class="flex items-left justify-left">
                                                        <a href="{{ url('/events-category/' . $item->id . '/' . $item->name) }}"
                                                            class="flex items-left text-base rtl-lg-text-big text-black font-poppins font-normal leading-5 {{ $catactive == $item->name ? ' text-primary capitalize p-2 bg-primary-light' : ' ' }}  capitalize p-2 hover:bg-primary-light rounded-md w-full">  @if (session('direction') == 'rtl')
                                 {{ $item->ar_name }}
                                 @else
                                 {{ $item->name }}
                                 @endif</a>
                                                    </div>
                                                @endforeach
                                            @endif
                                            <div class="flex items-left justify-left">
                                                <a href="{{ url('/all-category') }}"
                                                    class="flex items-left text-base rtl-lg-text-big  text-black font-poppins font-normal leading-5 {{ $catactive == 'all' ? 'text-primary capitalize p-2 bg-primary-light' : ' ' }} capitalize p-2 hover:bg-primary-light rounded-md w-full">{{ __('All categories') }}
                                                    <i class="fa-solid fa-chevron-right w-3 h-3 mt-1.5 ml-2 arrow-image mt-255  rtl-mr-t-10" ></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <!-- <li class="xxsm:max-lg:hidden nav-item {{ $activePage == 'blog' ? 'active' : '' }} mr-5px">
                            <a href="{{ url('/all-blogs') }}"
                                class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Blog') }}</a>
                        </li> -->
                        <li class="xxsm:max-lg:hidden nav-item {{ $activePage == 'contact' ? 'active' : '' }} ">
                            <a href="{{ url('/contact') }}"
                                class="nav-link md:px-1 capitalize font-poppins font-normal text-base rtl-lg-text-big leading-6 text-white absolute">{{ __('Contact Us') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="sm:flex sm:gap-3 sm:items-center">
                @if (session('direction') == 'ltr')
                    <!-- <div>
                        <form action="{{ url('user/search_event') }}" method="post">
                            @csrf
                            <div class="relative">
                                
                                <button style="margin-top: 10px; position: absolute; left: 12px;" class="search-btn-rtl">  <img src="{{ asset('images/search.svg') }}" class="w-5 h-5" alt=""></button>
                                <input type="search" id="default-search" name="search"
                                    class="input-header-rtl block p-2 pl-10 text-white bg-white border border-gray-light text-left font-poppins font-normal
                            text-base leading-6 rounded-md mx-1 focus:outline-none xxsm:w-full sm:w-48 lg:w-50" style="width:10rem;"
                                    placeholder="{{ __('Search..') }}" required>
                            </div>
                        </form>
                    </div> -->
                    <div class="sm:px-4 xmd:px-0 flex">
            
                    @endif
                    <div class="relative inline-block text-left">
                @if (Session::has('local'))
                    {{ Session::get('local') }}
                @endif
                @php
                    $language = \App\Models\Language::where('status', 1)->where('direction',"!=",session('direction'))->get();
                @endphp
                <!-- <a type="button" href="javascript:void(0);"
                    class="px-3 py-2 text-white bg-primary text-center font-poppins font-normal text-base leading-6 rounded-md flex languageTop">
                    {{ __('Language') }}<img src="{{ asset('images/downwhite.png') }}"
                        class="w-3 h-2 mx-2 mt-2 language" alt="">
                        
                </a> -->
                <div class="language-parent font-size-responsive rtl-lg-text-big" style="color:white">
                
                @foreach ($language as $key => $item)
                <div class="language-option">
                <a type="button" href="{{ url('change-language/' . $item->name) }}" ><i class="fa fa-globe rtl-ml-5 rtl-mt-4"  aria-hidden="true" style="  margin-right: 6px; margin-top: 2px;  " ></i> @if($item->name == "English") En @else Ar @endif </a>
                </div>
                @if($key < count($language) - 1 )
                <span style="padding-right:5px;padding-left:5px;"> / </span>
                @endif
                @endforeach
                </div>
                <!-- <div
                    class="languageClassTop hidden rigin-top-left absolute left-0 w-44 rounded-md shadow-2xl z-10 " style="top:50px;">
                    <div class="rounded-md bg-white shadow-lg p-3">
                    @foreach ($language as $item)
                            <div class="">
                                <div class="p-2 flex items-left justify-left">
                                    <a type="button" href="{{ url('change-language/' . $item->name) }}"
                                        class="hover:text-primary capitalize p-2 hover:bg-primary-light text-black w-full text-center font-poppins font-normal text-base leading-6 rounded-md flex language">
                                        <img src="{{ asset('images/upload/' . $item->image) }}"
                                            class="w-5 h-5 mx-2 language" alt="">c</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div> -->
            </div>
                    @if (Auth::guard('appuser')->check())
                        <div class="flex justify-center relative dropdownScreenButton cursor-pointer ml-20 " >
                            <div class="pt-3 mr-5 rtl-ml-10">
                                <p class="font-poppins font-medium text-sm leading-5 text-white font-size-responsive rtl-lg-text-big">
                                    {{ $user->name ?? ' ' }}</p>
                            </div>
                            <div class="">
                                <img src="{{ asset('images/upload/' . $user->image) }}"
                                    class="w-10 h-10 bg-cover object-contain border border-gray-light rounded-full"
                                    alt="">
                            </div>
                            <div class="ml-3 pt-1 dropdown relative flex">
                                <div class="relative inline-block text-left">
                                    <div
                                        class="dropdownMenuClass hidden rigin-top-right absolute right-0 rtl-left-side w-56 rounded-md shadow-2xl z-30 mt-10">
                                        <div class="rounded-md bg-white shadow-xs">
                                            <div class="py-1">
                                                <div
                                                    class="overflow-y-auto py-4 px-3 bg-gray-50 rounded pt-10 border-b border-gray-light pb-5">
                                                    <ul class="space-y-8 ">
                                                        <li>
                                                            <a href="{{ url('/my-tickets') }}"
                                                                class="flex items-center font-normal font-poppins leading-6 text-black text-base capitalize font-size-responsive">{{ __('My Tickets') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/user/profile') }}"
                                                                class="flex items-center font-normal font-poppins leading-6 text-black text-base capitalize font-size-responsive ">{{ __('Profile') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ url('/change-password') }}"
                                                                class="flex items-center font-normal font-poppins leading-6 text-black text-base capitalize font-size-responsive">{{ __('Change Password') }}
                                                            </a>
                                                        </li>
                                                        @if ($wallet == 1)
                                                            <li>
                                                                <a href="{{ route('myWallet') }}"
                                                                    class="flex items-center font-normal font-poppins leading-6 text-black text-base capitalize font-size-responsive">{{ __('My Wallet') }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                                <div class="px-3 py-5">
                                                    <button type="button"
                                                        class="flex items-center  font-poppins font-medium leading-4 text-danger capitalize">
                                                        <i class="fa-solid fa-right-from-bracket mr-2"></i><span
                                                            id="check"
                                                            class="flex-1  whitespace-nowrap">{{ __('Logout') }}</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="sm:px-4 flex xxsm:flex-wrap justify-end mt-2 sm:mt-0 ">
                            <a type="button" href="{{ url('user/login') }}"
                                class="px-5 py-2 text-white bg-primary text-center font-poppins font-normal text-base leading-6 rounded-md sign-in-button-css">{{ __('Sign In') }}</a>
                        </div>
                    @endif
                    <div class="cursor-pointer ml-3 xl:hidden">
                        <button id="nav-toggle"
                            class="btn text-white bg-white text-left font-poppins font-normal nav-toggle font-size-responsive">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="hidden nav-content" id="nav-content">
                <ul class="list-reset lg:flex gap-5 justify-end flex-1 items-center text-end pb-5">
                    <li class="mt-2 nav-item {{ $activePage == 'home' ? 'active' : '' }} ">
                        <a href="{{ url('/') }}"
                            class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white font-size-responsive">{{ __('Home') }} </a>
                    </li>
                    <li class="mt-2 nav-item {{ Request::is('all-events') ? 'active' : '' }}">
                        <a href="{{ url('/all-events') }}"
                            class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white font-size-responsive">{{ __('Events') }}</a>
                    </li>
                    <li class="mt-2 nav-item {{ Request::is('all-category') ? 'active' : '' }} ">
                        <div class="relative inline-block text-left">
                            <a type="button" href="#"
                                class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white flex focus:outline-none categories font-size-responsive">{{ __('Categories') }} 
                                <i class="fa-solid fa-chevron-down w-3 h-3 mt-1.5 ml-2 category-arrow-image mt-255  rtl-mr-t-10"></i>
                            </a>
                        </div>
                        <div id="cattab"
                            class="categoriesClass hidden rigin-top-right absolute right-18 w-44 rounded-md shadow-2xl z-30">
                            <div class="rounded-md bg-white shadow-lg p-3">
                                <div class="">
                                    @php
                                        if (!isset($catactive)) {
                                            $catactive = null;
                                        }
                                    @endphp
                                    @if (isset($category))
                                        @foreach ($category as $item)
                                            <div class="flex items-left justify-left">
                                                <a href="{{ url('/events-category/' . $item->id . '/' . $item->name) }}"
                                                    class=" font-size-responsive flex items-left text-base text-black text-white font-poppins font-normal leading-5 {{ $catactive == $item->name ? ' text-primary capitalize p-2 bg-primary-light' : ' ' }}  capitalize p-2 hover:bg-primary-light rounded-md w-full">  @if (session('direction') == 'rtl')
                                 {{ $item->ar_name }}
                                 @else
                                 {{ $item->name }}
                                 @endif</a>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="flex items-left justify-left">
                                        <a href="{{ url('/all-category') }}"
                                            class=" font-size-responsive flex items-left text-base font-poppins font-normal leading-5 {{ $catactive == 'all' ? 'text-primary capitalize p-2 bg-primary-light' : ' ' }} capitalize p-2 hover:bg-primary-light rounded-md w-full">{{ __('All categories') }}
                                            <i class="fa-solid fa-chevron-right w-3 h-3 mt-1.5 ml-2 arrow-image mt-255  rtl-mr-t-10" ></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <!-- <li class="mt-2 nav-item {{ $activePage == 'blog' ? 'active' : '' }}">
                        <a href="{{ url('/all-blogs') }}"
                            class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Blog') }}</a>
                    </li> -->
                    <li class="mt-2 nav-item {{ $activePage == 'contact' ? 'active' : '' }}">
                        <a href="{{ url('/contact') }}"
                            class=" font-size-responsive nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Contact Us') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
{{-- Mobile View --}}
<nav class="navbar rounded m-0 bg-white z-30 shadow-md relative md:hidden bg-primary">
    <div
        class="flex xxsm:space-y-2 xmd:space-y-0 md:space-y-2 msm:flex-col md:flex-col xmd:flex-col xxsm:flex-col lg:flex-wrap xmd:flex-wrap justify-between
     3xl:mx-52 2xl:mx-28 1xl:mx-28 xl:mx-10 xlg:mx-10 lg:mx-35 xxmd:mx-24 xmd:mx-32 sm:mx-20 msm:mx-16 xsm:mx-5 xxsm:mx-5 py-3 pt-4 z-30 xl2:flex-row">
     @if (session('direction') == 'rtl')
     <div class="flex items-center lg:items-left w-auto " >
     <div class="w-full  rtl-right-text-sm   xxsm:max-lg:block hidden ">
                <button class="btn text-white bg-primary text-left font-poppins font-normal nav-toggle">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
            </div>
            <ul
                class=" navbar-nav flex lg:flex-row xmd:flex-row md:flex-row md:text-xs md:space-x-3 sm:flex-row msm:flex-col xsm:flex-col xxsm:flex-col msm:space-x-2 sm:space-x-2 lg:space-x-10 temp:max-temp2:space-x-5 md:mt-0">
                {{-- App Logo --}}
                <a href="{{ url('/') }}" class="object-cover ">
                    <!-- <img src="{{ $logo ? url('images/upload/' . $logo) : asset('/images/logo.png') }}"
                        class="object-scale-down h-10 " alt="Logo"> -->
                    <img src="https://ticketby.co/frontend/images/icon-white.png"
                        class="object-scale-down h-10 " alt="Logo">
                </a>
            </ul>
            
        </div>
        <div class="hidden nav-content" id="nav-content">
            <ul class="list-reset lg:flex justify-end flex-1 items-center  rtl-right-text-sm">
                <li class="mt-2 nav-item {{ $activePage == 'home' ? 'active' : '' }} ">
                    <a href="{{ url('/') }}"
                        class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Home') }}</a>
                </li>
                <li class="mt-2 nav-item {{ Request::is('all-events') ? 'active' : '' }}">
                    <a href="{{ url('/all-events') }}"
                        class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Events') }}</a>
                </li>
                <li class="mt-2 nav-item {{ Request::is('all-category') ? 'active' : '' }} ">
                    <div class="relative inline-block text-left">
                        <a type="button" href="#" id="showcattab"
                            class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white flex focus:outline-none categories"
                            id="categories">{{ __('Categories') }}
                            <i class="fa-solid fa-chevron-down w-3 h-3 mt-1.5 ml-2 category-arrow-image mt-255  rtl-mr-t-10" ></i>
                        </a>
                    </div>
                    <div id="cattab"
                        class="categoriesClass hidden rigin-top-left left-0 rounded-md shadow-2xl z-30 mr-10 w-full">
                        <div class="rounded-md bg-white shadow-lg p-3">
                            <div class="">
                                @php
                                    if (!isset($catactive)) {
                                        $catactive = null;
                                    }
                                @endphp
                                @if (isset($category))
                                    @foreach ($category as $item)
                                        <div class="flex items-left justify-left">
                                            <a href="{{ url('/events-category/' . $item->id . '/' . $item->name) }}"
                                                class="flex items-left text-base font-poppins font-normal leading-5 {{ $catactive == $item->name ? ' text-primary capitalize p-2 bg-primary-light' : ' ' }}  capitalize p-2 hover:bg-primary-light rounded-md w-full">{{ $item->name }}</a>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="flex items-left justify-left">
                                    <a href="{{ url('/all-category') }}"
                                        class="flex items-left text-base font-poppins font-normal leading-5 {{ $catactive == 'all' ? 'text-primary capitalize p-2 bg-primary-light' : ' ' }} capitalize p-2 hover:bg-primary-light rounded-md w-full">{{ __('All categories') }}
                                        <img src="{{ asset('images/right-dropdown.png') }}" alt=""
                                            class=" ml-2 h-2 w-2 mt-2 arrow-image ">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- <li class="mt-2 nav-item {{ $activePage == 'blog' ? 'active' : '' }}">
                    <a href="{{ url('/all-blogs') }}"
                        class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Blog') }}</a>
                </li> -->
                <li class="mt-2 nav-item {{ $activePage == 'contact' ? 'active' : '' }}">
                    <a href="{{ url('/contact') }}"
                        class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Contact Us') }}</a>
                </li>
                <li class=" mt-2 nav-item rtl-right-text-sm {{ $activePage == 'contact' ? 'active' : '' }}">
                    <div class="language-parent " style="color:white">
                    <i class=" fa fa-globe rtl-ml-5 rtl-mt-4"  aria-hidden="true" style="  margin-right: 6px; margin-top: 2px;  " ></i>
                    @foreach ($language as $key => $item)
                    <div class="language-option ">
                    <a class="" type="button" href="{{ url('change-language/' . $item->name) }}" >@if($item->name == "English") En @else Ar @endif </a>
                    </div>
                    @if($key < count($language) - 1 )
                    <span style="padding-right:5px;padding-left:5px;"> / </span>
                    @endif
                    @endforeach
                    </div>
                </li>
            </ul>
        </div>
    @else  
    <div class="flex items-center lg:items-left w-auto">
            <div class="w-full text-left xxsm:max-lg:block hidden">
                <button class="btn text-white bg-primary text-left font-poppins font-normal nav-toggle">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
            </div>
            <ul
                class="navbar-nav flex lg:flex-row xmd:flex-row md:flex-row md:text-xs md:space-x-3 sm:flex-row msm:flex-col xsm:flex-col xxsm:flex-col msm:space-x-2 sm:space-x-2 lg:space-x-10 temp:max-temp2:space-x-5 md:mt-0">
                {{-- App Logo --}}
                <a href="{{ url('/') }}" class="object-cover ">
                    <!-- <img src="{{ $logo ? url('images/upload/' . $logo) : asset('/images/logo.png') }}"
                        class="object-scale-down h-10 " alt="Logo"> -->
                    <img src="https://ticketby.co/frontend/images/icon-white.png"
                        class="object-scale-down h-10 " alt="Logo">
                </a>
            </ul>
            
        </div>
        <div class="hidden nav-content" id="nav-content">
            <ul class="list-reset lg:flex flex-1 items-center text-left">
                <li class="mt-2 nav-item {{ $activePage == 'home' ? 'active' : '' }} ">
                    <a href="{{ url('/') }}"
                        class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Home') }}</a>
                </li>
                <li class="mt-2 nav-item {{ Request::is('all-events') ? 'active' : '' }}">
                    <a href="{{ url('/all-events') }}"
                        class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Events') }}</a>
                </li>
                <li class="mt-2 nav-item {{ Request::is('all-category') ? 'active' : '' }} ">
                    <div class="relative inline-block text-left">
                        <a type="button" href="#" id="showcattab"
                            class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white flex focus:outline-none categories"
                            id="categories">{{ __('Categories') }}
                            <i class="fa-solid fa-chevron-down w-3 h-3 mt-1.5 ml-2 category-arrow-image mt-255  rtl-mr-t-10" ></i>
                        </a>
                    </div>
                    <div id="cattab"
                        class="categoriesClass hidden rigin-top-left left-0 rounded-md shadow-2xl z-30 mr-10 w-full">
                        <div class="rounded-md bg-white shadow-lg p-3">
                            <div class="">
                                @php
                                    if (!isset($catactive)) {
                                        $catactive = null;
                                    }
                                @endphp
                                @if (isset($category))
                                    @foreach ($category as $item)
                                        <div class="flex items-left justify-left">
                                            <a href="{{ url('/events-category/' . $item->id . '/' . $item->name) }}"
                                                class="flex items-left text-base font-poppins font-normal leading-5 {{ $catactive == $item->name ? ' text-primary capitalize p-2 bg-primary-light' : ' ' }}  capitalize p-2 hover:bg-primary-light rounded-md w-full">{{ $item->name }}</a>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="flex items-left justify-left">
                                    <a href="{{ url('/all-category') }}"
                                        class="flex items-left text-base font-poppins font-normal leading-5 {{ $catactive == 'all' ? 'text-primary capitalize p-2 bg-primary-light' : ' ' }} capitalize p-2 hover:bg-primary-light rounded-md w-full">{{ __('All categories') }}
                                        <img src="{{ asset('images/right-dropdown.png') }}" alt=""
                                            class=" ml-2 h-2 w-2 mt-2 arrow-image ">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- <li class="mt-2 nav-item {{ $activePage == 'blog' ? 'active' : '' }}">
                    <a href="{{ url('/all-blogs') }}"
                        class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Blog') }}</a>
                </li> -->
                <li class="mt-2 nav-item {{ $activePage == 'contact' ? 'active' : '' }}">
                    <a href="{{ url('/contact') }}"
                        class="nav-link md:px-1 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Contact Us') }}</a>
                </li>
                <li class=" mt-2 nav-item rtl-float-left {{ $activePage == 'contact' ? 'active' : '' }}">
                    <div class="language-parent " style="color:white">
                    <i class=" fa fa-globe rtl-ml-5 rtl-mt-4"  aria-hidden="true" style="  margin-right: 6px; margin-top: 2px;  " ></i>
                    @foreach ($language as $key => $item)
                    <div class="language-option ">
                    <a class="" type="button" href="{{ url('change-language/' . $item->name) }}" >@if($item->name == "English") En @else Ar @endif </a>
                    </div>
                    @if($key < count($language) - 1 )
                    <span style="padding-right:5px;padding-left:5px;"> / </span>
                    @endif
                    @endforeach
                    </div>
                </li>
            </ul>
        </div>  
    @endif    
        {{-- Search Button and Login button --}}
        <div class="">
            <div class="flex justify-between sm:space-x-6 xxsm:flex-col sm:flex-row">
                <div>
                    <!-- <form action="{{ url('user/search_event') }}" method="post">
                        @csrf
                        <div class="relative">
                            <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none sm-right-15px">
                                <img src="{{ asset('images/search.svg') }}" class="w-5 h-5" alt="">
                            </div>
                            <input type="search" id="default-search" name="search"
                                class="block p-2 pl-10  bg-white border border-gray-light text-left font-poppins font-normal
                                text-base leading-6 rounded-md mx-1 focus:outline-none xxsm:w-full sm:w-48 lg:w-72"
                                placeholder="{{ __('Search..') }}" required>
                        </div>
                    </form> -->
                </div>
                @if (Auth::guard('appuser')->check())
                    <div class="flex justify-end mt-2  sm-header-user">
                        
                        <div class="ml-3 pt-1 dropdown relative flex ">
                            <div class="relative inline-block text-left  rtl-ml-wi">
                                <div
                                    class="dropdownMenuClass hidden rigin-top-right absolute right-0 w-56 rounded-md shadow-2xl z-30 mt-10 sm-right-minus rtl-sm-right-minus">
                                    <div class="rounded-md bg-white shadow-xs">
                                        <div class="py-1">
                                            <div
                                                class="overflow-y-auto py-4 px-3 bg-gray-50 rounded pt-10 border-b border-gray-light pb-5">
                                                <ul class="space-y-8 ">
                                                    <li>
                                                        <a href="{{ url('/my-tickets') }}"
                                                            class="flex items-center font-normal font-poppins leading-6 text-black text-base capitalize">{{ __('My tickets') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('/user/profile') }}"
                                                            class="flex items-center font-normal font-poppins leading-6 text-black text-base capitalize">{{ __('Profile') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ url('/change-password') }}"
                                                            class="flex items-center font-normal font-poppins leading-6 text-black text-base capitalize">{{ __('Change password') }}
                                                        </a>
                                                    </li>
                                                    @if ($wallet == 1)
                                                        <li>
                                                            <a href="{{ route('myWallet') }}"
                                                                class="flex items-center font-normal font-poppins leading-6 text-black text-base capitalize">{{ __('My Wallet') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="px-3 py-5">
                                                <button type="button"
                                                    class="flex items-center  font-poppins font-medium leading-4 text-danger capitalize">
                                                    <i class="fa-solid fa-right-from-bracket mr-3"></i><span id="check"
                                                        class="flex-1  whitespace-nowrap">{{ __('Logout') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-3 mr-5 sm-mr-0 rtl-sm-mt-25">
                            <p class="font-poppins font-medium text-sm leading-5 text-white rtl-ml-10">
                                {{ $user->name ?? ' ' }}</p>
                        </div>
                        <div class="dropdownScreenButton cursor-pointer z-index-high rtl-sm-mt-25" >
                            <img src="{{ asset('images/upload/' . $user->image) }}"
                                class="w-10 h-10 bg-cover object-contain border border-gray-light rounded-full"
                                alt="">
                        </div>
                        
                    </div>
                @else
                    <div class="sm:px-4 flex xxsm:flex-wrap justify-end mt-2 sm:mt-0 sign-in-button">
                        <a type="button" href="{{ url('user/login') }}"
                            class="px-5 py-2 text-white bg-primary text-center font-poppins font-normal text-base leading-6 rounded-md sign-in-button-css">{{ __('Sign In') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>
<div class="dev ">
  <a href="#" class="wab-icon js-whatsapp" data-name="John Doe" aria-label="Chat with us on WhatsApp">
    <img class="wab-icon__image rtl-transform-270" src="https://assets.codepen.io/82811/whatsapp-logo.svg" alt="" height="70px" width="70px" /> 
  </a>
</div>
<script>
    var check = document.getElementById("check");
    if(check) {
        check.addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure to logout!!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location = "/user/logoutuser";
                }
            })
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
  var whatsappButtons = document.getElementsByClassName("js-whatsapp");
  //var phoneNumber = "+917405072262"; // Your WhatsApp Business phone number with country code
  var phoneNumber = "+966556046094";
  var message = "Hello, I would like to know more about your services."; // Initial message sent to user

  Array.prototype.forEach.call(whatsappButtons, function (button) {
    button.addEventListener("click", function (e) {
      e.preventDefault;
      var whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(
        message
      )}`;
      window.open(whatsappUrl, "_blank");
    });
  });
});
</script>
