<!DOCTYPE html>
@php
    $lang = session('direction') == 'rtl' ? 'ar' : 'en';

@endphp


<html lang={{$lang}}>

<head>
    <!-- Google Tag Manager -->
    <script>
        (function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-5R6SN5SW');
    </script>
    <!-- End Google Tag Manager -->


    @php
        $favicon = \App\Models\Setting::find(1)->favicon;
    @endphp
    <meta charset="utf-8">
    <link href="{{ $favicon ? url('images/upload/' . $favicon) : asset('/images/logo.png') }}" rel="icon"
          type="image/png">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ \App\Models\Setting::find(1)->app_name }} | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <input type="hidden" name="base_url" id="base_url" value="{{ url('/') }}">
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('css/select2.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('css/custom.css') }}" rel="stylesheet"> -->

    <!-- frontAssets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" type="text/css"
          media="all"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
          integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link href="{{ asset('frontAssets/lib/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontAssets/css/output.css') }}" rel="stylesheet">
    <link href="{{ asset('frontAssets/css/custome.css') }}" rel="stylesheet">
    <!-- font -->
    <!-- inter font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
          rel="stylesheet">
    <!-- Bricolage Grotesque font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap"
          rel="stylesheet">
    <!-- alexandria font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&display=swap" rel="stylesheet">

    {!! JsonLdMulti::generate() !!}
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    {!! JsonLd::generate() !!}
    <!-- Favicons -->


    <!-- Vendor CSS Files -->
    <link href="{{ url('frontend/css/ionicons.min.css') }}" rel="stylesheet">
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" /> -->
    <!-- <link href="{{ url('frontend/css/animate.min.css') }}" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
          integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!-- <link href="{{ url('frontend/css/font-awesome.min.css') }}" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- <link href="{{ url('frontend/css/owl.carousel.min.css') }}" rel="stylesheet"> -->
    @stack("after-styles")
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
    <!-- Template Main CSS File -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
            integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @if (session('direction') == 'rtl')
        <!-- <link rel="stylesheet" href="{{ url('frontend/css/rtl.css') }}"> -->
    @endif

    <!--  -->

    <!-- Facebook Pixel Code -->
    <script>
        !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '516969950816868');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1"
                   src="https://www.facebook.com/tr?id=YOUR_PIXEL_ID&ev=PageView
  &noscript=1"/></noscript>
    <!-- End Facebook Pixel Code -->


    <!--  -->
</head>

<body class="{{ $lang == 'ar' ? 'rtl' : '' }}">
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5R6SN5SW"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="lds-ripple">
    <div></div>
    <div></div>
</div>

<div id="app">


    <input type="hidden" name="currency" id="currency" value="{{ $currency }}">
    <input type="hidden" name="default_lat" id="default_lat"
           value="{{ \App\Models\Setting::find(1)->default_lat }}">
    <input type="hidden" name="default_long" id="default_long"
           value="{{ \App\Models\Setting::find(1)->default_long }}">

    @include('front.layout.header')
    @yield('content')
    @include('front.auth')
    @include('front.layout.footer')

    @stack('before-scripts')

    <script>
        var _token = '{{ csrf_token() }}';
    </script>
    <!-- frontAssets -->
    <script src="{{ asset('frontAssets/lib/jq/jquery.min.js') }}"></script>
    <script src="{{ asset('frontAssets/lib/jq/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontAssets/lib/swiper/swiper-bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
            integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('frontAssets/js/script-custome.js') }}"></script>

    <script src="{{ asset('frontAssets/lib/jq/datepicker-ar.js') }}"></script>


    <!-- <script src="{{ asset('frontend/js/jquery.min.js') }}"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script> -->
    <script src="{{ asset('frontend/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('frontend/js/validate.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/l10n/ar.js"></script>
    <script src="{{ asset('translate/flatpicker-ar.js') }}"></script>
    <!-- <script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script> -->
    <!-- <script src="{{ asset('frontend/js/scrollreveal.min.js') }}"></script> -->
    <script src="{{ asset('frontend/js/map.js') }}"></script>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <?php $client_id = \App\Models\PaymentSetting::find(1)->paypalClientId;
    $cur = \App\Models\Setting::find(1)->currency;
    $map_key = \App\Models\Setting::find(1)->map_key;
    ?>
    @if ($client_id != null)
        <script src="https://www.paypal.com/sdk/js?client-id={{ $client_id }}&currency={{ $cur }}"
                data-namespace="paypal_sdk"></script>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://unpkg.com/flowbite@1.5.5/dist/flowbite.js"></script>
    <script src="{{ asset('frontend/js/qrcode.min.js') }}"></script>
    <script src="{{ asset('frontend/js/main.js') }}"></script>
    <script src="{{ asset('frontend/js/custom.js') }}"></script>
    <!-- <script src="{{ asset('js/custom.js') }}"></script> -->
    <script src="./TW-ELEMENTS-PATH/dist/js/index.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.4/datepicker.min.js"></script>
    <!-- <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script> -->
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script src="{{ asset("frontend/js/dev.js") }}"></script>
    @stack('after-scripts')
</div>
</body>

</html>