@php
$lang = session('direction') == 'rtl' ? 'ar' : 'en';
$phone = \App\Models\Country::get();

@endphp


<style type="text/css">
    .error {
        padding: 20px;
        color: #df3e3e;
    }
    #register_popup
    {
        padding: 35px;
    }
     #register-model {
        max-height: 170vh ;
        /* overflow: scroll; */
    }
</style>


<!-- user popup -->
<div id="register_popup" class="hidden pop-modal fixed inset-0 flex items-center justify-center bg-dark bg-opacity-75 p-2 z-50  ">
    <div class="bg-dark_4  rounded-2xl   p-2  md:p-32-32 relative w-w-500">
        <!-- register popup -->
        <div id="register-model" class="relative hidden form-container">
            <div class="mb-6 md:mb-7  ">
                <h2 class="text-h4 md:text-h2 text-primary_color_6 font-medium">{{__( 'Sign up to continue')}}</h2>
                <h4 class="text-gray_9 lg:text-h4 text-h6 mt-1 ">{{__( 'Please enter your details.')}}</h4>
            </div>
            <div class="spinner hidden" id="spinner">
                <div><span></span><span></span><span></span></div>
            </div>
            <div id="responceMessage " class="mb-2 responceMessage hidden text-center">
                <div class="mx-auto flex items-center justify-center bg-light bg-opacity-5 rounded-full w-14 h-14 border border-primary_color_o10_1   mb-5 fa-bounce">
                    <i class="fa-solid  fa-2x text-white "></i>
                </div>
                <h4 class="text-white massage"></h4>
            </div>
            <div id="errorMessages" class=" errorMessages hidden text-center rounded-md mb-1 border border-red bg-red_light p-1 text-h8 ">
                <i class="fa-solid fa-triangle-exclamation text-white"></i>
                <span class="messages"></span>
            </div>
            <form id="registerForm" class=" max-w-lg">
                @csrf
                <input type="hidden" value="user" checked name="user_type">
                <input type="hidden" name="checkout" value="checkout">

                <div class=" mb-2">
                    <label for="" class="text-gray_9 text-h7 md:text-h5 md:mb-3  mb-m6 block">{{__('Full name')}}</label>
                    <input type="text" name="name" required placeholder="{{ __('Full Name') }}"
                        class="text-h6  md:text-h5 w-full focus:border-primary_color_6 outline-0 bg-transparent  border border-gray_s px-3 py-1 h-h-38 md:h-auto md:p-16-16 rounded-lg text-white">
                </div>
                <div class="mb-2 font-12">
                    <label for="" class="text-gray_9 text-h7 md:text-h5 md:mb-3  mb-m6 block">{{__('Mobile number')}}</label>
                    <div class="countriescd flex gap-1 border-gray_s rounded-lg border items-center  pe-2">
                       
                        <input type="number" name="phone" required placeholder=" {{ __('Mobile number') }}"
                            class=" text-h6  md:text-h5 w-full focus:border-primary_color_6 outline-0 bg-transparent  border border-gray_s px-3 py-1 h-h-38 md:h-auto md:p-16-16 rounded-lg text-white">
                    </div>
                </div>
                <div class="mb-2">
                    <label for="" class="text-gray_9 text-h7 md:text-h5 md:mb-3  mb-m6 block">{{__('Email address')}}</label>
                    <input type="email" name="email" required placeholder="{{ __('Email') }}"
                        class="text-h6 md:text-h5  w-full focus:border-primary_color_6 outline-0 bg-transparent  border border-gray_s  px-3 py-1 h-h-38 md:h-auto  md:p-16-16 rounded-lg text-white">
                </div>
                <div class="mb-2 ">
                    <label for="" class="text-gray_9 text-h7 md:text-h5 md:mb-3  mb-m6 block">{{__('password')}}</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" required placeholder="******"
                            class="text-h6 md:text-h5  w-full focus:border-primary_color_6 outline-0 bg-transparent  border border-gray_s  px-3 py-1 h-h-38 md:h-auto md:p-16-16 rounded-lg text-white">
                        <span id="toggle-password" class="toggle-password">
                            <svg style="    float: right;
    margin-top: -35px;
    margin-right: 20px;" width="19" height="14" viewBox="0 0 19 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 7C14 9.21875 12.1875 11 10 11C7.78125 11 6 9.21875 6 7C6 4.8125 7.78125 3 10 3C12.1875 3 14 4.8125 14 7ZM10 4C8.3125 4 7 5.34375 7 7C7 8.65625 8.3125 10 10 10C11.6562 10 13 8.65625 13 7C13 5.34375 11.6562 4 10 4ZM16 2.53125C17.4688 3.875 18.4375 5.5 18.9062 6.625C19 6.875 19 7.15625 18.9062 7.40625C18.4375 8.5 17.4688 10.125 16 11.5C14.5312 12.875 12.5 14 10 14C7.46875 14 5.4375 12.875 3.96875 11.5C2.5 10.125 1.53125 8.5 1.0625 7.40625C0.96875 7.15625 0.96875 6.875 1.0625 6.625C1.53125 5.5 2.5 3.875 3.96875 2.53125C5.4375 1.15625 7.46875 0 10 0C12.5 0 14.5312 1.15625 16 2.53125ZM2 7C2.40625 8 3.3125 9.5 4.65625 10.75C6 12 7.78125 13 10 13C12.1875 13 13.9688 12 15.3125 10.75C16.6562 9.5 17.5625 8 18 7C17.5625 6 16.6562 4.5 15.3125 3.25C13.9688 2 12.1875 1 10 1C7.78125 1 6 2 4.65625 3.25C3.3125 4.5 2.40625 6 2 7Z" fill="#999999" />
                            </svg>
                        </span>
                    </div>
                </div>
                <button id="submitRegisterForm" class="rounded-full bg-primary_color_8 px-p-32 py-1 md:p-12-24 md:w-full mx-auto block md:mt-8 mt-4 text-center text-h6 md:text-h5 font-medium w-full">{{__( 'Sign up')}}
                </button>
                <p class="h7 lg:h5 mt-3 text-gray_9">{{ __('By signing up you agree to our') }} <a href="" class="text-primary_color_6"> {{__('terms')}}</a></p>
            </form>

            <span class=" close-modal absolute    @if($lang == 'ar') left-0 @else right-0 @endif  top-0 cursor-pointer">
                <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 20.5C4.45312 20.5 0 16.0469 0 10.5C0 4.99219 4.45312 0.5 10 0.5C15.5078 0.5 20 4.99219 20 10.5C20 16.0469 15.5078 20.5 10 20.5ZM6.83594 7.33594C6.44531 7.72656 6.44531 8.3125 6.83594 8.66406L8.67188 10.5L6.83594 12.3359C6.44531 12.7266 6.44531 13.3125 6.83594 13.6641C7.1875 14.0547 7.77344 14.0547 8.125 13.6641L9.96094 11.8281L11.7969 13.6641C12.1875 14.0547 12.7734 14.0547 13.125 13.6641C13.5156 13.3125 13.5156 12.7266 13.125 12.3359L11.2891 10.5L13.125 8.66406C13.5156 8.3125 13.5156 7.72656 13.125 7.33594C12.7734 6.98438 12.1875 6.98438 11.7969 7.33594L9.96094 9.17188L8.125 7.33594C7.77344 6.98438 7.1875 6.98438 6.83594 7.33594Z" fill="#6C6C6D" />
                </svg>
            </span>
            <div class="  f-bri mt-4 md:mt-7 text-center text-primary_color_6">
                <span class="text-h6 lg:text-h5 font-medium text-white">{{__( 'Already have account')}}</span>
                <button data-form-user="login-model" class="from-switch text-h6 lg:text-h5 font-medium ">{{__( 'sign in')}}</button>
            </div>
        </div>
        <!-- login popup -->
        <div id="login-model" class="relative hidden form-container  ">
            <div class="mb-6 md:mb-7  ">
                <h2 class="text-h4 md:text-h2 text-primary_color_5 font-medium"> {{__( 'login to continue')}}</h2>
                <h4 class="text-gray_9 lg:text-h4 text-h6 mt-1  ">{{__( 'Welcome back')}}</h4>
            </div>
            <div class="spinner " id="spinner" style="text-align: center;margin-left: 40%;">
                <div><span></span><span></span><span></span></div>
            </div>
            <div id="responceMessage " class="mb-2 responceMessage hidden text-center">
                <div class="mx-auto flex items-center justify-center bg-light bg-opacity-5 rounded-full w-14 h-14 border border-primary_color_o10_1   mb-5 fa-bounce">
                    <i class="fa-solid  fa-2x text-white "></i>
                </div>
                <h4 class="text-white massage"></h4>
            </div>
            <div id="errorMessages" class="errorMessages hidden text-center rounded-md mb-1 border border-red bg-red_light p-1 text-h8 ">
                <i class="fa-solid fa-triangle-exclamation text-white"></i>
                <span class="messages"></span>
            </div>
            <form id="login_form" action="{{ url('/web/login') }}" method="POST" class=" min-w ">
                @csrf
                <div class="mb-2">
                    <label for="" class="text-gray_9 text-h7 md:text-h5 md:mb-3  mb-m6 block">{{__('Mobile number or email')}}</label>
                    <div id="countrycontainr" class="countrycontainr countriescd flex gap-1 border-gray_s rounded-lg border items-center  px-2" style="margin-top: 10px;">
                        
                        <!-- <div class="selectContainer"></div> -->
                        <input type="text" value="" name="user_name" required placeholder="{{ __('Mobile number or email') }}"
                            class="text-h6 md:text-h5  w-full focus:border-primary_color_6 outline-0 bg-transparent     py-1 h-h-38 md:h-auto md:py-2  text-gray_6">
                    </div>
                    <!-- <input type="text" value="" name="user_name" placeholder="{{ __('Mobile number or email') }}"
                  class="text-white bg-transparent  border border-gray_s mt-1 w-full focus:border-primary_color_6  outline-0    rounded-lg  p-16-16  "> -->
                    @error('email')
                    <div class="_2OcwfRx4" data-qa="email-status-message">{{ $message }}</div>
                    @enderror
                    @if (Session::has('error_msg'))
                    <div class="mt-1 _2OcwfRx4 text-danger" data-qa="email-status-message">
                        <strong>{{ Session::get('error_msg') }}</strong>
                    </div>
                    @endif
                </div>

                <button class="rounded-full bg-primary_color_8 px-p-32 py-1 md:p-12-24 md:w-full mx-auto block md:mt-8 mt-4 text-center text-h6 md:text-h5 font-medium" style="width:100%"> {{__( 'Get code')}}
                </button>
            </form>
            <div>
                <p class="text-light text-center mb-7 mt-7 line-thr text-h6 md:text-h5">{{__('or login with')}}</p>
                <div class="flex gap-7">
                    <a href="javascript:void(0)" id="Google-login" class="bg-gray_35 rounded-lg md:rounded-2xl flex items-center gap-1 px-p-32 py-1 md:p-12-24 flex-1 justify-center text-h6 md:text-h5 font-medium">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.25 8.1875C15.25 12.625 12.2188 15.75 7.75 15.75C3.4375 15.75 0 12.3125 0 8C0 3.71875 3.4375 0.25 7.75 0.25C9.8125 0.25 11.5938 1.03125 12.9375 2.28125L10.8125 4.3125C8.0625 1.65625 2.9375 3.65625 2.9375 8C2.9375 10.7188 5.09375 12.9062 7.75 12.9062C10.8125 12.9062 11.9688 10.7188 12.125 9.5625H7.75V6.90625H15.125C15.1875 7.3125 15.25 7.6875 15.25 8.1875Z" fill="#FBF9FD" />
                        </svg>
                        Google</a>
                </div>
            </div>
            <span class=" close-modal absolute  @if($lang == 'ar') left-0 @else right-0 @endif  top-0 cursor-pointer">
                <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 20.5C4.45312 20.5 0 16.0469 0 10.5C0 4.99219 4.45312 0.5 10 0.5C15.5078 0.5 20 4.99219 20 10.5C20 16.0469 15.5078 20.5 10 20.5ZM6.83594 7.33594C6.44531 7.72656 6.44531 8.3125 6.83594 8.66406L8.67188 10.5L6.83594 12.3359C6.44531 12.7266 6.44531 13.3125 6.83594 13.6641C7.1875 14.0547 7.77344 14.0547 8.125 13.6641L9.96094 11.8281L11.7969 13.6641C12.1875 14.0547 12.7734 14.0547 13.125 13.6641C13.5156 13.3125 13.5156 12.7266 13.125 12.3359L11.2891 10.5L13.125 8.66406C13.5156 8.3125 13.5156 7.72656 13.125 7.33594C12.7734 6.98438 12.1875 6.98438 11.7969 7.33594L9.96094 9.17188L8.125 7.33594C7.77344 6.98438 7.1875 6.98438 6.83594 7.33594Z" fill="#6C6C6D" />
                </svg>
            </span>
            <div class="f-bri mt-4 md:mt-7 text-center text-primary_color_6">
                <span class="text-white text-h6 lg:text-h5"> {{__( 'Don’t have account?')}}</span>
                <button data-form-user="register-model" class="from-switch text-h6 lg:text-h5 font-medium">{{__( 'sign up')}}</button>
            </div>
        </div>
        <!-- otp -->
        <div id="otp-model" class="hidden px-2 lg:px-0 pop-modal fixed inset-0 flex items-center justify-center bg-dark bg-opacity-75  z-30  ">
            <div class="bg-dark_4  rounded-2xl   p-2  md:p-32-32 relative w-w-500">
                <div class="mb-6 md:mb-7  pb-2">
                    <div class="flex items-center gap-1">
                        <button id="returnTosign" class="@if($lang == 'ar') rotate-180 @endif">
                            <svg width="11" height="19" viewBox="0 0 11 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.601562 8.64062L8.10156 1.14062C8.57031 0.632812 9.39062 0.632812 9.85938 1.14062C10.3672 1.60938 10.3672 2.42969 9.85938 2.89844L3.25781 9.5L9.85938 16.1406C10.3672 16.6094 10.3672 17.4297 9.85938 17.8984C9.39062 18.4062 8.57031 18.4062 8.10156 17.8984L0.601562 10.3984C0.09375 9.92969 0.09375 9.10938 0.601562 8.64062Z" fill="#C4ACD3" />
                            </svg>
                        </button>
                        <h2 class="text-h4 md:text-h2 text-primary_color_5 font-medium"> {{__( 'Verification')}}</h2>
                    </div>
                    <h4 class="text-gray_9 lg:text-h4 text-h6 mt-1 ">{{__( 'Enter the 4-digit we just sent to your email')}}</h4>
                </div>
                <div class="spinner hidden" id="spinner" style="display: block;">
                    <div><span></span><span></span><span></span></div>
                </div>
                <form id="verificationForm" class="verification">
                    @csrf
                    <input type="hidden" name="otp" required id="otpField">
                    <input type="hidden" name='id' id="user_id" value="">
                    <div id="verification" class="flex @if($lang == 'ar') flex-row-reverse @endif">
                        <input type="number" autofocus maxlength="1" class=" text-center verification-pass h-16 w-16 col-span-1 mx-auto outline-0 focus:border-primary_color_6 text-white bg-transparent border border-gray_s p-16-16 rounded-lg" oninput="moveToNext(this, 1)" data-val="p-1">
                        <input type="number" maxlength="1" class="disabled text-center verification-pass h-16 w-16 col-span-1 mx-auto outline-0 focus:border-primary_color_6 text-white bg-transparent border border-gray_s p-16-16 rounded-lg" oninput="moveToNext(this, 2)" data-val="p-2">
                        <input type="number" maxlength="1" class="disabled text-center verification-pass h-16 w-16 col-span-1 mx-auto outline-0 focus:border-primary_color_6 text-white bg-transparent border border-gray_s p-16-16 rounded-lg" oninput="moveToNext(this, 3)" data-val="p-3">
                        <input type="number" maxlength="1" class="disabled text-center verification-pass h-16 w-16 col-span-1 mx-auto outline-0 focus:border-primary_color_6 text-white bg-transparent border border-gray_s p-16-16 rounded-lg" oninput="moveToNext(this, 4)" data-val="p-4">
                    </div>
                    <p class="error" id="login-error"></p>
                    <button type="button" id="confirmButton" class="rounded-full bg-primary_color_8 p-12-24 w-full block mt-7 text-center">{{__('Confirm')}}</button>
                </form>
            </div>
        </div>
        <!--  password popup -->
        <div id="password-model" class="hidden form-container">
            <div class="mb-6 md:mb-7  pb-2">
                <h2 class="text-h4 md:text-h2 text-primary_color_5 font-medium">{{ __('Forgot password') }} </h2>
                <h4 class="text-gray_9 lg:text-h4 text-h6 mt-1 ">{{ __('Please enter your email to reset the password') }}</h4>
            </div>
            <div id="responceMessage " class="mb-2 responceMessage hidden text-center">
                <div class="mx-auto flex items-center justify-center bg-light bg-opacity-5 rounded-full w-14 h-14 border border-primary_color_o10_1   mb-5 fa-bounce">
                    <i class="fa-solid  fa-2x text-white "></i>
                </div>
                <h4 class="text-white massage"></h4>
            </div>
            <form id="resetPasswordForm">
                @csrf
                <input type="hidden" value="user" name="type">
                <div class="mb-2">
                    <input name="email" type="email" placeholder="{{__( 'Email')}}"
                        class="text-dark mt-1 w-full focus:border-primary_color_6 outline-0  bg-light p-16-16 rounded-lg "
                        id="">
                    @error('email')
                    <div class="text-danger font-medium">{{ $message }}</div>
                    @enderror
                </div>
                <button id="submitResetForm" class="rounded-full bg-primary_color_8 p-12-24 w-full block md:mt-8 mt-4 text-center text-h6 md:text-h5">{{ __('Reset Password') }}
                </button>
            </form>
            <span class=" close-modal absolute -top-50 left-0 cursor-pointer">
                <i class="fa-regular fa-circle-xmark fa-2xl my-6"></i></span>
        </div>
    </div>
