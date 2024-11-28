@php

$language = \App\Models\Language::where('status', 1)->where('direction',"!=",session('direction'))->get();
$showLinkBanner = \App\Models\Setting::find(1,['show_link_banner','googleplay_link','appstore_link']);
@endphp
<?php $url =  $_SERVER['REQUEST_URI']; ?>

<footer class="container mt-32 mb-10 flex flex-col items-center">
    <div class="text-center">
        <h3 class="">We are here to know all of your opinion</h3>
        <p class="text-gray_6 mt-2">Subscribe to our newsletter to stay updated with the latest features<br>
            and improvements in Ticketby.</p>
    </div>
    <div
        class="flex items-center bg-light bg-opacity-5 rounded-2xl border border-primary_color_o10_1 w-w-480 max-w-full	 mt-7 mb-14 ">
        <span class="p-16-16">
            <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M2.5 2.20508C2.14844 2.20508 1.875 2.51758 1.875 2.83008V3.72852L8.59375 9.23633C9.41406 9.90039 10.5469 9.90039 11.3672 9.23633L18.125 3.72852V2.83008C18.125 2.51758 17.8125 2.20508 17.5 2.20508H2.5ZM1.875 6.15039V12.8301C1.875 13.1816 2.14844 13.4551 2.5 13.4551H17.5C17.8125 13.4551 18.125 13.1816 18.125 12.8301V6.15039L12.5781 10.6816C11.0547 11.9316 8.90625 11.9316 7.42188 10.6816L1.875 6.15039ZM0 2.83008C0 1.46289 1.09375 0.330078 2.5 0.330078H17.5C18.8672 0.330078 20 1.46289 20 2.83008V12.8301C20 14.2363 18.8672 15.3301 17.5 15.3301H2.5C1.09375 15.3301 0 14.2363 0 12.8301V2.83008Z"
                    fill="#FBF9FD" fill-opacity="0.25" />
            </svg>
        </span>
        <form action="" class="flex items-center space-x-2  h-14 w-full">
            <input type="text" class="border bg-transparent border-transparent p-16-16 w-full  "
                placeholder="Your email address">
            <button type="submit" class="bg-primary_color_8 text-white h-full rounded-1-lr p-x3">Subscribe</button>
        </form>
    </div>
    <div>
        <div class="flex flex-row gap-8 mb-3">
            <a href="/">
                <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_18_258)">
                        <path
                            d="M12.6802 0.330078H12.6742C6.05775 0.330078 0.677246 5.71208 0.677246 12.3301C0.677246 14.9551 1.52325 17.3881 2.96175 19.3636L1.46625 23.8216L6.07875 22.3471C7.97625 23.6041 10.2397 24.3301 12.6802 24.3301C19.2967 24.3301 24.6772 18.9466 24.6772 12.3301C24.6772 5.71358 19.2967 0.330078 12.6802 0.330078Z"
                            fill="#FBF9FD" />
                        <path
                            d="M19.6628 17.2756C19.3733 18.0931 18.2243 18.7711 17.3078 18.9691C16.6808 19.1026 15.8618 19.2091 13.1048 18.0661C9.57832 16.6051 7.30732 13.0216 7.13032 12.7891C6.96082 12.5566 5.70532 10.8916 5.70532 9.1696C5.70532 7.4476 6.57982 6.6091 6.93232 6.2491C7.22182 5.9536 7.70032 5.8186 8.15932 5.8186C8.30782 5.8186 8.44132 5.8261 8.56132 5.8321C8.91382 5.8471 9.09082 5.8681 9.32332 6.4246C9.61282 7.1221 10.3178 8.8441 10.4018 9.0211C10.4873 9.1981 10.5728 9.4381 10.4528 9.6706C10.3403 9.9106 10.2413 10.0171 10.0643 10.2211C9.88732 10.4251 9.71932 10.5811 9.54232 10.8001C9.38032 10.9906 9.19732 11.1946 9.40132 11.5471C9.60532 11.8921 10.3103 13.0426 11.3483 13.9666C12.6878 15.1591 13.7738 15.5401 14.1623 15.7021C14.4518 15.8221 14.7968 15.7936 15.0083 15.5686C15.2768 15.2791 15.6083 14.7991 15.9458 14.3266C16.1858 13.9876 16.4888 13.9456 16.8068 14.0656C17.1308 14.1781 18.8453 15.0256 19.1978 15.2011C19.5503 15.3781 19.7828 15.4621 19.8683 15.6106C19.9523 15.7591 19.9523 16.4566 19.6628 17.2756Z"
                            fill="#121212" />
                    </g>
                    <defs>
                        <clipPath id="clip0_18_258">
                            <rect width="24" height="24" fill="white" transform="translate(0.677246 0.330078)" />
                        </clipPath>
                    </defs>
                </svg>
            </a>
            <a href=""><svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_18_249)">
                        <path
                            d="M14.7569 10.4924L23.4993 0.330078H21.4276L13.8366 9.15391L7.77365 0.330078H0.780762L9.94913 13.6733L0.780762 24.3301H2.85255L10.8689 15.0118L17.2718 24.3301H24.2647L14.7564 10.4924H14.7569ZM11.9193 13.7908L10.9903 12.4622L3.59905 1.88969H6.7812L12.7461 10.422L13.675 11.7507L21.4286 22.8414H18.2465L11.9193 13.7913V13.7908Z"
                            fill="#FBF9FD" />
                    </g>
                    <defs>
                        <clipPath id="clip0_18_249">
                            <rect width="24" height="24" fill="white" transform="translate(0.522705 0.330078)" />
                        </clipPath>
                    </defs>
                </svg>
            </a>
            <a href=""><svg width="27" height="27" viewBox="0 0 27 27" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19.5227 1.33008H7.52271C4.209 1.33008 1.52271 4.01637 1.52271 7.33008V19.3301C1.52271 22.6438 4.209 25.3301 7.52271 25.3301H19.5227C22.8364 25.3301 25.5227 22.6438 25.5227 19.3301V7.33008C25.5227 4.01637 22.8364 1.33008 19.5227 1.33008Z"
                        stroke="#FBF9FD" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M18.3223 12.5747C18.4704 13.5734 18.2998 14.5933 17.8348 15.4895C17.3698 16.3857 16.634 17.1124 15.7322 17.5663C14.8304 18.0202 13.8084 18.1782 12.8116 18.0178C11.8148 17.8574 10.894 17.3868 10.1801 16.6729C9.46616 15.959 8.99553 15.0381 8.83514 14.0413C8.67474 13.0445 8.83273 12.0226 9.28665 11.1207C9.74056 10.2189 10.4673 9.48317 11.3634 9.01817C12.2596 8.55317 13.2796 8.38259 14.2783 8.53068C15.297 8.68175 16.2401 9.15644 16.9683 9.88465C17.6965 10.6129 18.1712 11.556 18.3223 12.5747Z"
                        stroke="#FBF9FD" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M20.1228 6.7301H20.1348" stroke="#FBF9FD" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>
            <a href=""><svg width="35" height="25" viewBox="0 0 35 25" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M33.9621 4.08687C33.7777 3.35012 33.4022 2.67508 32.8734 2.12993C32.3446 1.58479 31.6813 1.18884 30.9505 0.982083C28.2804 0.330078 17.5999 0.330078 17.5999 0.330078C17.5999 0.330078 6.91945 0.330078 4.24934 1.04418C3.51854 1.25093 2.85525 1.64688 2.32646 2.19203C1.79767 2.73718 1.4221 3.41222 1.23769 4.14897C0.749023 6.85875 0.509987 9.60766 0.523592 12.3611C0.506174 15.1353 0.745224 17.9052 1.23769 20.6354C1.44099 21.3492 1.82497 21.9986 2.35254 22.5207C2.8801 23.0429 3.5334 23.4201 4.24934 23.616C6.91945 24.3301 17.5999 24.3301 17.5999 24.3301C17.5999 24.3301 28.2804 24.3301 30.9505 23.616C31.6813 23.4092 32.3446 23.0133 32.8734 22.4681C33.4022 21.923 33.7777 21.2479 33.9621 20.5112C34.447 17.8218 34.6861 15.0939 34.6762 12.3611C34.6937 9.58693 34.4546 6.81706 33.9621 4.08687Z"
                        fill="#FBF9FD" />
                    <path
                        d="M14.1079 16.7499C14.1079 17.0567 14.439 17.2493 14.7056 17.0976L22.4228 12.7089C22.6924 12.5556 22.6924 12.1669 22.4228 12.0135L14.7056 7.62484C14.439 7.4732 14.1079 7.66579 14.1079 7.97255V16.7499Z"
                        fill="#121212" />
                </svg>
            </a>
            <a href=""><svg width="14" height="25" viewBox="0 0 14 25" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M13.5228 0.330078H9.92275C8.33145 0.330078 6.80533 0.962219 5.68011 2.08744C4.55489 3.21266 3.92275 4.73878 3.92275 6.33008V9.93008H0.322754V14.7301H3.92275V24.3301H8.72275V14.7301H12.3228L13.5228 9.93008H8.72275V6.33008C8.72275 6.01182 8.84918 5.70659 9.07423 5.48155C9.29927 5.25651 9.60449 5.13008 9.92275 5.13008H13.5228V0.330078Z"
                        fill="#FBF9FD" />
                </svg>
            </a>
        </div>
        <p>© Ticketby 2024. All rights reserved.</p>
    </div>
</footer>