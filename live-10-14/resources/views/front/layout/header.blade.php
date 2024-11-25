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
        --wab-background: hsla(129, 62%, 44%, 1);
        --wab-background-focus: hsla(129, 62%, 50%, 1);
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

<div class="hidden font-sans w-full m-0  ">
    <div class=" bg-primary shadow xxsm:max-md:hidden header">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-6">
                <div class="sm:flex sm:gap-3 sm:items-center">
                    @if (Auth::guard('appuser')->check())
                    <div class="flex justify-center relative dropdownScreenButton cursor-pointer ml-20 ">
                        <div class="pt-3 mr-5 rtl-ml-10">
                            <p class="font-poppins font-medium text-sm leading-5 text-white font-size-responsive rtl-lg-text-big">
                                {{ $user->name ?? ' ' }}
                            </p>
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
                </div>
            </div>
        </div>
    </div>
</div>



<nav class="container mt-8">
    <div class="bg-primary_color_15 p-24-16 rounded-2xl">
        <div class="flex justify-between ">
            <div class="flex w-full justify-between items-center">
                <div class="flex-shrink-0">
                    <a href="{{ url('/') }}" class="text-xl font-bold"><svg width="108" height="28" viewBox="0 0 108 28"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M39.7553 13.9404C39.6343 13.8193 39.5253 13.6849 39.4078 13.5554C39.5035 13.4549 39.5713 13.381 39.6427 13.3108C40.5556 12.3979 41.4782 11.4947 42.3777 10.5698C43.1889 9.73679 43.1647 8.50065 42.3511 7.70279C41.5411 6.90857 40.3219 6.90857 39.4793 7.73427C38.3412 8.84934 37.2237 9.98498 36.0953 11.1109C35.9936 11.2114 35.8786 11.2986 35.7237 11.4318C35.7237 11.1509 35.7237 10.9499 35.7237 10.7489C35.7237 8.55029 35.7261 6.35164 35.7224 4.15299C35.7212 3.27643 35.2478 2.55485 34.4875 2.24975C33.157 1.71462 31.6545 2.6057 31.6581 4.22684C31.6605 5.39639 31.6581 6.56594 31.6581 7.73669C31.6581 8.21856 31.6581 8.70042 31.6581 9.29367C28.8856 6.14945 24.4144 6.08165 21.6068 8.6871C18.7955 11.2974 18.6345 15.8824 21.2072 18.7118C23.7158 21.471 28.3541 22.04 31.6194 18.6113C31.6411 18.8135 31.6545 18.9902 31.6787 19.1646C31.8373 20.3051 32.7441 21.0654 33.8507 20.9855C34.927 20.9092 35.7103 20.0448 35.7224 18.9164C35.7285 18.3534 35.7224 17.7904 35.7224 17.2262C35.7636 17.2565 35.8048 17.2868 35.8447 17.3158C36.0687 17.041 36.2927 16.765 36.5397 16.4599C36.9344 16.8594 37.3 17.2323 37.6693 17.6028C38.5955 18.5302 39.5096 19.4709 40.4539 20.3801C41.4007 21.293 42.8971 21.0933 43.5872 20.0012C43.9722 19.391 44.0667 18.2336 43.2652 17.4466C42.0847 16.2879 40.9224 15.1111 39.7553 13.9404ZM31.623 16.4344C31.5285 16.3654 31.4595 16.3291 31.4075 16.2746C30.7137 15.5421 29.3868 15.6438 28.7572 16.3751C28.2282 16.9889 27.5199 17.3025 26.7002 17.4006C25.1057 17.5931 23.4688 16.547 23.0281 14.7528C22.5209 12.6885 23.9132 10.715 25.8297 10.4172C26.9254 10.2477 27.8577 10.5819 28.6519 11.3216C28.9715 11.6194 29.3142 11.8531 29.7476 11.9112C30.3651 11.9936 30.9026 11.8107 31.336 11.3555C31.416 11.272 31.485 11.1775 31.6218 11.0117V16.4344H31.623ZM107.496 19.7554C107.424 22.6369 105.373 24.7205 102.755 25.1298C101.033 25.3985 99.3441 25.2145 97.6951 24.6564C96.89 24.384 96.419 23.7871 96.3803 22.982C96.3403 22.1381 96.804 21.3487 97.5547 21.0388C98.0741 20.8245 98.5765 20.816 99.1189 21.0775C100.373 21.6817 101.641 21.6695 102.885 20.9952C103.183 20.8329 103.358 20.6308 103.283 20.2772C103.31 20.2627 103.338 20.2482 103.366 20.2336C103.353 20.2203 103.338 20.2058 103.325 20.1925C103.31 20.2203 103.296 20.2482 103.281 20.276C103.078 20.3426 102.87 20.4007 102.67 20.4782C101.448 20.954 100.2 21.0158 98.9337 20.7119C98.2218 20.5412 97.6721 20.0884 97.1854 19.575C96.2483 18.5859 95.7495 17.4103 95.7531 16.0313C95.7604 13.7915 95.7531 11.5529 95.7555 9.31304C95.758 7.96431 96.7023 7.09138 98.0426 7.18824C98.9615 7.25483 99.6795 7.94978 99.6928 8.86992C99.7206 10.8264 99.7109 12.7829 99.7194 14.7407C99.7206 15.0022 99.7218 15.2661 99.7594 15.5252C99.9676 16.9805 101.603 17.7105 102.791 16.8667C103.268 16.5289 103.577 16.0664 103.574 15.4441C103.563 13.3265 103.563 11.209 103.544 9.09027C103.536 8.13986 104.108 7.31537 104.964 7.07686C106.074 6.76812 106.966 7.34321 107.332 8.05511C107.439 8.26093 107.509 8.51034 107.51 8.74037C107.521 12.4125 107.588 16.0858 107.496 19.7554ZM14.2239 4.7765C14.4212 5.78261 13.7359 6.85408 12.7359 7.07928C12.4829 7.13618 12.2165 7.14466 11.9562 7.14708C11.0518 7.15434 10.1474 7.15071 9.15946 7.15071V7.88924C9.15946 11.3398 9.15583 14.7903 9.16188 18.2396C9.16309 19.1198 8.84589 19.8147 8.04439 20.224C6.70051 20.9092 5.12658 19.9685 5.09874 18.4587C5.07815 17.3691 5.0951 16.2795 5.0951 15.1898C5.0951 12.7285 5.0951 10.2671 5.0951 7.80449V7.1495C4.13259 7.1495 3.21245 7.15313 2.29231 7.14708C2.05259 7.14587 1.80802 7.13376 1.57436 7.08412C0.551306 6.86861 -0.070999 5.98722 0.00648645 4.89394C0.0742862 3.94959 0.906044 3.1481 1.8843 3.08635C1.94483 3.08272 2.00537 3.08393 2.06591 3.08393C5.43531 3.08393 8.80472 3.0803 12.1741 3.08635C13.2383 3.08877 14.0217 3.7474 14.2227 4.7765H14.2239ZM18.7556 19.7881C18.7556 19.9298 18.7664 20.0714 18.7495 20.2106C18.6938 20.6598 18.4093 20.9637 17.9565 20.9758C17.1308 21 16.3027 21.0012 15.4769 20.9758C15.006 20.9613 14.7215 20.6368 14.6779 20.1477C14.6621 19.9673 14.6755 19.7845 14.6755 19.6041C14.6755 17.7093 14.6755 15.8133 14.6755 13.9186C14.6755 12.0238 14.6755 10.1278 14.6755 8.23308C14.6755 8.09143 14.6646 7.94978 14.6779 7.80934C14.7287 7.29478 15.0229 6.99453 15.5411 6.98242C16.3269 6.96426 17.1138 6.96668 17.8996 6.98242C18.4117 6.99332 18.7095 7.29963 18.7531 7.81297C18.7628 7.93283 18.7556 8.05511 18.7556 8.17618C18.7556 12.0468 18.7556 15.9187 18.7556 19.7893V19.7881ZM18.8911 5.87946C18.8694 6.49329 18.5594 6.80566 17.9468 6.81655C17.2204 6.82987 16.4939 6.82745 15.7675 6.81655C15.1876 6.80808 14.8728 6.57683 14.8365 6.0078C14.7832 5.18573 14.782 4.35518 14.828 3.53189C14.8571 3.0125 15.1949 2.75341 15.7264 2.74251C16.4722 2.72677 17.2192 2.72677 17.9662 2.74251C18.5497 2.75462 18.8609 3.06093 18.8887 3.63965C18.9305 4.46538 18.9267 4.90961 18.8911 5.87946ZM55.4243 9.3312C52.7087 5.94605 48.5632 6.23299 46.1164 8.55877C44.827 9.78279 44.0667 11.3047 43.8935 13.065C43.6587 15.4489 44.3524 17.5192 46.212 19.1222C47.8792 20.5605 49.8284 21.0933 51.9896 20.8608C53.147 20.7361 54.2269 20.3656 55.1241 19.5762C55.5575 19.1949 55.8771 18.7445 56.0006 18.1682C56.204 17.2141 55.4025 16.1899 54.4279 16.1366C53.8698 16.1063 53.4121 16.3388 52.9618 16.61C52.3043 17.0059 51.5985 17.1996 50.8297 17.1258C49.694 17.0168 48.7194 16.6403 48.1395 15.4138H48.8114C50.768 15.4138 52.7257 15.4162 54.6822 15.4138C56.1883 15.4114 57.1351 14.3472 56.9038 12.8519C56.7053 11.565 56.2537 10.3639 55.4256 9.33241L55.4243 9.3312ZM47.2448 12.4996C47.2811 11.14 48.5657 10.1424 50.05 9.99709C52.1239 9.79369 53.2378 11.0855 53.4993 12.4996H47.2448ZM79.5599 0.0922652C75.9701 0.499064 72.9579 2.11173 70.6103 4.85641C67.6985 8.26093 66.7324 12.2284 67.4503 16.6475C66.2602 15.8824 65.6863 15.8945 64.5834 16.5604C64.3691 16.6899 64.1088 16.7407 63.8218 16.8449V11.439C64.1439 11.439 64.4429 11.4403 64.7408 11.439C65.8837 11.433 66.7409 10.64 66.8183 9.51523C66.8886 8.49702 66.1125 7.57204 65.0398 7.42675C64.6463 7.37348 64.2468 7.36985 63.7928 7.33958C63.7928 6.84803 63.8013 6.36859 63.7904 5.89036C63.7746 5.22084 63.4913 4.68086 62.9332 4.31159C61.674 3.47862 60.0396 4.35397 59.9609 5.90126C59.9379 6.36133 59.9572 6.82503 59.9572 7.3311C58.2719 7.50181 57.4511 8.15802 57.4196 9.34573C57.4063 9.86149 57.5697 10.3421 57.9051 10.7417C58.4293 11.3676 59.1461 11.5032 59.9572 11.4427V12.0831C59.9572 13.898 59.9536 15.7141 59.9621 17.5289C59.9621 17.765 59.9936 18.0144 60.0747 18.2336C60.5904 19.6186 61.9888 20.5823 63.4659 20.6477C64.5919 20.6973 65.5979 20.3487 66.5786 19.8499C67.2094 19.5278 67.6997 19.0992 67.868 18.2856C69.1938 22.0255 71.528 24.7992 75.0136 26.5197C78.5199 28.2498 82.1714 28.4592 85.8556 27.1698C92.4721 24.8525 96.5582 17.9224 94.7773 10.6085C93.1658 3.99075 86.8931 -0.735861 79.5611 0.0946867L79.5599 0.0922652ZM86.7309 19.5472C86.1231 20.1065 85.4415 20.5739 84.663 20.8608C83.7986 21.1792 82.9135 21.4032 81.974 21.3862C80.5224 21.3596 79.0695 21.3717 77.6167 21.3766C76.975 21.379 76.4629 21.1078 76.0088 20.6792C75.6795 20.3692 75.5524 19.9951 75.5536 19.5496C75.5609 17.6936 75.556 15.8376 75.556 13.9815C75.556 11.7829 75.5972 9.58182 75.5391 7.38438C75.5088 6.23541 76.1905 5.72328 76.975 5.42302C77.2825 5.30559 77.6409 5.28258 77.9762 5.27774C79.0054 5.25958 80.0345 5.24989 81.0623 5.27653C82.8554 5.32254 84.5032 5.84193 85.8386 7.05991C86.6498 7.79965 87.1353 8.744 87.1958 9.90992C87.26 11.1327 86.806 12.0916 85.9439 12.9718C86.8084 13.4222 87.3992 14.0288 87.7503 14.8569C87.7975 14.9683 87.8787 15.076 87.8883 15.1898C88.0215 16.7795 88.036 18.3474 86.7309 19.5496V19.5472ZM78.2426 12.1667V7.37106C78.3637 7.35532 78.4956 7.32384 78.6276 7.32263C79.4521 7.31779 80.279 7.29236 81.1023 7.32747C82.1883 7.37469 83.1714 7.704 83.9535 8.51397C84.6158 9.19923 84.6061 10.037 84.4717 10.8761C84.4063 11.2817 84.0468 11.5613 83.7053 11.7671C83.2973 12.0141 82.853 12.1776 82.3493 12.1715C81.003 12.1534 79.6555 12.1655 78.2438 12.1655L78.2426 12.1667ZM85.1715 15.3872C85.5626 16.2795 85.496 17.2517 84.8434 18.0568C84.2538 18.7844 83.4547 19.207 82.5213 19.3062C81.8251 19.3801 81.1217 19.4007 80.4219 19.4188C79.7173 19.437 79.0126 19.4225 78.2644 19.4225V14.2237C79.9884 14.2237 81.681 14.1898 83.3724 14.2382C84.1557 14.2612 84.8676 14.691 85.1727 15.386L85.1715 15.3872Z"
                                fill="#FBF9FD" />
                        </svg>
                    </a>
                </div>
                <ul class="hidden lg:flex space-x-8 ml-10 items-center">
                    <li>
                        <a href="{{ url('/all-events') }}" class="text-light hover:text-primary_color_9">Upcoming events</a>
                    </li>
                    <li class="relative flex items-center" id="menu-droplist" aria-expanded="true" aria-haspopup="true">
                        <a href="#" class="text-light hover:text-primary_color_9 flex items-center gap-2" id="toggleCategories">
                            <span>Categories </span>
                            <svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.28125 8.71875L1.28125 2.71875C0.875 2.34375 0.875 1.6875 1.28125 1.3125C1.65625 0.90625 2.3125 0.90625 2.6875 1.3125L8 6.59375L13.2812 1.3125C13.6562 0.90625 14.3125 0.90625 14.6875 1.3125C15.0938 1.6875 15.0938 2.34375 14.6875 2.71875L8.6875 8.71875C8.3125 9.125 7.65625 9.125 7.28125 8.71875Z"
                                    fill="#E0D3E8"></path>
                            </svg></a>
                        <div class="relative inline-block text-left">
                            <div id="dropdownMenu"
                                class="hidden absolute left-1/2 transform -translate-x-1/2   z-10 mt-3 w-56  rounded-md bg-primary_color_8  transition ease-out duration-100 transform opacity-0 scale-95"
                                role="menu" aria-orientation="vertical" aria-labelledby="menu-droplist"
                                tabindex="-1">
                                <div class="py-1" role="none">
                                    @php
                                    if (!isset($catactive)) {
                                    $catactive = null;
                                    }
                                    @endphp
                                    @if (isset($category))
                                    @foreach ($category as $item)
                                    <a href="{{ url('/events-category/' . $item->id . '/' . $item->name) }}"
                                        class="block px-4 py-2 text-sm text-light hover:text-primary_color_5"
                                        role="menuitem" tabindex="-1" id="menu-item-0">
                                        @if (session('direction') == 'rtl')
                                        {{ $item->ar_name }}
                                        @else
                                        {{ $item->name }}
                                        @endif
                                    </a>
                                    @endforeach
                                    @endif
                                    <a href="{{ url('/all-category') }}"
                                        class="block px-4 py-2 text-sm text-light hover:text-primary_color_5"
                                        role="menuitem" tabindex="-1" id="menu-item-1">{{ __('All categories') }}</a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <a href="#" class="text-light hover:text-primary_color_9">Cities</a>
                    </li>
                    <li>
                        <a href="{{ url('/contact') }}" class="text-light hover:text-primary_color_9">Contact</a>
                    </li>
                </ul>
                <div class="hidden lg:flex  flex-row items-center gap-3">
                    @if (Auth::guard('appuser')->check())
                    <div>
                        <div class="pt-3 mr-5 rtl-ml-10">
                            <p class="font-poppins font-medium text-sm leading-5 text-white font-size-responsive rtl-lg-text-big">
                                {{ $user->name ?? ' ' }}
                            </p>
                        </div>
                        <div class="">
                            <img src="{{ asset('images/upload/' . $user->image) }}"
                                class="w-10 h-10 bg-cover object-contain border border-gray-light rounded-full"
                                alt="">
                        </div>
                    </div>
                    @else
                    <a href="{{ url('user/login') }}" class=" f-bri rounded-full bg-primary_color_8 p-6-8  w-24 text-center  flex items-center justify-center h-8">{{ __('Sign In') }}
                    </a>
                    @endif

                    @if (Session::has('local'))
                    {{ Session::get('local') }}
                    @endif
                    @php
                    $language = \App\Models\Language::where('status', 1)->where('direction',"!=",session('direction'))->get();
                    @endphp

                    @foreach ($language as $key => $item)
                    @if($item->name == "English")
                    <a class="f-bri rounded-full border-2 border-primary_color_8 p-6-8 flex items-center gap-3 w-24  h-8" type="button" href="{{ url('change-language/' . $item->name) }}">
                        <svg width="14" height="15" viewBox="0 0 14 15" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7 0.75C10.8555 0.75 14 3.89453 14 7.75C14 11.6328 10.8555 14.75 7 14.75C3.11719 14.75 0 11.6328 0 7.75C0 3.89453 3.11719 0.75 7 0.75ZM7 13.875C7.4375 13.875 8.09375 13.4922 8.66797 12.3164C8.94141 11.7695 9.1875 11.1133 9.32422 10.375H4.64844C4.78516 11.1133 5.03125 11.7695 5.30469 12.3164C5.87891 13.4922 6.53516 13.875 7 13.875ZM4.48438 9.5H9.48828C9.57031 8.95312 9.625 8.37891 9.625 7.75C9.625 7.14844 9.57031 6.57422 9.48828 6H4.48438C4.40234 6.57422 4.375 7.14844 4.375 7.75C4.375 8.37891 4.40234 8.95312 4.48438 9.5ZM9.32422 5.125C9.1875 4.38672 8.94141 3.75781 8.66797 3.21094C8.09375 2.03516 7.4375 1.625 7 1.625C6.53516 1.625 5.87891 2.03516 5.30469 3.21094C5.03125 3.75781 4.78516 4.38672 4.64844 5.125H9.32422ZM10.3633 6C10.4453 6.57422 10.5 7.14844 10.5 7.75C10.5 8.37891 10.4453 8.95312 10.3633 9.5H12.8516C13.0156 8.95312 13.125 8.37891 13.125 7.75C13.125 7.14844 13.0156 6.57422 12.8516 6H10.3633ZM8.94141 1.95312C9.51562 2.71875 9.98047 3.83984 10.2266 5.125H12.5234C11.8125 3.64844 10.5273 2.5 8.94141 1.95312ZM5.03125 1.95312C3.44531 2.5 2.16016 3.64844 1.44922 5.125H3.74609C3.99219 3.83984 4.45703 2.71875 5.03125 1.95312ZM0.875 7.75C0.875 8.37891 0.957031 8.95312 1.12109 9.5H3.60938C3.52734 8.95312 3.5 8.37891 3.5 7.75C3.5 7.14844 3.52734 6.57422 3.60938 6H1.12109C0.957031 6.57422 0.875 7.14844 0.875 7.75ZM12.5234 10.375H10.2266C9.98047 11.6875 9.51562 12.7812 8.94141 13.5742C10.5273 13.0273 11.8125 11.8789 12.5234 10.375ZM3.74609 10.375H1.44922C2.16016 11.8789 3.44531 13.0273 5.03125 13.5742C4.45703 12.7812 3.99219 11.6875 3.74609 10.375Z"
                                fill="#C4ACD3" />
                        </svg>
                        En
                        <svg width="12" height="7" viewBox="0 0 12 7" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.6875 1.49219L6.46484 6.49609C6.30078 6.63281 6.13672 6.6875 6 6.6875C5.83594 6.6875 5.67188 6.63281 5.53516 6.52344L0.285156 1.49219C0.0117188 1.24609 0.0117188 0.808594 0.257812 0.5625C0.503906 0.289062 0.941406 0.289062 1.1875 0.535156L6 5.12891L10.7852 0.535156C11.0312 0.289062 11.4688 0.289062 11.7148 0.5625C11.9609 0.808594 11.9609 1.24609 11.6875 1.49219Z"
                                fill="#C4ACD3" />
                        </svg>
                    </a>
                    @else
                    <a class="f-bri rounded-full border-2 border-primary_color_8 p-6-8 flex items-center gap-3 w-24  h-8" type="button" href="{{ url('change-language/' . $item->name) }}">
                        <svg width="14" height="15" viewBox="0 0 14 15" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7 0.75C10.8555 0.75 14 3.89453 14 7.75C14 11.6328 10.8555 14.75 7 14.75C3.11719 14.75 0 11.6328 0 7.75C0 3.89453 3.11719 0.75 7 0.75ZM7 13.875C7.4375 13.875 8.09375 13.4922 8.66797 12.3164C8.94141 11.7695 9.1875 11.1133 9.32422 10.375H4.64844C4.78516 11.1133 5.03125 11.7695 5.30469 12.3164C5.87891 13.4922 6.53516 13.875 7 13.875ZM4.48438 9.5H9.48828C9.57031 8.95312 9.625 8.37891 9.625 7.75C9.625 7.14844 9.57031 6.57422 9.48828 6H4.48438C4.40234 6.57422 4.375 7.14844 4.375 7.75C4.375 8.37891 4.40234 8.95312 4.48438 9.5ZM9.32422 5.125C9.1875 4.38672 8.94141 3.75781 8.66797 3.21094C8.09375 2.03516 7.4375 1.625 7 1.625C6.53516 1.625 5.87891 2.03516 5.30469 3.21094C5.03125 3.75781 4.78516 4.38672 4.64844 5.125H9.32422ZM10.3633 6C10.4453 6.57422 10.5 7.14844 10.5 7.75C10.5 8.37891 10.4453 8.95312 10.3633 9.5H12.8516C13.0156 8.95312 13.125 8.37891 13.125 7.75C13.125 7.14844 13.0156 6.57422 12.8516 6H10.3633ZM8.94141 1.95312C9.51562 2.71875 9.98047 3.83984 10.2266 5.125H12.5234C11.8125 3.64844 10.5273 2.5 8.94141 1.95312ZM5.03125 1.95312C3.44531 2.5 2.16016 3.64844 1.44922 5.125H3.74609C3.99219 3.83984 4.45703 2.71875 5.03125 1.95312ZM0.875 7.75C0.875 8.37891 0.957031 8.95312 1.12109 9.5H3.60938C3.52734 8.95312 3.5 8.37891 3.5 7.75C3.5 7.14844 3.52734 6.57422 3.60938 6H1.12109C0.957031 6.57422 0.875 7.14844 0.875 7.75ZM12.5234 10.375H10.2266C9.98047 11.6875 9.51562 12.7812 8.94141 13.5742C10.5273 13.0273 11.8125 11.8789 12.5234 10.375ZM3.74609 10.375H1.44922C2.16016 11.8789 3.44531 13.0273 5.03125 13.5742C4.45703 12.7812 3.99219 11.6875 3.74609 10.375Z"
                                fill="#C4ACD3" />
                        </svg>
                        AR
                        <svg width="12" height="7" viewBox="0 0 12 7" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.6875 1.49219L6.46484 6.49609C6.30078 6.63281 6.13672 6.6875 6 6.6875C5.83594 6.6875 5.67188 6.63281 5.53516 6.52344L0.285156 1.49219C0.0117188 1.24609 0.0117188 0.808594 0.257812 0.5625C0.503906 0.289062 0.941406 0.289062 1.1875 0.535156L6 5.12891L10.7852 0.535156C11.0312 0.289062 11.4688 0.289062 11.7148 0.5625C11.9609 0.808594 11.9609 1.24609 11.6875 1.49219Z"
                                fill="#C4ACD3" />
                        </svg>
                    </a>
                    @endif
                    @endforeach

                    <button class="rounded-full bg-primary_color_8 w-8 h-8 flex items-center justify-center">
                        <svg width="17" height="16" viewBox="0 0 17 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M15.75 14.7188C16.0625 15.0312 16.0625 15.5 15.75 15.7812C15.625 15.9375 15.4375 16 15.25 16C15.0312 16 14.8438 15.9375 14.6875 15.7812L10.5 11.5938C9.375 12.5 7.96875 13 6.46875 13C2.90625 13 0 10.0938 0 6.5C0 2.9375 2.875 0 6.46875 0C10.0312 0 12.9688 2.9375 12.9688 6.5C12.9688 8.03125 12.4688 9.4375 11.5625 10.5312L15.75 14.7188ZM1.5 6.5C1.5 9.28125 3.71875 11.5 6.5 11.5C9.25 11.5 11.5 9.28125 11.5 6.5C11.5 3.75 9.25 1.5 6.5 1.5C3.71875 1.5 1.5 3.75 1.5 6.5Z"
                                fill="#EEE8F4" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex items-center lg:hidden">
                <button id="menu-btn" class="text-light hover:text-primary_color_9 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>


