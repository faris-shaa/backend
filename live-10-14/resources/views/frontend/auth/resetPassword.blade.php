<!doctype HTML>
<html>

<head>
    <title>{{ __('Forget password Page') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @php
        $favicon = \App\Models\Setting::find(1)->favicon;
    @endphp
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
    </style>
</head>
@php
    $setting = \App\Models\Setting::find(1);
@endphp

<body>
    @php
        $setting = \App\Models\Setting::find(1);
    @endphp
    <div class="flex justify-center mt-32">
        <div
            class="bg-white shadow-2xl rounded-md p-5 mt-10 1xl:w-[28%] xl:w-[35%] lg:w-[40%] xmd:w-[50%] md:w-[60%] sm:w-[70%] xxsm:w-full">
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
                {{ __('Forgot Password') }}
            </p>
            <form action="{{ url('user/resetPassword') }}" method="post" data-qa="form-login" name="login">
                @csrf

                <div class="pt-12">
                    <div
                        class="flex sm:space-x-7 justify-center  xxsm:space-y-5 msm:space-y-0 msm:space-x-5 xsm:space-x-0 xxsm:space-x-0 xxsm:mx-10.0 xxsm:flex-wrap xsm:flex-wrap msm:flex-nowrap">
                        <div
                            class="border border-gray-light py-3.5 px-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex">
                            <input id="default-radio-1" type="radio" value="user" checked name="type"
                                class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none">
                            <label for="">{{ __('User') }}</label>
                        </div>
                        <div
                            class="border border-gray-light py-3.5 px-5 rounded-lg text-gray-100  w-full font-normal font-poppins text-base leading-6 flex">
                            <input id="default-radio-1" type="radio" value="org" name="type"
                                class="w-5 h-5 mr-2 border border-gray-light hover:border-gray-light focus:outline-none">
                            <label for="" class="">{{ __('Organizer') }}</label>
                        </div>
                    </div>
                </div>

                <div class="pt-5">
                    <label for="email"
                        class="font-poppins font-medium text-base leading-6 text-black">{{ __('Email') }}</label>
                    <input type="email" name="email" id=""
                        class="w-full text-sm font-poppins font-normal text-black block p-3 z-20 rounded-lg border border-gray-light focus:outline-none"
                        placeholder="john@gmail.com">
                    @error('email')
                        <div class="text-danger font-medium">{{ $message }}</div>
                    @enderror
                </div>

                <div class="pt-7">
                    <button
                        class="font-poppins text-white bg-primary leading-4 w-full text-sm font-medium py-4 rounded-lg focus:outline-none">{{ __('Reset Password') }}</button>

                </div>
            </form>
            <div class="pt-6 flex justify-center">
                <a href="{{ url('user/login') }}"
                    class="font-poppins text-primary text-medium text-base">{{ __('Back to login') }}</a>
            </div>
        </div>
    </div>
</body>
<script src="https://unpkg.com/flowbite@1.5.5/dist/flowbite.js"></script>
</html>
