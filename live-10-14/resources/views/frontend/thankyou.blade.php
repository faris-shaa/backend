@extends('frontend.master', ['activePage' => 'checkout'])
@section('title', __('Thank You'))
@section('content')
{{-- content --}}

<div class="pb-20 bg-scroll min-h-screen" style="background:linear-gradient(to top, #eae9e9, white); align-content:center;">
<style>
 /*
#   $$$$$$\  $$\           $$\                 $$\
#  $$  __$$\ $$ |          $$ |                $$ |
#  $$ /  \__|$$ | $$$$$$\  $$$$$$$\   $$$$$$\  $$ | $$$$$$$\
#  $$ |$$$$\ $$ |$$  __$$\ $$  __$$\  \____$$\ $$ |$$  _____|
#  $$ |\_$$ |$$ |$$ /  $$ |$$ |  $$ | $$$$$$$ |$$ |\$$$$$$\
#  $$ |  $$ |$$ |$$ |  $$ |$$ |  $$ |$$  __$$ |$$ | \____$$\
#  \$$$$$$  |$$ |\$$$$$$  |$$$$$$$  |\$$$$$$$ |$$ |$$$$$$$  |
#   \______/ \__| \______/ \_______/  \_______|\__|\_______/
*/
@font-face {
    font-family: "Akkurat-Regular";
    src:url("../font/akkurat/lineto-akkurat-regular.eot");
    src:url("../font/akkurat/lineto-akkurat-regular.eot?#iefix") format("embedded-opentype"),
        url("../font/akkurat/lineto-akkurat-regular.woff") format("woff");
    font-weight: normal;
    font-style: normal;
}

.cf:before,
.cf:after {
    content: " ";
    display: table;
}
.cf:after {
    clear: both;
}

* {
	box-sizing: border-box;
}




/*
#   $$$$$$\  $$\   $$\                     $$\   $$\                           $$\
#  $$  __$$\ \__|  $$ |                    $$ |  $$ |                          $$ |
#  $$ /  \__|$$\ $$$$$$\    $$$$$$\        $$ |  $$ | $$$$$$\   $$$$$$\   $$$$$$$ | $$$$$$\   $$$$$$\
#  \$$$$$$\  $$ |\_$$  _|  $$  __$$\       $$$$$$$$ |$$  __$$\  \____$$\ $$  __$$ |$$  __$$\ $$  __$$\
#   \____$$\ $$ |  $$ |    $$$$$$$$ |      $$  __$$ |$$$$$$$$ | $$$$$$$ |$$ /  $$ |$$$$$$$$ |$$ |  \__|
#  $$\   $$ |$$ |  $$ |$$\ $$   ____|      $$ |  $$ |$$   ____|$$  __$$ |$$ |  $$ |$$   ____|$$ |
#  \$$$$$$  |$$ |  \$$$$  |\$$$$$$$\       $$ |  $$ |\$$$$$$$\ \$$$$$$$ |\$$$$$$$ |\$$$$$$$\ $$ |
#   \______/ \__|   \____/  \_______|      \__|  \__| \_______| \_______| \_______| \_______|\__|
*/
.site-header {
	margin: 0 auto;
	
	max-width: 820px;
    text-align:center;
}
.site-header__title {
	margin: 0;
	font-family: Montserrat, sans-serif;
	font-size: 2.5rem;
	font-weight: 700;
	line-height: 1.1;
	text-transform: uppercase;
	-webkit-hyphens: auto;
	-moz-hyphens: auto;
	-ms-hyphens: auto;
	hyphens: auto;
}

/*
#  $$\      $$\           $$\                  $$$$$$\                       $$\                          $$\
#  $$$\    $$$ |          \__|                $$  __$$\                      $$ |                         $$ |
#  $$$$\  $$$$ | $$$$$$\  $$\ $$$$$$$\        $$ /  \__| $$$$$$\  $$$$$$$\ $$$$$$\    $$$$$$\  $$$$$$$\ $$$$$$\
#  $$\$$\$$ $$ | \____$$\ $$ |$$  __$$\       $$ |      $$  __$$\ $$  __$$\\_$$  _|  $$  __$$\ $$  __$$\\_$$  _|
#  $$ \$$$  $$ | $$$$$$$ |$$ |$$ |  $$ |      $$ |      $$ /  $$ |$$ |  $$ | $$ |    $$$$$$$$ |$$ |  $$ | $$ |
#  $$ |\$  /$$ |$$  __$$ |$$ |$$ |  $$ |      $$ |  $$\ $$ |  $$ |$$ |  $$ | $$ |$$\ $$   ____|$$ |  $$ | $$ |$$\
#  $$ | \_/ $$ |\$$$$$$$ |$$ |$$ |  $$ |      \$$$$$$  |\$$$$$$  |$$ |  $$ | \$$$$  |\$$$$$$$\ $$ |  $$ | \$$$$  |
#  \__|     \__| \_______|\__|\__|  \__|       \______/  \______/ \__|  \__|  \____/  \_______|\__|  \__|  \____/
*/
.main-content {
	margin: 0 auto;
	max-width: 820px;
}
.main-content__checkmark {
	font-size: 4.0625rem;
	line-height: 1;
	color: #24b663;
}
.main-content__body {
	margin: 20px 0 0;
	font-size: 1rem;
	line-height: 1.4;
}


