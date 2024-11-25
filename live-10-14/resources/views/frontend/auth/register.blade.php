<!doctype HTML>
<html>

<head>
    <title>{{ __('Register Page') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @php
        $favicon = \App\Models\Setting::find(1)->favicon;
    @endphp
    @if (session('direction') == 'rtl')
        <link rel="stylesheet" href="{{ url('frontend/css/rtl.css') }}">
    @endif
    <meta charset="utf-8">
    <link href="{{ $favicon ? url('images/upload/' . $favicon) : asset('/images/logo.png') }}" rel="icon"
        type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />

    <script src="{{ asset('js/custom.js') }}"></script>
    <?php $primary_color = \App\Models\Setting::find(1)->primary_color; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root {
            --primary_color: <?php echo $primary_color; ?>;
            --light_primary_color: <?php echo $primary_color . '1a'; ?>;
            --profile_primary_color: <?php echo $primary_color . '52'; ?>;
            --middle_light_primary_color: <?php echo $primary_color . '85'; ?>;
        }

        .bg-primary {
            --tw-bg-opacity: 1;
            background-color: var(--primary_color);
        }

        .bg-primary-dark {
            --tw-bg-opacity: 1;
            background-color: var(--profile_primary_color);
            /* Use the profile_primary_color variable */
        }

        .navbar-nav>.active>a {
            color: var(--primary_color);
        }

        .text-primary {
            --tw-text-opacity: 1;
            color: var(--primary_color);
        }

        .border-primary {
            --tw-border-opacity: 1;
            border-color: var(--primary_color);
        }

        input[type="radio"]:checked {
            background-color: var(--primary_color) !important;
            color: var(--primary_color) !important;
        }
        .font-bold-big
        {
            font-size: 20px;
            font-weight: 900;
        }
        /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
    </style>
</head>
@php
    $setting = \App\Models\Setting::find(1);
@endphp

<body>
    <div class="flex justify-center mt-16">
        <div
            class="bg-white shadow-2xl rounded-md p-5 mt-10 1xl:w-[40%] xl:w-[45%] lg:w-[45%] xmd:w-[55%] md:w-[65%] sm:w-[75%] xxsm:w-full">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div id="alert-2"
                        class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                        role="alert">
                        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                        </svg>
                        <span class="sr-only">Info</span>
                        <div class="ms-3 text-sm font-medium">
                            {{ $error }}
                        </div>
                        <button type="button"
                            class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
                            data-dismiss-target="#alert-2" aria-label="Close">
                            <span class="sr-only">Close</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            @endif
            @if (Session::has('error'))
                <div id="alert-2"
                    class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div class="ms-3 text-sm font-medium">
                        {{ Session::get('error') }}
                    </div>
                    <button type="button"
                        class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
                        data-dismiss-target="#alert-2" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
            @endif
            <div class="flex justify-center mt-5">
                <img src="{{ $setting->logo ? url('images/upload/' . $setting->logo) : asset('/images/logo.png') }}"
                    alt="" class="h-20 w-auto object-cover">
            </div>
            <p class="font-poppins font-bold text-3xl leading-9 text-black text-center pt-6">
                {{ __('Create an account') }}
            </p>
            <form action="{{ url('user/register') }}" method="post">
                @csrf
                <div class="pt-12">
                    <div
                        class="flex sm:space-x-7 justify-center 1xl:mx-28 xl:mx-20 lg:mx-10 xmd:mx-10 sm:mx-10 msm:mx-10 xxsm:space-y-5 msm:space-y-0 msm:space-x-5 xsm:space-x-0 xxsm:space-x-0 xxsm:mx-10.0 xxsm:flex-wrap xsm:flex-wrap msm:flex-nowrap">
                        <label for="default-radio-1" class="w-full">
                            <div
                                class="font-bold-big border border-gray-light py-3.5 px-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex">
                                <input id="default-radio-1" type="radio" value="user" checked name="user_type"
                                    class="font-bold-big user h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none sm-ml-10 rt-ml-10">
                                <label for="default-radio-1">{{ __('User') }}
                            </div>
                        </label>
                        @if(!isset($request->checkout))
                        <label for="default-radio-2" class="w-full" >
                            <div
                                class="font-bold-big rtl-mr-25 sm-rtl-mr-0 border border-gray-light py-3.5 px-5 rounded-lg text-gray-100  w-full font-normal font-poppins text-base leading-6 flex">
                                <input id="default-radio-2" type="radio" value="organizer" name="user_type"
                                    class="font-bold-big org w-5 h-5 mr-2 border border-gray-light hover:border-gray-light focus:outline-none sm-ml-10 rt-ml-10">
                                {{ __('Organizer') }}
                            </div>
                        </label>
                         @endif
                    </div>
                </div>
                 @if(request()->has('checkout'))
                        <input type="hidden" name="checkout" value="{{ request('checkout') }}">
                    @endif
                     @if(!isset($request->checkout))    
                <div class="grid grid-cols-2 gap-5 sm:grid-cols-2 msm:grid-cols-2 xxsm:grid-cols-1">
                    @else
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-1 msm:grid-cols-1 xxsm:grid-cols-1">
                    @endif

                    <div class="pt-5 userInput">
                        <label for="name"
                            class="font-bold-big font-poppins font-medium text-base leading-6 text-black">{{ __('First Name') }}</label>
                        <input type="text" name="name"
                            id=""class="font-bold-big w-full text-sm font-poppins font-normal text-black block p-3 z-20 rounded-lg border border-gray-light focus:outline-none"
                            placeholder="{{ __('First Name') }}">
                    </div>

                    <div class="pt-5 orginput hidden">
                        <label for="name"
                            class="font-bold-big font-poppins font-medium text-base leading-6 text-black">{{ __('First Name') }}</label>
                        <input type="text" name="first_name"
                            id=""class="font-bold-big w-full text-sm font-poppins font-normal text-black block p-3 z-20 rounded-lg border border-gray-light focus:outline-none"
                            placeholder="{{ __('First Name') }}">
                    </div>
                    @if(!isset($request->checkout))   
                    <div class="pt-5">
                        <label for="last_name"
                            class="font-bold-big font-poppins font-medium text-base leading-6 text-black">{{ __('Last Name') }}</label>
                        <input type="text" name="last_name" id="" required
                            class="font-bold-big w-full text-sm font-poppins font-normal text-black block p-3 z-20 rounded-lg border border-gray-light focus:outline-none"
                            placeholder="{{ __('Last Name') }}">
                    </div>
                        @endif
                         @if(!isset($request->checkout))  
                    <div class="">
                        <label for="number"
                            class="font-bold-big font-poppins font-medium text-base leading-6 text-black">{{ __('Country') }}</label>
                        <div class="flex space-x-3">
                            <div class="w-[100%]">
                                <select id="countries" name="Countrycode"
                                    class="font-bold-big bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" disabled selected>{{ __('Select Country') }}</option>
                                    @foreach ($phone as $item)
                                        <option class=" " value="{{ $item->phonecode }}" @if($item->phonecode == "966") selected @endif>
                                            {{ $item->name . '(+' . $item->phonecode . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                          
                        </div>
                    </div>
                    @else
                    <input type="hidden" name="Countrycode" value="966">
                    @endif

                    <div class="">
                        <label for="number"
                            class="font-bold-big font-poppins font-medium text-base leading-6 text-black">{{ __('Contact Number') }}</label>
                        <div class="flex space-x-3">
                            <div class="w-[100%]">
                                <input type="number" name="phone" id=""
                                    class="font-bold-big w-full text-sm font-poppins font-normal text-black block p-3 z-20 rounded-md border border-gray-light focus:outline-none"
                                    placeholder="{{ __('Number') }}">
                            </div>
                        </div>
                    </div>
                    <div class="orginput hidden">
                        <label for="organization_name"
                            class="font-bold-big font-poppins font-medium text-base leading-6 text-black">{{ __('Organization Name') }}</label>
                        <input type="text" name="organization_name" id=""
                            class="font-bold-big w-full text-sm font-poppins font-normal text-black block p-3 z-20 rounded-lg border border-gray-light focus:outline-none"
                            placeholder="{{ __('Organization Name') }}">
                    </div>
                    <div class=" ">
                        <label for="email"
                            class="font-bold-big font-poppins font-medium text-base leading-6 text-black">{{ __('Email Address') }}</label>
                        <input type="email" name="email" id="" required
                            class="font-bold-big w-full text-sm font-poppins font-normal text-black block p-3 z-20 rounded-lg border border-gray-light focus:outline-none"
                            placeholder="{{ __('Email Address') }}">
                    </div>
                    @if(!isset($request->checkout))
                    <div class=" ">
                        <label for="password"
                            class="font-bold-big font-poppins font-medium text-base leading-6 text-black">{{ __('Password') }}</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                class="font-bold-big w-full focus:outline-none text-sm font-poppins font-normal text-black block p-3 z-30 rounded-lg border border-gray-light"
                                placeholder="{{ __('Password') }}">
                            <span
                                class="absolute right-2.5 bottom-2.5 text-xl font-poppins font-medium text-gray px-2"><i
                                    class="fa-regular fa-eye text-primary register-password-eye" id="togglePassword"></i></span>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="pt-7">
                    <button
                        class="font-bold-big font-poppins text-white bg-primary leading-4 w-full text-sm font-medium py-4 rounded-lg focus:outline-none">{{ __('Create') }}</button>
                </div>
            </form>
            <div class="pt-6 flex justify-center">
                <h1 class="font-bold-big font-poppins font-medium text-base leading-5 pt-4 text-left text-gray">
                    {{ __('Already have an account?') }}
                    <a href="{{ url('/user/login') }}"
                        class="font-bold-big text-primary text-medium text-base">{{ __('Login') }}</a>
                </h1>
            </div>
        </div>

    </div>
</body>
<script>
    window.addEventListener("DOMContentLoaded", function() {
        const togglePassword = document.querySelector("#togglePassword");

        togglePassword.addEventListener("click", function(e) {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            // toggle the eye / eye slash icon
            this.classList.toggle("fa-eye-slash");
        });
    });
    $('.user').on('click', function() {
        $('.orginput').addClass('hidden')
        $('.userInput').removeClass('hidden')
    });
    $('.org').on('click', function() {
        $('.userInput').addClass('hidden')
        $('.orginput').removeClass('hidden')
    });
</script>
<script>
    $('#location').select2();
</script>
<script src="https://unpkg.com/flowbite@1.5.5/dist/flowbite.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</html>