</div>

@if (!Auth::guard('appuser')->check())
<script>
    var isAuthenticated = false;
</script>
@endif


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script script>
    $(document).ready(function() {
        $('#toggle-password').on('click', function() {
            const passwordField = $('#password');
            const passwordFieldType = passwordField.attr('type');
            const icon = $(this).find('i');

            if (passwordFieldType === 'password') {
                passwordField.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });


    $(document).ready(function() {
        $('#countrycontainr').on("click", function(e) {
            e.stopPropagation();
            $(this).addClass("border-primary_color_6");
        });

        $(document).on("click", function() {
            $('#countrycontainr').removeClass("border-primary_color_6");
        });

        $('#countrycontainr').on("click", function(e) {
            e.stopPropagation();
        });
    });

    $(document).ready(function() {
        $(".auth_popup").on('click', function() {
            if (!isAuthenticated) {
                $(`#register-model`).addClass('hidden');
                $('#register-model').addClass('hidden');
                $('#register_popup').removeClass('hidden');
                 $('#login-model').removeClass('hidden')
            }
        })
    })
    $('.from-switch').on('click', function(e) {
        let model_id = $(this).attr('data-form-user');
        $('.form-container').addClass('hidden');
        $(`#${model_id}`).removeClass('hidden');

        if (model_id == 'register-model' || model_id == 'password-model') {
            $('.responceMessage').addClass("hidden")
        }
    });

    $(document).ready(function() {
        $('input[name="user_name"]').on('input', function() {
            if ($(this).val().match(/^\d+$/)) {
                $('#countriescode').removeClass('hidden-imp').css('display', 'block');
            } else {
                $('#countriescode').addClass('hidden-imp').css('display', 'none');
            }
        });
    });

    $('#Google-login').on('click', function(e) {
         $('.google_login').val(1);
       $('#tickets').submit();
       $('#ticketSlot').submit();

    });

    $("#returnTosign").on("click", function() {
        $('#otp-model').addClass("hidden");
        $('#register-model').removeClass("hidden");
    })

    $(window).on('load', function() {
        $('input[data-val="p-1"]').addClass('focus').focus();
    });

    function moveToNext(currentInput, index) {
        if (currentInput.value.length >= 1) {
            const nextInput = document.querySelector(`input[data-val='p-${index + 1}']`);
            if (nextInput) {
                nextInput.focus();
                nextInput.classList.add('focus');
                nextInput.classList.remove('disabled');
            }
        }
    }

    function setOtpValue() {
        const otpField = document.getElementById('otpField');
        const inputs = document.querySelectorAll('.verification-pass');
        let otp = '';
        inputs.forEach(input => {
            otp += input.value;
        });
        otpField.value = otp;
        return true; // Allow form submission
    }
    $('#confirmButton').on('click', function() {
        setOtpValue();

        // Perform AJAX call
        $.ajax({
            url: "{{ url('user/login/verify/otp') }}",
            type: 'POST',
            data: $('#verificationForm').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('.spinner').show();
            },
            success: function(response) {
                // Handle success (e.g., show a message, redirect, etc.)
                // console.log('OTP verified successfully:', response);
                if (response.success == false) {
                    // console.log("aa");
                    $("#login-error").html(response.msg);
                } else {
                    setTimeout(function() {
                        $('#otp-model').addClass('hidden');
                    }, 1000);
                    if ($('#tickets').length == 1 || $('#ticketSlot').length == 1) {
                        $('#tickets').submit();
                        $('#ticketSlot').submit();
                    } else {
                        window.location.reload();
                    }
                }
            },
            error: function(xhr) {
                // Handle error (e.g., show an error message)
                console.error('Error verifying OTP:', xhr.responseText);
            },
            complete: function() {
                $('.spinner').hide();
            }
        });
    });

    $(document).ready(function() {
        $('#login_form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ url('api/web/login') }}",
                type: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('.spinner').show();
                },
                success: function(response) {
                    if (response.success) {
                        let id = response.data.id
                        $('#user_id').val(id);
                        $('#otp-model').removeClass('hidden')
                        $('#login-model').addClass('hidden')
                    } else {
                        $('.errorMessages messages').html('');
                        $('.errorMessages').removeClass('hidden')
                        $('.errorMessages .messages').text(response);
                    }
                },
                error: function(xhr) {
                    // Handle validation errors or other failures 
                    let errors = xhr.responseJSON.errors;
                    console.log(errors);
                    // Clear any previous error messages
                    $('.errorMessages messages').html('');
                    $('.errorMessages').removeClass('hidden')
                    // Loop through the errors and display them
                    $.each(errors, function(field, messages) {
                        $('.errorMessages .messages').text(messages[0]);
                    });
                },
                complete: function() {
                    $('.spinner').hide();
                }
            });
        });


    });

    $('form input').on('input', function() {
        $('.errorMessages').addClass('hidden')
    })


    $(document).ready(function() {

        $('#submitRegisterForm').on('click', function(e) {
            e.preventDefault();

            var formData = $('#registerForm').serialize();
            let userName = $('#registerForm [name="name"]').val()
            $.ajax({
                url: "{{ url('user/register') }}",
                type: "POST",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('.spinner').show();
                },
                success: function(response) {
                    if (response.success) {
                        name = "name"
                        let massage = `welcome ${userName}`;
                        responseMessage('fa-check', massage);
                        setTimeout(function() {
                            $('#register_popup').addClass('hidden');
                        }, 1000);
                        if ($('#tickets').length == 1 || $('#ticketSlot').length == 1) {
                            $('#tickets').submit();
                            $('#ticketSlot').submit();
                        } else {
                            window.location.reload();
                        }
                    } else {
                        responseMessage('fa-xmark', "error");
                    }
                },
                error: function(xhr) {
                    // Handle validation errors or other failures 
                    let errors = xhr.responseJSON.errors;
                    console.log(errors);
                    // Clear any previous error messages
                    $('#errorMessages messages').html('');
                    $('#errorMessages').removeClass('hidden')
                    // Loop through the errors and display them
                    $.each(errors, function(field, messages) {
                        $('#errorMessages .messages').text(messages[0]);
                    });
                },
                complete: function() {
                    $('.spinner').hide();
                }
            });
        });
    });

    function responseMessage(icon, massage) {
        $('.fa-solid').addClass(icon)
        $('.responceMessage .massage').text(massage)
        $('.responceMessage').toggleClass('hidden');
    }

    $('.close-modal').click(function() {
        // $('#error-modal').addClass('hidden');
        $('.pop-modal').addClass('hidden');
        $('.form-container').addClass('hidden');

    });
</script>