@php
    
    $language = \App\Models\Language::where('status', 1)->where('direction',"!=",session('direction'))->get();
    $showLinkBanner = \App\Models\Setting::find(1,['show_link_banner','googleplay_link','appstore_link']);
@endphp
<?php $url =  $_SERVER['REQUEST_URI']; ?>
<!-- new footer -->

<meta name="viewport" content="width=device-width" />
<style>
  
.footer a{
    text-decoration:none !important;
    min-width: fit-content;
    width: fit-content;
    width: -webkit-fit-content;
    width: -moz-fit-content;
}

.footer a, button{
    transition:0.5s;
}

.footer a, p{
    font-size:18px;
}

.footer h1, h2, h3, h4, h5, h6{
    color:var(--primary);
    font-weight:600;
}

.footer a, button, input, textarea, select{
    outline:none !important;
}

.footer fieldset{
    border:0;
}

.footer .title{
    color:var(--primary);
}

.footer .flex, .fixed_flex{
    display:flex;
}

.footer .flex-content{
    width:100%;
    position:relative;
}

.padding_1x{
    padding:1rem;
}

.footer .padding_2x{
    padding:2rem;
}

.footer .padding_3x{
    padding:3rem;
}

.footer .padding_4x{
    padding:4rem;
}

.footer .btn{
    padding:0.8rem 2rem;
    border-radius:5px;
    text-align:center;
    font-weight:500;
    text-transform:uppercase;
}

.footer .btn_1{
    border:1px solid var(--primary);
    background-color:var(--primary);
    color:var(--secondary);
}

.footer .btn_1:hover{
    background-color:transparent;
    color:var(--primary);
}

.footer .btn_2{
    border:1px solid var(--secondary);
    background-color:var(--secondary);
    color:var(--primary);
}

.footer .btn_2:hover{
    border:1px solid var(--primary);
    background-color:var(--primary);
    color:var(--secondary);
}

@media (max-width:920px){
    .footer .flex{
        flex-wrap:wrap;
    }
    
    .footer .padding_1x, .padding_2x, .padding_3x, .padding_4x{
        padding:1rem;
    }
  
    .footer  .btn{
        padding:0.5rem 1rem;
    }
    
    .footer a, p{
        font-size:12px;
    }
}



/***************************
               FOOTER
****************************/
footer{
    background-color:var(--primary_color);
    color:white;
	font-size: 20px;
}

footer h3{
    color:var(--white);
    margin-bottom:1.5rem;
	font-size: 24px;
}

footer a{
    color:white;
    display:block;
    margin:15px 0;
	font-size: 18px;
}

footer a:hover{
    color:var(--white);
}

footer fieldset{
    padding:0;
}

footer fieldset input{
    background-color:#334f6c;
    border:0;
    color:white;
    padding:1rem;
}

footer fieldset .btn{
    border-radius:0;
    border:0;
}

footer fieldset .btn_2:hover{
    background-color:var(--secondary);
    border:0;
    color:var(--primary);
}

footer .flex:last-child{
    align-items:center;
}

footer .flex:last-child .flex-content:last-child{
    text-align:right;
}

footer .flex:last-child p{
    color:var(--white);
}

footer .flex:last-child a{
    width:40px;
    display:inline-block;
    background-color:#334f6c;
    color:var(--white);
    padding:0.5rem;
    margin-right:3px;
    text-align:center;
}

footer .flex:last-child a:hover{
    background-color:var(--secondary);
    color:var(--gray);
}

@media (max-width:1100px){
    footer .flex:first-child{
        flex-wrap:wrap;
    }
    
    footer .flex:first-child .flex-content{
        flex: 1 1 40%;
    }
}
.align-footer-a
{
    display: flex;
    justify-content: center;
}

@media (max-width:920px){
    footer .flex:last-child .flex-content:last-child{
        text-align:left;
    }
}

@media (max-width:320px){
    footer .flex:first-child .flex-content{
        flex:1 1 100%;
    }
}
.mt-20
{
    margin-top: 20px;
}
.p-30
{
    padding:30px;
}
.footer .language-option .remove-a{
    width: auto !important;
  background: none !important;
  padding: 0px !important;
  margin: 0px !important;
}