/*
#   $$$$$$\  $$\   $$\                     $$$$$$$$\                   $$\
#  $$  __$$\ \__|  $$ |                    $$  _____|                  $$ |
#  $$ /  \__|$$\ $$$$$$\    $$$$$$\        $$ |    $$$$$$\   $$$$$$\ $$$$$$\    $$$$$$\   $$$$$$\
#  \$$$$$$\  $$ |\_$$  _|  $$  __$$\       $$$$$\ $$  __$$\ $$  __$$\\_$$  _|  $$  __$$\ $$  __$$\
#   \____$$\ $$ |  $$ |    $$$$$$$$ |      $$  __|$$ /  $$ |$$ /  $$ | $$ |    $$$$$$$$ |$$ |  \__|
#  $$\   $$ |$$ |  $$ |$$\ $$   ____|      $$ |   $$ |  $$ |$$ |  $$ | $$ |$$\ $$   ____|$$ |
#  \$$$$$$  |$$ |  \$$$$  |\$$$$$$$\       $$ |   \$$$$$$  |\$$$$$$  | \$$$$  |\$$$$$$$\ $$ |
#   \______/ \__|   \____/  \_______|      \__|    \______/  \______/   \____/  \_______|\__|
*/
.site-footer {
	margin: 0 auto;
	padding: 80px 0 25px;
	padding: 0;
	max-width: 820px;
}
.site-footer__fineprint {
	font-size: 0.9375rem;
	line-height: 1.3;
	font-weight: 300;
}
.main-content
{
    text-align:center;
}


/*
#  $$\      $$\                 $$\ $$\                  $$$$$$\                                $$\
#  $$$\    $$$ |                $$ |\__|                $$  __$$\                               \__|
#  $$$$\  $$$$ | $$$$$$\   $$$$$$$ |$$\  $$$$$$\        $$ /  $$ |$$\   $$\  $$$$$$\   $$$$$$\  $$\  $$$$$$\   $$$$$$$\
#  $$\$$\$$ $$ |$$  __$$\ $$  __$$ |$$ | \____$$\       $$ |  $$ |$$ |  $$ |$$  __$$\ $$  __$$\ $$ |$$  __$$\ $$  _____|
#  $$ \$$$  $$ |$$$$$$$$ |$$ /  $$ |$$ | $$$$$$$ |      $$ |  $$ |$$ |  $$ |$$$$$$$$ |$$ |  \__|$$ |$$$$$$$$ |\$$$$$$\
#  $$ |\$  /$$ |$$   ____|$$ |  $$ |$$ |$$  __$$ |      $$ $$\$$ |$$ |  $$ |$$   ____|$$ |      $$ |$$   ____| \____$$\
#  $$ | \_/ $$ |\$$$$$$$\ \$$$$$$$ |$$ |\$$$$$$$ |      \$$$$$$ / \$$$$$$  |\$$$$$$$\ $$ |      $$ |\$$$$$$$\ $$$$$$$  |
#  \__|     \__| \_______| \_______|\__| \_______|       \___$$$\  \______/  \_______|\__|      \__| \_______|\_______/
#                                                            \___|
*/
@media only screen and (min-width: 40em) {
	
	.site-header__title {
		font-size: 6.25rem;
	}
	.main-content__checkmark {
		font-size: 9.75rem;
	}
	.main-content__body {
		font-size: 1.25rem;
	}
	.site-footer {
		padding: 145px 0 25px;
	}
	.site-footer__fineprint {
		font-size: 1.125rem;
	}
}
    </style>
<header class="site-header thankyou" id="header">
		<h1 class="site-header__title text-primary" data-lead-id="site-header-title">{{__('THANK YOU!')}}</h1>
	</header>

	<div class="main-content">
		<i class="fa fa-check main-content__checkmark" id="checkmark"></i>
		<p class="main-content__body text-primary" data-lead-id="main-content-body">{{__('Thank you , your order has been placed and you will soon be redirected your tickets page.')}}</p>
	</div>
<!-- <div style="margin-left:40%">
<img src="{{ url('frontend/images/thankyou.png') }}" alt="" class="object-contain "  >
                                 <h1 style="font-size: 20px; margin-top: 30px;"> Thank you for your order ! </h1>
                                 </div> -->
                                </div>

                                 <script type="text/javascript">   
    function Redirect() 
    {  
        window.location="/my-tickets"; 
    } 
    //document.write("You will be redirected to a new page in 5 seconds"); 
    setTimeout('Redirect()',4500);   
</script>
@endsection