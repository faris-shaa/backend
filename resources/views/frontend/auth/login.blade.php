<!doctype HTML>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('Login') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <link href="{{ asset('admin/css/dropdown-admin.css') }}" rel="stylesheet">
    <?php $primary_color = \App\Models\Setting::find(1)->primary_color; ?>
    @php
        $favicon = \App\Models\Setting::find(1)->favicon;
    @endphp
    @if (session('direction') == 'rtl')
        <link rel="stylesheet" href="{{ url('frontend/css/rtl.css') }}">
    @endif
    <meta charset="utf-8">
    <link href="{{ $favicon ? url('images/upload/' . $favicon) : asset('/images/logo.png') }}" rel="icon"
        type="image/png">
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
    </style>
</head>

<body>
    @php
        $setting = \App\Models\Setting::find(1);
    @endphp
    <div class="flex justify-center mt-24">
        <div
            class="bg-white shadow-2xl rounded-md p-5 mt-10 1xl:w-[28%] xl:w-[35%] lg:w-[40%] xmd:w-[50%] md:w-[60%] sm:w-[70%] xxsm:w-full">
            @if (Session::has('success'))
                <div id="alert-3"
                    class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                    role="alert">
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div class="ms-3 text-sm font-medium">
                        {{ Session::get('success') }}
                    </div>
                    <button type="button"
                        class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"
                        data-dismiss-target="#alert-3" aria-label="Close">
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
                    alt="" class="object-cover w-auto h-20">
            </div>
            <p class="pt-6 text-3xl font-bold leading-9 text-center text-black font-poppins">
                {{ __('Sign in to your account') }}</p>
            <form action="{{ url('user/login') }}" method="post" data-qa="form-login" name="login">
                @csrf
                <input type="hidden" value="{{ url()->previous() }}" name="url">

                <div class="pt-12">
                    <div
                        class=" flex sm:space-x-7 justify-center xxsm:space-y-5 msm:space-y-0 msm:space-x-5 xsm:space-x-0 xxsm:space-x-0 xxsm:mx-10.0 xxsm:flex-wrap xsm:flex-wrap msm:flex-nowrap">
                        <label for="default-radio-1" class="w-full">
                            <div
                                class=" font-bold-big border border-gray-light py-3.5 px-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex items-center">
                                <input id="default-radio-1" type="radio" value="user" checked name="type"
                                    class="w-5 h-5 mr-2 border border-gray-light hover:border-gray-light focus:outline-none sm-ml-10 rt-ml-10">
                                <span>{{ __('User') }}</span>
                            </div>
                        </label>
                        <label for="default-radio-2" class="w-full" >
                            <div
                                class=" font-bold-big rtl-mr-25 sm-rtl-mr-0 border border-gray-light py-3.5 px-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex items-center">
                                <input id="default-radio-2" type="radio" value="org" name="type"
                                    class="w-5 h-5 mr-2 border select border-gray-light hover:border-gray-light focus:outline-none sm-ml-10 rt-ml-10">
                                <span>{{ __('Organizer') }}</span>
                            </div>
                        </label>
                    </div>

                </div>

                <div class="pt-5">
                    @if(request()->has('checkout'))
                        <input type="hidden" name="checkout" value="{{ request('checkout') }}">
                    @endif
                    <label for="email"
                        class=" font-bold-big text-base font-medium leading-6 text-black font-poppins">{{ __('Email') }}</label>
                    <input type="email" name="email" id=""
                        class="font-bold-big z-20 block w-full p-3 text-sm font-normal text-black border rounded-lg font-poppins border-gray-light focus:outline-none"
                        placeholder="{{ __('Your Email') }}">
                    @error('email')
                        <div class="_2OcwfRx4" data-qa="email-status-message">{{ $message }}</div>
                    @enderror
                    @if (Session::has('error_msg'))
                        <div class="mt-1 _2OcwfRx4 text-danger" data-qa="email-status-message">
                            <strong>{{ Session::get('error_msg') }}</strong>
                        </div>
                    @endif
                </div>
                <div class="pt-5 ">
                    <label for="password"
                        class=" font-bold-big text-base font-medium leading-6 text-black font-poppins">{{ __('Password') }}</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class=" font-bold-big z-30 block w-full p-3 text-sm font-normal text-black border rounded-lg focus:outline-none font-poppins border-gray-light"
                            placeholder="{{ __('Password') }}">
                        <span class="absolute right-2.5 bottom-2.5 text-xl font-poppins font-medium text-gray px-2"><i
                                class="fa-regular fa-eye text-primary login-password-eye" id="togglePassword"></i></span>
                        @error('password')
                            <div class="_2OcwfRx4 font-bold-big" data-qa="email-status-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-between pt-4">
                    <div class="flex">
                        <input id="default-radio-1" type="checkbox" value="true" name="remember" class="mr-2 ">
                        <label for=""
                            class=" font-bold-big font-poppins font-medium text-xs leading-5 text-black pt-0.5 rtl-mr-10">{{ __('Remember me') }}</label>
                    </div>
                    <div>
                        <a href="{{ url('/user/resetPassword') }}"
                            class="font-bold-big text-xs font-medium leading-5 font-poppins text-primary">{{ __('Forgot your password?') }}</a>
                    </div>
                </div>

                <div class="pt-7">
                    <button
                        class=" font-bold-big w-full py-4 text-sm font-medium leading-4 text-white rounded-lg font-poppins bg-primary focus:outline-none">{{ __('Sign In') }}</button>
                </div>
            </form>
            <div class="flex justify-center pt-6">
                <h1 class="pt-4 text-base font-medium leading-5 text-left font-poppins text-gray">
                    {{ __('Don’t have an account?') }}
                    <a href="{{ url('/user/register') }}"
                        class=" font-bold-big text-base text-primary text-medium">{{ __('Create Account') }}</a>
                </h1>
            </div>
            
            
            <div class="font-bold-big text-base text-center text-primary font-medium mt-3"><a href="{{ url('/admin/login') }}">{{ __('Admin Login') }}</a></div>
            @php
                    $language = \App\Models\Language::where('status', 1)->get();
                @endphp
            <div class="language-parent" style="justify-content:center;">
                <i class="fa fa-globe rtl-ml-5"  aria-hidden="true" style="  margin-right: 6px; margin-top: 2px;  " ></i>
                @foreach ($language as $key => $item)
                <div class="language-option">
                <a type="button" href="{{ url('change-language/' . $item->name) }}" >@if($item->name == "English") En @else Ar @endif </a>
                </div>
                @if($key < count($language) - 1 )
                <span style="padding-right:5px;padding-left:5px;"> / </span>
                @endif
                @endforeach
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
</script>
<script src="https://unpkg.com/flowbite@1.5.5/dist/flowbite.js"></script>
<script src="{{ url('admin/js/dropdown-action-admin.js') }}"></script>

</html>