.social-below-hr{
    text-align:center;
}
.hr-below{
    margin-top:45px;
}

    </style>
   <footer class="padding_4x footer">
  <div class="flex p-30">
    <section class="flex-content padding_1x hidden-in-some">
      <div class="language-parent language-parent-footer ">
    <i class="fa fa-globe rtl-ml-5"  aria-hidden="true" style="  margin-right: 6px; margin-top: 2px;  " ></i>
    @foreach ($language as $key => $item)
    <div class="language-option">
    <a type="button" class="remove-a" href="{{ url('change-language/' . $item->name) }}" >@if($item->name == "English") En @else Ar @endif </a>
    </div>
    @if($key < count($language) - 1 )
    <span style="padding-right:5px;padding-left:5px;"> / </span>
    @endif
    @endforeach
    </div>
    <!-- <h3>{{__('Follow Us On')}}</h3>
    <section class="flex-content  sm-flex">
        <i class="fa wp-icon fa-facebook-f fa-lg"></i>
        <i class="fa wp-icon fa-twitter fa-lg"></i>
        <i class="fa wp-icon fa-instagram fa-lg"></i>
      
    </section> -->
    </section>
    <section class="flex-content padding_1x">
      <h3>{{__('Home')}}</h3>
      <div style="text-align:center;">
      <a href="{{ url('/all-events') }}">{{ __('Events') }} </a>

      <a href="{{ url('/user/login') }}">{{ __('Organizer') }}</a>
      <a href="{{ url('/user/register') }}">{{ __('Register') }}</a>
      <a href="{{ url('/user/FAQs') }}">{{ __("FAQ's") }}</a>
    </div>
    </section>
    <section class="flex-content padding_1x">
      <h3>{{__('Help')}}</h3>
      <a href="{{ url('/privacy_policy') }}">{{ __('Privacy Policy') }}</a>
      <a href="#">{{__('Terms of Service')}}</a>
    </section>
    <section class="flex-content padding_1x ">
      <h3>{{__('Contact')}}</h3>
      <a href="https://maps.app.goo.gl/AwmDtDj41RTubGfq8?g_st=com.microsoft.skype.teams.extshare" target="_blank" class="mr-minus-15"><p> <i class="fas fa-map-marker-alt mr-3 rtl-ml-5 "></i> {{ __('Wasltech Company')}}</p></a>
      <a href="#" class="mr-minus-15"> <p><i class="fas fa-envelope mr-3 rtl-ml-5"></i> info@ticketby.co</p></a>
      @if (session('direction') == 'rtl')
      <a href="#" class="mr-minus-15 " ><p style="direction:ltr"> <span style="direction:ltr;"> +966 55 604 6094 <i class="fas fa-phone mr-3 rtl-ml-5"></i></span></p> </a>
      @else
      <a href="#" class="mr-minus-15"><p><i class="fas fa-phone mr-3 rtl-ml-5"></i> +966 55 604 6094</p></a>
      @endif
      
    </section>
    <section class="flex-content padding_1x">
      <h3>{{__('Downloads')}}</h3>
      <a  target="_blank" href="https://apps.apple.com/in/app/ticketby/id6503729969"><button
                     class="w-36  text-white font-poppins font-semibold text-lg rounded-[6px]"><img
                     src="{{ asset('images/AppStore.svg') }}" alt="" ></button></a>
                  <a target="_blank" href="https://play.google.com/store/apps/details?id=com.ticketby.app"><button
                     class="w-36  text-white font-poppins font-semibold text-lg rounded-[6px]"><img
                     src="{{ asset('images/GooglePlay.svg') }}" alt="" ></button></a>
		<div class="sm-hidden">
			@foreach ($language as $key => $item)
                <div class="language-option">
                <a type="button" href="{{ url('change-language/' . $item->name) }}" ><i class="fa fa-globe rtl-ml-5 rtl-mt-4"  aria-hidden="true" style="  margin-right: 6px; margin-top: 2px;  " ></i> @if($item->name == "English") En @else Ar @endif </a>
                </div>
                @if($key < count($language) - 1 )
                <span style="padding-right:5px;padding-left:5px;"> / </span>
                @endif
                @endforeach
		</div>
    </section>
    
 
  </div>
  <hr>
  <div class="hr-below">
     <div style="display:flex; justify-content: center;">
    <img  src="{{ url('/frontend/images/icon-white.png') }}" alt="" class="object-contain  social-below-hr" style="height:50px;margin-bottom: 20px ">
    </div>
    
    <div style="display:flex; justify-content: center;margin-bottom: 20px; text-align:center;">
    @if (session('direction') == 'rtl')
    <span style="font-size:13px; width:400px;">شركة سعودية مختصة في حجز وبيع وادارة تذاكر الفعاليات الترفيهية والرياضية والمعارض والمؤتمرات</span>
    @else
    <span style="font-size:13px; width:550px;">{{__('A Saudi company specialized in booking, selling and managing tickets for entertainment events, sports events, exhibitions and conferences.')}}</span>
    @endif
    </div>
    <br>
    <div class="social-below-hr align-footer-a">
        <a  href="https://www.facebook.com/profile.php?id=61561368001613&mibextid=LQQJ4d"><i class="fa wp-icon fa-facebook-f fa-lg"></i></a> 
        <a  href="https://x.com/ticketby_ksa?s=11&t=Y1QBBr7GqJUwZrLIvDTyMg"><i class="fa wp-icon fa-twitter fa-lg"></i></a> 
        <a  href="https://www.instagram.com/ticketbyksa?igsh=NDFueGwxb3A5Z2Zt"><i class="fa wp-icon fa-instagram fa-lg"></i></a>
    </div>
    <br>
    <div style="display:flex; justify-content: center; background-color:white;">
    <img  src="{{ url('frontend/images/payment-page-logo_same.png') }}" alt="" class="object-contain sm-mb-35  social-below-hr" style="height:40px">
    <div  class="footer-tag-line">
        <span>{{__('Copyright @ 2024 Wasl Technology Company. All Rights Reserved') }}
        </span>
    </div>
    </div>
  </div>