<div id="sidebar"
    class=" fixed inset-0 bg-dark bg-opacity-75 z-50 transform -translate-x-full transition-transform duration-300">
    <div class="w-64 bg-primary_color_15 p-24-16 h-full shadow-md p-4">
        <button id="close-btn" class="text-light hover:text-primary_color_9 focus:outline-none">
            <i class="fa-regular fa-circle-xmark fa-2xl my-6"></i>
        </button>
        <ul class="mt-4 flex flex-col gap-3">
            <li>
                <a href="{{ url('/all-events') }}" class="text-light hover:text-primary_color_9"> {{ __('Upcoming events') }}</a>
            </li>
            <li>
                <a href="#" class="text-light hover:text-primary_color_9 flex items-center gap-2" id="toggleCategories">
                    <span>Categories </span>
                    <svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.28125 8.71875L1.28125 2.71875C0.875 2.34375 0.875 1.6875 1.28125 1.3125C1.65625 0.90625 2.3125 0.90625 2.6875 1.3125L8 6.59375L13.2812 1.3125C13.6562 0.90625 14.3125 0.90625 14.6875 1.3125C15.0938 1.6875 15.0938 2.34375 14.6875 2.71875L8.6875 8.71875C8.3125 9.125 7.65625 9.125 7.28125 8.71875Z"
                            fill="#E0D3E8"></path>
                    </svg></a>
                <ul id="categoryList" class="hidden flex flex-col mt-2 gap-2">
                    @php
                    if (!isset($catactive)) {
                    $catactive = null;
                    }
                    @endphp
                    @if (isset($category))
                    @foreach ($category as $item)
                    <a href="{{ url('/events-category/' . $item->id . '/' . $item->name) }}"
                        class="block px-4 py-2 text-sm text-light hover:text-primary_color_5"
                        role="menuitem" tabindex="-1" id="menu-item-0">
                        @if (session('direction') == 'rtl')
                        {{ $item->ar_name }}
                        @else
                        {{ $item->name }}
                        @endif
                    </a>
                    @endforeach
                    @endif
                    <a href="{{ url('/all-category') }}"
                        class="block px-4 py-2 text-sm text-light hover:text-primary_color_5"
                        role="menuitem" tabindex="-1" id="menu-item-1">{{ __('All categories') }}</a>
                </ul>
            </li>

            <li>
                <a href="#" class="text-light hover:text-primary_color_9">Cities</a>
            </li>
            <li>
                <a href="{{ url('/contact') }}" class="text-light hover:text-primary_color_9">{{ __('Contact') }}</a>
            </li>
        </ul>
        <div class="flex  flex-row items-center gap-3 mt-4">
            @if (Auth::guard('appuser')->check())
            <div>
                <div class="pt-3 mr-5 rtl-ml-10">
                    <p class="font-poppins font-medium text-sm leading-5 text-white font-size-responsive rtl-lg-text-big">
                        {{ $user->name ?? ' ' }}
                    </p>
                </div>
                <div class="">
                    <img src="{{ asset('images/upload/' . $user->image) }}"
                        class="w-10 h-10 bg-cover object-contain border border-gray-light rounded-full"
                        alt="">
                </div>
            </div>
            @else
            <a href="{{ url('user/login') }}" class="rounded-full bg-primary_color_8 p-6-8  w-24 text-center  h-8">{{ __('Sign In') }}
            </a>
            @endif

            @if (Session::has('local'))
            {{ Session::get('local') }}
            @endif
            @php
            $language = \App\Models\Language::where('status', 1)->where('direction',"!=",session('direction'))->get();
            @endphp

            @foreach ($language as $key => $item)
            @if($item->name == "English")
            <a class="f-bri rounded-full border-2 border-primary_color_8 p-6-8 flex items-center gap-3 w-24  h-8" type="button" href="{{ url('change-language/' . $item->name) }}">
                <svg width="14" height="15" viewBox="0 0 14 15" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M7 0.75C10.8555 0.75 14 3.89453 14 7.75C14 11.6328 10.8555 14.75 7 14.75C3.11719 14.75 0 11.6328 0 7.75C0 3.89453 3.11719 0.75 7 0.75ZM7 13.875C7.4375 13.875 8.09375 13.4922 8.66797 12.3164C8.94141 11.7695 9.1875 11.1133 9.32422 10.375H4.64844C4.78516 11.1133 5.03125 11.7695 5.30469 12.3164C5.87891 13.4922 6.53516 13.875 7 13.875ZM4.48438 9.5H9.48828C9.57031 8.95312 9.625 8.37891 9.625 7.75C9.625 7.14844 9.57031 6.57422 9.48828 6H4.48438C4.40234 6.57422 4.375 7.14844 4.375 7.75C4.375 8.37891 4.40234 8.95312 4.48438 9.5ZM9.32422 5.125C9.1875 4.38672 8.94141 3.75781 8.66797 3.21094C8.09375 2.03516 7.4375 1.625 7 1.625C6.53516 1.625 5.87891 2.03516 5.30469 3.21094C5.03125 3.75781 4.78516 4.38672 4.64844 5.125H9.32422ZM10.3633 6C10.4453 6.57422 10.5 7.14844 10.5 7.75C10.5 8.37891 10.4453 8.95312 10.3633 9.5H12.8516C13.0156 8.95312 13.125 8.37891 13.125 7.75C13.125 7.14844 13.0156 6.57422 12.8516 6H10.3633ZM8.94141 1.95312C9.51562 2.71875 9.98047 3.83984 10.2266 5.125H12.5234C11.8125 3.64844 10.5273 2.5 8.94141 1.95312ZM5.03125 1.95312C3.44531 2.5 2.16016 3.64844 1.44922 5.125H3.74609C3.99219 3.83984 4.45703 2.71875 5.03125 1.95312ZM0.875 7.75C0.875 8.37891 0.957031 8.95312 1.12109 9.5H3.60938C3.52734 8.95312 3.5 8.37891 3.5 7.75C3.5 7.14844 3.52734 6.57422 3.60938 6H1.12109C0.957031 6.57422 0.875 7.14844 0.875 7.75ZM12.5234 10.375H10.2266C9.98047 11.6875 9.51562 12.7812 8.94141 13.5742C10.5273 13.0273 11.8125 11.8789 12.5234 10.375ZM3.74609 10.375H1.44922C2.16016 11.8789 3.44531 13.0273 5.03125 13.5742C4.45703 12.7812 3.99219 11.6875 3.74609 10.375Z"
                        fill="#C4ACD3" />
                </svg>
                En
                <svg width="12" height="7" viewBox="0 0 12 7" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M11.6875 1.49219L6.46484 6.49609C6.30078 6.63281 6.13672 6.6875 6 6.6875C5.83594 6.6875 5.67188 6.63281 5.53516 6.52344L0.285156 1.49219C0.0117188 1.24609 0.0117188 0.808594 0.257812 0.5625C0.503906 0.289062 0.941406 0.289062 1.1875 0.535156L6 5.12891L10.7852 0.535156C11.0312 0.289062 11.4688 0.289062 11.7148 0.5625C11.9609 0.808594 11.9609 1.24609 11.6875 1.49219Z"
                        fill="#C4ACD3" />
                </svg>
            </a>
            @else
            <a class="f-bri rounded-full border-2 border-primary_color_8 p-6-8 flex items-center gap-3 w-24  h-8" type="button" href="{{ url('change-language/' . $item->name) }}">
                <svg width="14" height="15" viewBox="0 0 14 15" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M7 0.75C10.8555 0.75 14 3.89453 14 7.75C14 11.6328 10.8555 14.75 7 14.75C3.11719 14.75 0 11.6328 0 7.75C0 3.89453 3.11719 0.75 7 0.75ZM7 13.875C7.4375 13.875 8.09375 13.4922 8.66797 12.3164C8.94141 11.7695 9.1875 11.1133 9.32422 10.375H4.64844C4.78516 11.1133 5.03125 11.7695 5.30469 12.3164C5.87891 13.4922 6.53516 13.875 7 13.875ZM4.48438 9.5H9.48828C9.57031 8.95312 9.625 8.37891 9.625 7.75C9.625 7.14844 9.57031 6.57422 9.48828 6H4.48438C4.40234 6.57422 4.375 7.14844 4.375 7.75C4.375 8.37891 4.40234 8.95312 4.48438 9.5ZM9.32422 5.125C9.1875 4.38672 8.94141 3.75781 8.66797 3.21094C8.09375 2.03516 7.4375 1.625 7 1.625C6.53516 1.625 5.87891 2.03516 5.30469 3.21094C5.03125 3.75781 4.78516 4.38672 4.64844 5.125H9.32422ZM10.3633 6C10.4453 6.57422 10.5 7.14844 10.5 7.75C10.5 8.37891 10.4453 8.95312 10.3633 9.5H12.8516C13.0156 8.95312 13.125 8.37891 13.125 7.75C13.125 7.14844 13.0156 6.57422 12.8516 6H10.3633ZM8.94141 1.95312C9.51562 2.71875 9.98047 3.83984 10.2266 5.125H12.5234C11.8125 3.64844 10.5273 2.5 8.94141 1.95312ZM5.03125 1.95312C3.44531 2.5 2.16016 3.64844 1.44922 5.125H3.74609C3.99219 3.83984 4.45703 2.71875 5.03125 1.95312ZM0.875 7.75C0.875 8.37891 0.957031 8.95312 1.12109 9.5H3.60938C3.52734 8.95312 3.5 8.37891 3.5 7.75C3.5 7.14844 3.52734 6.57422 3.60938 6H1.12109C0.957031 6.57422 0.875 7.14844 0.875 7.75ZM12.5234 10.375H10.2266C9.98047 11.6875 9.51562 12.7812 8.94141 13.5742C10.5273 13.0273 11.8125 11.8789 12.5234 10.375ZM3.74609 10.375H1.44922C2.16016 11.8789 3.44531 13.0273 5.03125 13.5742C4.45703 12.7812 3.99219 11.6875 3.74609 10.375Z"
                        fill="#C4ACD3" />
                </svg>
                AR
                <svg width="12" height="7" viewBox="0 0 12 7" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M11.6875 1.49219L6.46484 6.49609C6.30078 6.63281 6.13672 6.6875 6 6.6875C5.83594 6.6875 5.67188 6.63281 5.53516 6.52344L0.285156 1.49219C0.0117188 1.24609 0.0117188 0.808594 0.257812 0.5625C0.503906 0.289062 0.941406 0.289062 1.1875 0.535156L6 5.12891L10.7852 0.535156C11.0312 0.289062 11.4688 0.289062 11.7148 0.5625C11.9609 0.808594 11.9609 1.24609 11.6875 1.49219Z"
                        fill="#C4ACD3" />
                </svg>
            </a>
            @endif
            @endforeach
        </div>
    </div>
</div>

<div class="dev ">
    <a href="#" class="wab-icon js-whatsapp" data-name="John Doe" aria-label="Chat with us on WhatsApp">
        <img class="wab-icon__image rtl-transform-270" src="https://assets.codepen.io/82811/whatsapp-logo.svg" alt="" height="70px" width="70px" />
    </a>
</div>

<script>
    var check = document.getElementById("check");
    if (check) {
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

    document.addEventListener("DOMContentLoaded", function() {
        var whatsappButtons = document.getElementsByClassName("js-whatsapp");
        //var phoneNumber = "+917405072262"; // Your WhatsApp Business phone number with country code
        var phoneNumber = "+966556046094";
        var message = "Hello, I would like to know more about your services."; // Initial message sent to user

        Array.prototype.forEach.call(whatsappButtons, function(button) {
            button.addEventListener("click", function(e) {
                e.preventDefault;
                var whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(
        message
      )}`;
                window.open(whatsappUrl, "_blank");
            });
        });
    });
</script>