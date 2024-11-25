@extends('front.master', ['activePage' => 'checkout'])
@section('title', __('Thank You'))
@section('content')
{{-- content --}}

<div class="">
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
			src: url("../font/akkurat/lineto-akkurat-regular.eot");
			src: url("../font/akkurat/lineto-akkurat-regular.eot?#iefix") format("embedded-opentype"),
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
			text-align: center;
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

		.main-content {
			text-align: center;
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
	<div class="container mt-28 md:mt-40 xl:mt-48 text-center">
		<div class="flex justify-center mb-4">
			<svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path
					d="M29.375 41.5C28.625 42.25 27.25 42.25 26.5 41.5L18.5 33.5C17.75 32.75 17.75 31.375 18.5 30.625C19.25 29.875 20.625 29.875 21.375 30.625L28 37.25L42.5 22.625C43.25 21.875 44.625 21.875 45.375 22.625C46.125 23.375 46.125 24.75 45.375 25.5L29.375 41.5ZM64 32C64 49.75 49.625 64 32 64C14.25 64 0 49.75 0 32C0 14.375 14.25 0 32 0C49.625 0 64 14.375 64 32ZM32 4C16.5 4 4 16.625 4 32C4 47.5 16.5 60 32 60C47.375 60 60 47.5 60 32C60 16.625 47.375 4 32 4Z"
					fill="#01E6A0" />
			</svg>
		</div>
		<header class=" thankyou" id="header">
			<h1 class="" data-lead-id="site-header-title ">{{__('THANK YOU!')}}</h1>
		</header>
		<div class="main-content text-center mt-2">
			<i class="" id="checkmark"></i>
			<p class="text-h2 leading-relaxed text-primary_color_5" data-lead-id="main-content-body">{{__('Thank you ,
                your order has
                been placed and you will soon be redirected your tickets page.')}}</p>
		</div>
	</div>
</div>

<script type="text/javascript">
	function Redirect() {
		window.location = "/user/profile2";
	}
	//document.write("You will be redirected to a new page in 5 seconds"); 
	setTimeout('Redirect()', 4500);
</script>
@endsection