</footer>


<!-- old footer  -->
<!-- <div class="footer bg-primary py-3 bottom-0 m-0">
    <div
        class="flex xxsm:flex-wrap xsm:flex-wrap msm:flex-wrap 3xl:mx-52 2xl:mx-28 1xl:mx-28 xl:mx-36 xlg:mx-32 lg:mx-36 xxmd:mx-24 xmd:mx-32 sm:mx-20 msm:mx-16 xsm:mx-10 xxsm:mx-5 justify-between  md:mx-28 py-3 pt-4">
        <div class="flex justify-between items-center sm:items-left w-auto">
            <ul
                class="flex lg:flex-row xmd:flex-row md:flex-row md:text-xs md:-space-x-3 sm:flex-row msm:flex-row xsm:flex-col xxsm:flex-col msm:space-x-3 sm:space-x-2 lg:space-x-10 md:mt-0">
                <li class="mt-2">
                    <a href="{{ url('/') }}"
                        class="md:px-3 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Home') }} </a>
                </li>
                <li class="mt-2">
                    <a href="{{ url('/user/login') }}"
                        class="md:px-3 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Organizer') }}</a>
                </li>
                <li class="mt-2">
                    <a href="{{ url('/privacy_policy') }}"
                        class="md:px-3 capitalize font-poppins font-normal text-base leading-6 text-white flex">{{ __('Privacy Policy') }}

                    </a>
                </li>
                <li class="mt-2">
                    <a href="{{ url('/contact') }}"
                        class="md:px-3 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __('Contact') }}</a>
                </li>
                <li class="mt-2">
                    <a href="{{ url('/user/FAQs') }}"
                        class="md:px-3 capitalize font-poppins font-normal text-base leading-6 text-white">{{ __("FAQ's") }}</a>
                </li>
            </ul>
        </div>
        <div class="button-footer">
        <a href="{{$showLinkBanner->googleplay_link}}" target="_blank"><button
                     class="w-36 h-14 text-white font-poppins font-semibold text-lg rounded-[6px]"><img
                     src="{{ asset('images/AppStore.svg') }}" alt="" style="height:40px"></button></a>
                  <a href="{{$showLinkBanner->appstore_link}}" target="_blank"><button
                     class="w-36 h-14 text-white font-poppins font-semibold text-lg rounded-[6px]"><img
                     src="{{ asset('images/GooglePlay.svg') }}" alt="" style="height:40px"></button></a>
        </div>
        <div>
        <div class="language-parent language-parent-footer rtl-mr-25px">
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
            
                <i class="fa wp-icon fa-facebook-f fa-lg"></i>
                <i class="fa wp-icon fa-twitter fa-lg"></i>
                <i class="fa wp-icon fa-instagram fa-lg"></i>
            
        </div>

        
        <div class="sm:px-4 xmd:px-0 flex">
            <div class="relative inline-block text-left">
                @if (Session::has('local'))
                    {{ Session::get('local') }}
                @endif
                <a type="button" href="javascript:void(0);"
                    class="px-3 py-2 text-white bg-primary-dark text-center font-poppins font-normal text-base leading-6 rounded-md flex language">
                    {{ __('Language') }}<img src="{{ asset('images/downwhite.png') }}"
                        class="w-3 h-2 mx-2 mt-2 language" alt="">
                </a>

                <div
                    class="languageClass hidden rigin-top-left absolute left-0 w-44 rounded-md shadow-2xl z-10 bottom-12">
                    <div class="rounded-md bg-white shadow-lg p-3">
                        @foreach ($language as $item)
                            <div class="">
                                <div class="p-2 flex items-left justify-left">
                                    <a type="button" href="{{ url('change-language/' . $item->name) }}"
                                        class="hover:text-primary capitalize p-2 hover:bg-primary-light text-black w-full text-center font-poppins font-normal text-base leading-6 rounded-md flex language">
                                        <img src="{{ asset('images/upload/' . $item->image) }}"
                                            class="w-5 h-5 mx-2 language" alt="">{{ $item->name }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
