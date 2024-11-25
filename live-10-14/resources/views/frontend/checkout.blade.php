@extends('frontend.master', ['activePage' => 'checkout'])
@section('title', __('Checkout'))
@section('content')
{{-- content --}}
<link rel="stylesheet" href="{{asset('frontend/css/payment-card.css')}}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script async crossorigin
        src="https://applepay.cdn-apple.com/jsapi/1.latest/apple-pay-sdk.js"
        ></script><style>
apple-pay-button {
  --apple-pay-button-width: 150px;
  --apple-pay-button-height: 30px;
  --apple-pay-button-border-radius: 3px;
  --apple-pay-button-padding: 0px 0px;
  --apple-pay-button-box-sizing: border-box;
}
.apple-pay-button {
    display: inline-block;
    -webkit-appearance: -apple-pay-button;
    appearance: -apple-pay-button;
    apple-pay-button-type: buy;
    apple-pay-button-style: black;
    height: 44px;
    width: 100%;
}
.apple-pay-button-div
{
   margin-left:0px;
}
.d-block{
   display: block;
}

.hide
{
   display: none;
}
</style>
<div class="pb-20 bg-scroll min-h-screen" style="background:linear-gradient(to top, #eae9e9, white)">
   {{-- scroll --}}
   <div id="stripe_message" class="bg-danger text-white text-center p-2 hidden"></div>
   <div class="mr-4 flex justify-end z-30">
      <a type="button" href="{{ url('#') }}"
         class="scroll-up-button bg-primary rounded-full p-4 fixed z-20  2xl:mt-[49%] xl:mt-[59%] xlg:mt-[68%] lg:mt-[75%] xxmd:mt-[83%] md:mt-[90%]
         xmd:mt-[90%] sm:mt-[117%] msm:mt-[125%] xsm:mt-[160%]">
      <img src="{{ asset('images/downarrow.png') }}" alt="" class="w-3 h-3 z-20">
      </a>
   </div>
   <input type="hidden" name="totalAmountTax" id="totalAmountTax" value="{{ $data->totalAmountTax }}">
   <input type="hidden" name="totalPersTax" id="totalPersTax" value="{{ $data->totalPersTax }}">
   <input type="hidden" name="flutterwave_key" value="{{ \App\Models\PaymentSetting::find(1)->ravePublicKey }}">
   @if(isset(auth()->guard('appuser')->user()->email))
   <input type="hidden" name="email" id="email" value="{{ auth()->guard('appuser')->user()->email }}">
   <input type="hidden" name="phone" id="phone" value="{{ auth()->guard('appuser')->user()->phone }}">
   <input type="hidden" name="name" id="name" value="{{ auth()->guard('appuser')->user()->name }}">
   <input type="hidden" name="is_login" value="1" id="is-login">
   @else
   <input type="hidden" name="is_login" value="0" id="is-login">
   @endif
   <input type="hidden" name="flutterwave_key" value="{{ \App\Models\PaymentSetting::find(1)->ravePublicKey }}">
   <div id="ticketorder">
      @csrf
      <input type="hidden" id="razor_key" name="razor_key"
         value="{{ \App\Models\PaymentSetting::find(1)->razorPublishKey }}">
      <input type="hidden" id="stripePublicKey" name="stripePublicKey"
         value="{{ \App\Models\PaymentSetting::find(1)->stripePublicKey }}">
      <input type="hidden" value="{{ $data->ticket_per_order }}" name="tpo" id="tpo">
      <input type="hidden" value="{{ $data->available_qty }}" name="available" id="available">
      <input type="hidden" name="price" id="ticket_price" value="{{ $data->price }}">
      <input type="hidden" name="tax" id="tax_total" value="{{ $data->type == 'free' ? 0 :  request('quantity')  * $data->tax_total }}">
      <input type="hidden" name="tax_total_price" id="tax_total_price" value="{{ $data->type == 'free' ? 0 :  request('quantity')  * $data->tax_total }}">

      <input type="hidden" name="payment" id="payment"
         value="{{ $data->type == 'free' ? 0 : (request('quantity')  * $data->price) + request('quantity')  * $data->tax_total }}">

      <input type="hidden" name="ticket_total" id="ticket_total"
         value="{{ $data->type == 'free' ? 0 : (request('quantity')  * $data->price) }}">
      @php
      $price = (request('quantity')  * $data->price) + ( request('quantity')  * $data->tax_total);
      if ($data->currency_code == 'USD' || $data->currency_code == 'EUR' || $data->currency_code == 'INR') {
      $price = $price * 100;
      }
      @endphp
      <input type="hidden" name="stripe_payment" id="stripe_payment"
         value="{{ $data->type == 'free' ? 0 : $price }}">
      <input type="hidden" name="currency_code" id="currency_code" value="{{ $data->currency_code }}">
      <input type="hidden" name="currency" id="currency" value="{{ __($currency) }}">
      <input type="hidden" name="payment_token" id="payment_token">
      <input type="hidden" name="ticket_id" id="ticket_id" value="{{ $data->id }}">
      <input type="hidden" name="selectedSeats" id="selectedSeats">
      <input type="hidden" name="selectedSeatsId[]" id="selectedSeatsId">
      <input type="hidden" name="coupon_id" id="coupon_id" value="">
      <input type="hidden" name="coupon_discount" id="coupon_discount" value="0">
      <input type="hidden" name="subtotal" id="subtotal" value="">
      <input type="hidden" name="ticket-price-amount" id="ticket-price-amount" value="">
      <input type="hidden" name="add_ticket" value="">
      <input type="hidden" name="event_id" value="{{ $data->id }}" id="event-id">
      <input type="hidden" name="event_quantity" value="{{  request('quantity') }}" id="event-quantity">
      <input type="hidden" name="event_time_id" value="{{  request('event_time_id') }}" id="event-time-id">
      <input type="hidden" name="event_date" value="{{  request('event_date') }}" id="event-date">
      <input type="hidden" name="ticketname" id="ticketname" value="{{ $data->name }}">
      <div
         class="mt-10 3xl:mx-52 2xl:mx-28 1xl:mx-28 xl:mx-36 xlg:mx-32 lg:mx-36 xxmd:mx-24 xmd:mx-32 md:mx-28 sm:mx-20 msm:mx-16 xsm:mx-10 xxsm:mx-5 z-10 relative">
         <div
            class="flex sm:space-x-6 msm:space-x-0 xxsm:space-x-0 xlg:flex-row lg:flex-col xmd:flex-col xxsm:flex-col">
            <div class="xlg:w-[75%] xxmd:w-full xxsm:w-full">
               <div
                  class="flex 3xl:flex-row 2xl:flex-nowrap 1xl:flex-nowrap xl:flex-nowrap xlg:flex-wrap flex-wrap justify-between 3xl:pt-5 xl:pt-5 gap-x-5 xl:w-full xlg:w-full">
                  <div class="sm-width-100 lg-width-60" style="width: 100%;" >
                     <!-- <div
                        class="w-full shadow-lg p-5 rounded-lg flex 3xl:flex-nowrap md:flex-wrap xxmd:flex-nowrap sm:flex-wrap msm:flex-wrap xsm:flex-wrap xxsm:flex-wrap bg-white xlg:w-full xmd:w-full 3xl:mb-0 xl:mb-0 xlg:mb-5 xxsm:mb-5">
                        <img src="{{ asset('images/upload/' . $data->event->image) }}" alt=""
                           class="rounded-lg w-56 h-56 sm-full-width">
                        <div class="ml-4 2xl:w-[60%] xl:w-[80%] xlg:w-[80%] xmd:w-full xxmd:w-[80%] sm-full-width sm-p10 sm-ml-0" >
                           <p class="font-poppins font-bold text-4xl leading-8 text-left pt-3 text-black-100 width-300px sm-width-100p rtl-text-right rtl-mr-20px sm-rtl-mr-0" >
                              {{ $data->event->name }}
                           </p>
                           <p class="font-poppins font-normal text-sm text-gray-200 leading-8 text-left pt-2 rtl-text-right rtl-mr-20px sm-rtl-mr-0">
                              {{ __('Date') }}
                           </p>
                           <p class="font-poppins font-medium text-base leading-7 text-gray text-left rtl-text-right rtl-mr-20px sm-rtl-mr-0">
                           @if (session('direction') == 'rtl')
                           {{ Carbon\Carbon::parse($data->event->start_time)->locale('ar')->translatedFormat('d M Y') }} -
                           {{ Carbon\Carbon::parse($data->event->end_time)->locale('ar')->translatedFormat('d M Y') }}
                           @else
                           {{ Carbon\Carbon::parse($data->event->start_time)->format('d M Y') }} -
                              {{ Carbon\Carbon::parse($data->event->end_time)->format('d M Y') }}
                           @endif
                           </p>
                           @if($data->event->address )
                           <p class="font-poppins font-normal text-sm text-gray-200 leading-8 text-left pt-2 rtl-text-right rtl-mr-20px sm-rtl-mr-0" >
                              {{ __('Location') }}
                           </p>
                           <p
                              class="font-poppins font-medium text-base leading-7 text-gray text-left w-[50%] rtl-text-right rtl-mr-20px sm-rtl-mr-0">
                              {{ $data->event->address }}
                           </p>
                           @endif
                           @if ($data->allday == 0)
                           <div>
                              <input type="date" name="ticket_date" id="onetime"
                                 data-date="{{ $data->event->end_time }}" placeholder="mm/dd/yy"
                                 class="mt-3 border p-2 border-gray-light">
                              @if ($errors->has('ticket_date'))
                              <div class="text-danger">{{ $errors->first('ticket_date') }}</div>
                              @endif
                              <div class="ticket_date text-danger"></div>
                           </div>
                           @endif
                        </div>  <div class="md:w-[25%] sm:w-full  ">
                           @if ($data->type == 'paid')
                           <p class="font-poppins font-medium text-sm leading-7 text-danger text-left rtl-text-right rtl-sm-text-right">
                              {{ __('Paid') }}
                           </p>
                           @else
                           <p class="font-poppins font-medium text-sm leading-7 text-success text-left rtl-text-right rtl-sm-text-right">
                              {{ __('Free') }}
                           </p>
                           @endif
                           @if ($data->type == 'paid')
                           <p
                              class="font-poppins font-semibold text-3xl leading-7 text-primary text-left rtl-text-right pt-5 rtl-sm-text-right">
                              
                              @if (session('direction') == 'rtl')
                              {{  $data->price ." ".  __($data->currency)  }}
                              @else
                              {{  $data->price ." ".  __($data->currency)  }}
                              @endif
                           </p>
                           @endif
                           @if ($data->seatmap_id == null || $data->module->is_enable == 0)
                           <p
                              class="font-poppins font-medium text-base leading-7 text-black text-left pt-10 rtl-text-right rtl-sm-text-right ">
                              {{ __('Quantity') }}
                           </p>
                           <div
                              class="flex flex-row h-10 w-full rounded-lg relative bg-transparent mt-1 pro-qty">
                              <button data-mdb-ripple="true" id="dec-{{ $data->id }}"
                                 data-mdb-ripple-color="light" data-action="decrement" type="button"
                                 class="border-l dec qtybtn  border-t border-b border-primary bg-primary-light text-primary hover:text-black-700 h-8 w-9 cursor-pointer">
                              <span class="m-auto text-2xl font-thin">−</span>
                              </button>
                              <div class="text-center">
                                 <input type="number" id="quantity" readonly name="quantity"
                                   value="{{ request('quantity') }}"
                                    class="bg-primary-light outline-none focus:outline-none text-center w-8 font-semibold text-md
                                    hover:text-black focus:text-black md:text-basecursor-default flex items-center text-primary h-8"
                                    name="custom-input-number" >
                              </div>
                              <button data-mdb-ripple="true" data-mdb-ripple-color="light"
                                 data-action="increment" id="inc-{{ $data->id }}" type="button"
                                 class="border-r inc qtybtn border-t border-b border-primary bg-primary-light text-primary hover:text-black-700 h-8 w-9 cursor-pointer">
                              <span class="m-auto text-2xl font-thin">+</span>
                              </button>
                           </div>
                           <div class="font-poppins font-medium text-base leading-7 text-danger" id="quantityMsg"></div>
                           @endif
                        </div>

                      
                     </div> -->
                     @if ($data->seatmap_id != null && $data->module->is_install == 1 && $data->module->is_enable == 1)
                     @include('seatmap::seatmapView', [
                     'seat_map' => $data->seat_map,
                     'rows' => $data->rows,
                     'seatsByRow' => $data->seatsByRow,
                     'seatLimit' => $data->ticket_per_order,
                     ])
                     @endif
                     @if ($data->available_qty > 0)
                     <div
                        class="w-full shadow-lg p-5 rounded-lg  bg-white xlg:w-full xmd:w-full 3xl:mb-0 xl:mb-0 xlg:mb-5 xxsm:mb-5 mt-5">
                        <p class="font-poppins font-semibold text-2xl leading-8 text-black pb-3 pt-10 sm-padding-top-0">
                           {{ __('Payment Methods') }}
                        <!-- md:flex-row -->
                         <p id="login-error-payment" class="text-danger hide mb-3"> {{__('Please register to continue')}}</p>
                        <div
                           class="flex md:space-x-5  md:space-y-0 sm:flex-col sm:space-x-0 sm:space-y-5 xxsm:flex-col xxsm:space-x-0 xxsm:space-y-5 mb-5 payments">

                           <div
                              class="border border-gray-light p-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex border-primary d-block">
                              <div class="d-f">
                              <input id="Edafpay" type="radio" value="EDAFPAY"
                                 name="payment_type"
                                 class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none mt-15" selected="selected">
                              
                              <label for="Edafpay"><img
                                 src="{{ url('frontend/images/payment-page-logo_same.png') }}"
                                 alt="" class="object-contain " style="height:50px; margin-left: 5px;"></label>
                                 </div>
                                                         
                              <!-- <div class="d-f">
                                  <input id="Tabby" type="radio" value="TAMARA"
                                 name="payment_type"
                                 class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none mt-15" selected="selected">
                              <label for="Tamara"><img
                                 src="{{ url('frontend/images/tamara.jpg') }}"
                                 alt="" class="object-contain " style="height:30px; margin-top: 10px; margin-left: 5px;"></label>
                              </div>              -->                    
                           </div>

                        </div>
                        <div
                           class="flex md:space-x-5  md:space-y-0 sm:flex-col sm:space-x-0 sm:space-y-5 xxsm:flex-col xxsm:space-x-0 xxsm:space-y-5 mb-5 payments">

                            <div
                              class="border border-gray-light p-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex border-primary d-block">
                            
                              <div class="d-f">
                                  <input id="Tabby" type="radio" value="TABBY"
                                 name="payment_type"
                                 class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none mt-15" selected="selected">
                              <label for="Tabby"><img
                                 src="{{ url('frontend/images/tabby.png') }}"
                                 alt="" class="object-contain " style="height:50px"></label>
                              </div>                             
                              <!-- <div class="d-f">
                                  <input id="Tabby" type="radio" value="TAMARA"
                                 name="payment_type"
                                 class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none mt-15" selected="selected">
                              <label for="Tamara"><img
                                 src="{{ url('frontend/images/tamara.jpg') }}"
                                 alt="" class="object-contain " style="height:30px; margin-top: 10px; margin-left: 5px;"></label>
                              </div>              -->                    
                           </div> 
                           <?php $setting = App\Models\PaymentSetting::find(1); ?>
                           @if ($data->type == 'free')
                           <div
                              class="border border-gray-light  p-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex">
                              {{ __('FREE') }}
                              <input id="default-radio-1" required type="radio" value="FREE"
                                 name="payment_type"
                                 class="ml-2 h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none">
                           </div>
                           @else
                           @if ($setting->paypal == 1)
                           <div
                              class="border border-gray-light  p-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex align-middle">
                              <input id="Paypal" required type="radio" value="PAYPAL"
                                 name="payment_type"
                                 class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none">
                              <label for="Paypal"><img
                                 src="{{ asset('images/payments/paypal.svg') }}"
                                 alt="" class="object-contain"></label>
                           </div>
                           @endif
                           @if (false)
                           <div
                              class="border border-gray-light p-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex">
                              <input id="Razor" required type="radio" value="RAZOR"
                                 name="payment_type"
                                 class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none">
                              <label for="Razor"><img
                                 src="{{ asset('images/payments/razorpay.svg') }}"
                                 alt="" class="object-contain"></label>
                           </div>
                           @endif
                           @if ($setting->stripe == 1)
                           <div
                              class="border border-gray-light p-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex">
                              <input id="Stripe" required type="radio" value="STRIPE"
                                 name="payment_type"
                                 class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none">
                              <label for="Stripe"><img
                                 src="{{ url('images/payments/stripe.svg') }}"
                                 alt="" class="object-contain"></label>
                           </div>
                           @endif
                           @if ($setting->flutterwave == 1)
                           <div
                              class="border border-gray-light p-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex">
                              <input id="Flutterwave" required type="radio"
                                 value="FLUTTERWAVE" name="payment_type"
                                 class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none">
                              <label for="Flutterwave"><img
                                 src="{{ url('images/payments/flutterwave.svg') }}"
                                 alt="" class="object-contain"></label>
                           </div>
                           @endif
                           @if (
                           $setting->cod == 1 ||
                           ($setting->flutterwave == 0 && $setting->stripe == 0 && $setting->paypal == 0 && $setting->razor == 0))
                           <!-- <div
                              class="border border-gray-light p-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex">
                              <input id="Cash" type="radio" value="LOCAL"
                                 name="payment_type"
                                 class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none">
                              <label for="Cash"><img
                                 src="{{ url('images/payments/cash.svg') }}"
                                 alt="" class="object-contain"></label>
                           </div> -->
                           @endif
                           @if ($setting->wallet == 1)
                           <div
                              class="border border-gray-light p-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex  border-primary">
                              <input id="wallet" type="radio" value="wallet"
                                 name="payment_type"
                                 class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none">
                              <label for="wallet"><img
                                 src="{{ url('images/payments/wallet.svg') }}"
                                 alt="" class="object-contain"></label>
                           </div>
                           @endif
                           @endif
                           <!-- <div
                              class="border border-gray-light p-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex">
                              <input id="Cash" type="radio" value="LOCAL"
                                 name="payment_type"
                                 class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none">
                              <label for="Cash"><img
                                 src="{{ url('images/payments/cash.svg') }}"
                                 alt="" class="object-contain"></label>
                           </div> -->
                          
                           <!-- <div
                              class="border border-gray-light p-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex border-primary">
                              <input id="Tabby" type="radio" value="TABBY"
                                 name="payment_type"
                                 class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none mt-15" selected="selected">
                              <label for="Tabby"><img
                                 src="{{ url('frontend/images/payment-page-logo_same.png') }}"
                                 alt="" class="object-contain " style="height:50px"></label>
                           </div> -->
                           <br>
                           <div class="apple-pay-button-div">
                           <div class="apple-pay-button" id="applePayButton"></div>
                           <div id="apple-pay-button"></div>
                           </div>
                           <!-- <div
                              class="border border-gray-light p-5 rounded-lg text-gray-100 w-full font-normal font-poppins text-base leading-6 flex">
                              <input id="Card" type="radio" value="CARD"
                                 name="payment_type"
                                 class="h-5 w-5 mr-2 border border-gray-light  hover:border-gray-light focus:outline-none">
                                 <label for="Edafpay"><img
                                 src="{{ url('images/payments/edafpay.jpg') }}"
                                 alt="" class="object-contain" style="height:50px"></label>
                           </div> -->
                        </div>
                        
                        <div class="paypal-button-section  mt-4 mx-auto">
                           <div id="paypal-button-container" class="hidden">
                           </div>
                        </div>
                        <!-- Tabby  -->
                        <div class="tabby  hidden">
                           <div id="tabbyCard"></div>
                           <?php
                           $language ="en";
                           if(session('direction') == 'rtl') 
                           {
                              $language ="ar";
                           }  
                           ?>
                           <script src="https://checkout.tabby.ai/tabby-card.js"></script>
                           <script>
                              function initializeTabbyCard() {

                             var payment = $('#payment').val();

                             // Remove any existing instance if needed
                             // if (window.tabbyCardInstance) {
                             //   window.tabbyCardInstance.destroy(); // Ensure you clean up the previous instance
                             // }

                             // Initialize the new TabbyCard instance
                             window.tabbyCardInstance = new TabbyCard({
                               selector: '#tabbyCard',
                               currency: 'SAR',
                               lang: '{{$language}}',
                               price: payment,
                               size: 'narrow',
                               theme: 'black',
                               header: false
                             });
                           }
                             initializeTabbyCard();
                           //    var payment  =  $('#payment').val();

                           // new TabbyCard({
                           //   selector: '#tabbyCard', // empty div for TabbyCard.
                           //   currency: 'SAR', // required, currency of your product. AED|SAR|KWD|BHD|QAR only supported, with no spaces or lowercase.
                           //   lang: '{{$language}}', // Optional, language of snippet and popups.
                           //   price: payment, // required, total price or the cart. 2 decimals max for AED|SAR|QAR and 3 decimals max for KWD|BHD.
                           //   size: 'narrow', // required, can be also 'wide', depending on the width.
                           //   theme: 'black', // required, can be also 'default'.
                           //   header: false // if a Payment method name present already. 
                           // });
                           </script>
                           <button type="button" data-tabby-info="installments" data-tabby-price="{{ request('quantity')  *($data->price + $data->tax_total)}}" data-tabby-currency="SAR" id="tabby-button">Click here to know more</button>
                               <script src="https://checkout.tabby.ai/tabby-promo.js"></script>
                               <script>
                                 new TabbyPromo({});
                               </script>
                        </div>
                        <!-- Tabby  -->
                        <!-- edafpay  -->
                        <div class="edafpay card-pay hidden">
                           <div class='center'>
                              <div class='card'>
                                 <div class='front' >
                                    <div class='top'>
                                       <!-- <div class='chip'></div> -->
                                       <div class='cardType'>
                                          <svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" version="1.1" id="svg10306" viewBox="0 0 500.00001 162.81594" height="162.81593" width="500">
                                             <defs id="defs10308">
                                                <clipPath id="clipPath10271" clipPathUnits="userSpaceOnUse">
                                                   <path id="path10273" d="m 413.742,90.435 c -0.057,-4.494 4.005,-7.002 7.065,-8.493 3.144,-1.53 4.2,-2.511 4.188,-3.879 -0.024,-2.094 -2.508,-3.018 -4.833,-3.054 -4.056,-0.063 -6.414,1.095 -8.289,1.971 l -1.461,-6.837 c 1.881,-0.867 5.364,-1.623 8.976,-1.656 8.478,0 14.025,4.185 14.055,10.674 0.033,8.235 -11.391,8.691 -11.313,12.372 0.027,1.116 1.092,2.307 3.426,2.61 1.155,0.153 4.344,0.27 7.959,-1.395 l 1.419,6.615 c -1.944,0.708 -4.443,1.386 -7.554,1.386 -7.98,0 -13.593,-4.242 -13.638,-10.314 m 34.827,9.744 c -1.548,0 -2.853,-0.903 -3.435,-2.289 l -12.111,-28.917 8.472,0 1.686,4.659 10.353,0 0.978,-4.659 7.467,0 -6.516,31.206 -6.894,0 m 1.185,-8.43 2.445,-11.718 -6.696,0 4.251,11.718 m -46.284,8.43 -6.678,-31.206 8.073,0 6.675,31.206 -8.07,0 m -11.943,0 -8.403,-21.24 -3.399,18.06 c -0.399,2.016 -1.974,3.18 -3.723,3.18 l -13.737,0 -0.192,-0.906 c 2.82,-0.612 6.024,-1.599 7.965,-2.655 1.188,-0.645 1.527,-1.209 1.917,-2.742 l 6.438,-24.903 8.532,0 13.08,31.206 -8.478,0"/>
                                                </clipPath>
                                                <linearGradient id="linearGradient10277" spreadMethod="pad" gradientTransform="matrix(84.1995,31.0088,31.0088,-84.1995,19.512,-27.4192)" gradientUnits="userSpaceOnUse" y2="0" x2="1" y1="0" x1="0">
                                                   <stop id="stop10279" offset="0" style="stop-opacity:1;stop-color:#222357"/>
                                                   <stop id="stop10281" offset="1" style="stop-opacity:1;stop-color:#254aa5"/>
                                                </linearGradient>
                                             </defs>
                                             <metadata id="metadata10311">
                                                <rdf:RDF>
                                                   <cc:Work rdf:about="">
                                                      <dc:format>image/svg+xml</dc:format>
                                                      <dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage"/>
                                                      <dc:title/>
                                                   </cc:Work>
                                                </rdf:RDF>
                                             </metadata>
                                             <g transform="translate(-333.70157,-536.42431)" id="layer1">
                                                <g id="g10267" transform="matrix(4.9846856,0,0,-4.9846856,-1470.1185,1039.6264)">
                                                   <g clip-path="url(#clipPath10271)" id="g10269">
                                                      <g transform="translate(351.611,96.896)" id="g10275">
                                                         <path id="path10283" style="fill: white;fill-opacity:1;fill-rule:nonzero;stroke:none" d="M 0,0 98.437,36.252 120.831,-24.557 22.395,-60.809"/>
                                                      </g>
                                                   </g>
                                                </g>
                                             </g>
                                          </svg>
                                       </div>
                                    </div>
                                    <div class='middle'>
                                       <div class='cd-number'>
                                          <p><span class='num-1'>1234</span><span class='num-2'>1234</span><span class='num-3'>1234</span><span class='num-4'>1234</span></p>
                                       </div>
                                    </div>
                                    <div class='bottom'>
                                       <div class='cardholder align-content-center'>
                                          <p class='holder '>Firstname Lastname</p>
                                       </div>
                                       <div class='expires d-f align-center'>
                                          <div>
                                          <p class='label '>Valid Thru<a</p>
                                          <p><span class='month '>09</span>/<span class='year'>19</span></p>
                                          </div>
                                          <div >
                                          <svg class="mastercard"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="mastercard"><path fill="#FF5F00" d="M15.245 17.831h-6.49V6.168h6.49v11.663z"></path><path fill="#EB001B" d="M9.167 12A7.404 7.404 0 0 1 12 6.169 7.417 7.417 0 0 0 0 12a7.417 7.417 0 0 0 11.999 5.831A7.406 7.406 0 0 1 9.167 12z"></path><path fill="#F79E1B" d="M24 12a7.417 7.417 0 0 1-12 5.831c1.725-1.358 2.833-3.465 2.833-5.831S13.725 7.527 12 6.169A7.417 7.417 0 0 1 24 12z"></path></svg>
                                          </div>
                                       </div>
                                       
                                    </div>
                                 </div>
                                 <div class='back'>
                                    <div class='top'>
                                       <div class='magstripe'></div>
                                    </div>
                                    <div class='middle'>
                                       <p class='label'>CCV</p>
                                       <div class='cvc'>
                                          <p>123</p>
                                       </div>
                                    </div>
                                    <div class='bottom'>
                                    </div>
                                 </div>
                              </div>
                              <div class='form'>
                                 <form>
                                    <div class='cd-numbers'>
                                       <label>Card Number</label>
                                       <div class='fields'>
                                          <input type='text' class='1' maxlength="4" id="cd-number-1" />
                                          <input type='text' class='2'  maxlength="4" id="cd-number-2"/>
                                          <input type='text' class='3'  maxlength="4" id="cd-number-3"/>
                                          <input type='text' class='4' maxlength="4" id="cd-number-4"/>
                                       </div>
                                    </div>
                                    <div class='cd-holder'>
                                       <label for='cd-holder-input'>Card Holder</label>
                                       <input type='text' id='cd-holder-input' />
                                    </div>
                                    <div class='cd-validate'>
                                       <div class='expiration'>
                                          <div class='field'>
                                             <label for='month'>Month</label>
                                             <select id='month'>
                                                <option value='01'>01</option>
                                                <option value='02'>02</option>
                                                <option value='03'>03</option>
                                                <option value='04'>04</option>
                                                <option value='05'>05</option>
                                                <option value='06'>06</option>
                                                <option value='07'>07</option>
                                                <option value='08'>08</option>
                                                <option value='09'>09</option>
                                                <option value='10'>10</option>
                                                <option value='11'>11</option>
                                                <option value='12'>12</option>
                                             </select>
                                          </div>
                                          <div class='field'>
                                             <label for='year'>Year</label>
                                             <select id='year'>
                                                <option value='24'>24</option>
                                                <option value='25'>25</option>
                                                <option value='26'>26</option>
                                                <option value='27'>27</option>
                                                <option value='28'>28</option>
                                                <option value='29'>29</option>
                                                <option value='30'>30</option>
                                                <option value='31'>31</option>
                                                <option value='32'>32</option>
                                                <option value='33'>33</option>
                                                <option value='34'>34</option>
                                                <option value='35'>35</option>
                                                <option value='36'>36</option>
                                                <option value='37'>37</option>
                                                <option value='38'>38</option>
                                                <option value='39'>39</option>
                                                <option value='40'>40</option>
                                             </select>
                                          </div>
                                       </div>
                                       <div class='cvc'>
                                          <label for='cvc'>CCV</label>
                                          <input type='text' id='cvc' maxlength='4' />
                                       </div>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        </div>
                        <div class="payment-error" style="text-align: center; display:none;"><span class="error-message"> This is error </span></div>
                        <!-- edafpay -->
                        <!-- <div class="stripe-form-section hidden mt-4  mx-auto"> -->
                        <div class="card stripeCard hidden" id="stripeform">
                           <div class="bg-danger text-white hidden stripe_alert rounded-lg py-5 px-6 mb-3 text-base text-red-700 inline-flex items-center w-full"
                              role="alert">
                              <svg aria-hidden="true" focusable="false" data-prefix="fas"
                                 data-icon="times-circle" class="w-4 h-4 mr-2 fill-current"
                                 role="img" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 512 512">
                                 <path fill="currentColor"
                                    d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z">
                                 </path>
                              </svg>
                              <div class="stripeText"></div>
                           </div>
                           <div class="card-body">
                              <form method="post"
                                 class="require-validation customform xxxl:w-[680px] s:w-[225px] m:w-[300px] l:w-[400px] sm:w-[320px] md:w-[450px] lg:w-[300px] xl:w-[540px] xxl:w-[550px]"
                                 data-cc-on-file="false" id="stripe-payment-form">
                                 @csrf
                                 <div>
                                    <div class="mb-3">
                                       <div class="form-group">
                                          <label for="email"
                                             class="font-poppins font-medium text-black text-base tracking-wide">{{ __('Email') }}</label>
                                          <input type="email" name="card_email"
                                             title="Enter Your Email" placeholder="Email"
                                             class="email form-control required border border-gray-light focus:outline-none rounded-lg p-3 w-full mt-3" />
                                       </div>
                                    </div>
                                    <div>
                                       <div class="form-group">
                                          <label for="card-number"
                                             class="font-poppins font-medium text-black text-base tracking-wide">{{ __('Card Information') }}</label>
                                          <div class="form-group">
                                             <div id="card-number"></div>
                                          </div>
                                          <div class="form-group">
                                             <div id="card-expiry"></div>
                                          </div>
                                          <input type="hidden"
                                             class="card-expiry-month required form-control"
                                             name="card-expiry-month" />
                                          <input type="hidden"
                                             class="card-expiry-year required form-control"
                                             name="card-expiry-year" />
                                          <div class="form-group">
                                             <div id="card-cvc"></div>
                                          </div>
                                       </div>
                                       <div class="form-group mt-3">
                                          <label
                                             class="font-poppins font-medium text-black text-base tracking-wide ">{{ __('Name on card') }}</label>
                                          <input type="text"
                                             class="required form-control border border-gray-light focus:outline-none rounded-lg p-3 w-full mt-3"
                                             name="card_name" placeholder="Name"
                                             title="Name on Card" required />
                                       </div>
                                    </div>
                                    <div class="form-group text-start">
                                       <button type="submit"
                                          class="bg-primary l:w-[250px] h-[47px] s:w-full px-5 p-2 rounded-md cursor-pointer font-poppins font-medium text-white text-lg mt-4 btn-submit">{{ __('Pay with stripe') }}</button>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                        <div>
                          
                           
                        </div>
                        <!-- </div> -->
                        <div class="mt-3">
                           <button type="submit" id="form_submit"
                              class="font-poppins font-medium text-lg leading-6 text-white bg-primary w-full rounded-md py-3"
                              >
                              <div id="formtext">
                              @if (session('direction') == 'rtl')
                              {{ __('Place Order') }}<i class="fa pr-2 fa-check-square"></i>
                              @else
                              <i class="fa pr-2 fa-check-square"></i>{{ __('Place Order') }}
                              @endif
                               
                              </div>
                              <div id="formloader"
                                 class="hidden mx-auto animate-spin rounded-full border-t-2 border-blue-500 border-solid h-7 w-7">
                              </div>
                           </button>
                        </div>
                     </div>
                     @endif
                  </div>
               </div>
            </div>
            @if ($data->type == 'paid')
            <div class="xlg:w-[25%] xxmd:w-full xxsm:w-full">
               <div class="p-4 bg-white shadow-lg rounded-md space-y-5">
                  <p class="font-poppins font-semibold text-2xl leading-8 text-black pb-3">
                     {{ __('Payment Summary') }}
                  </p>
                  <div
                     class="flex justify-between border border-primary rounded-md py-5 xxsm:flex-wrap sm:flex-nowrap xlg:px-0">
                     <input type="text" value="" name="coupon_code" id="coupon_id"
                        class="focus:outline-none font-poppins font-normal text-base leading-6 text-white-100 ml-5 1xl:w-44 xl:w-36
                        xlg:w-28 rtl-mr-10"
                        placeholder="{{ __('Coupon Code') }}">
                     <button type="button" id="apply" name="apply"
                        class="font-poppins font-medium text-base leading-6 text-primary focus:outline-none mr-5 rtl-ml-10">{{ __('Apply') }}</button>
                  </div>
                  <div class="couponerror"></div>
                  <div class="flex justify-between border-dashed border-b border-gray-light pb-5">
                     <p class="font-poppins font-normal text-lg leading-7 text-gray-200">
                        {{ __('Coupon discount') }}
                     </p>
                     <p class="font-poppins font-medium text-lg leading-7 text-gray-300 discount">00.00</p>
                  </div>
                  <div class="flex justify-between">
                     <p class="font-poppins font-normal text-lg leading-7 text-gray-200">
                        {{ __('Tickets amount') }}
                     </p>
                     <p class="font-poppins font-medium text-lg leading-7 text-gray-300 ticket-price-amount">

                        
                        {{-- @if ($data->seatmap_id == null) --}}
                        @if (session('direction') == 'rtl')
                        {{ request('quantity')  * $data->price  }} {{ __($currency) }} 
                        @else
                        {{request('quantity')  * $data->price  }} {{ __($currency) }} 
                        @endif

                        {{-- @endif --}}
                     </p>
                  </div>
                  @if(count($data->tax) >= 1)
                  <p class="font-poppins font-semibold text-base leading-8 text-black ">
                     {{ __('Taxes and Charges') }}
                  </p>
                  <div class="taxes">
                     @foreach ($data->tax as $key => $item)
                     <input type="hidden" class="amount_type" name="amount_type"
                        value="{{ $item->amount_type }}">
                     <div class="flex justify-between">
                        <p class="font-poppins font-normal text-lg leading-7 text-gray-200 ">
                           {{ $item->name }}
                           @if ($item->amount_type == 'percentage')
                           ({{ $item->price . '%' }})
                           @endif
                        </p>
                        <p class="font-poppins font-medium text-lg leading-7 text-gray-300 tax_total_price">
                           @if ($item->amount_type == 'percentage')
                           @php
                           $result = ($data->price * $item->price) / 100;
                           $formattedResult = round($result, 2);
                           @endphp
                              @if (session('direction') == 'rtl')
                              {{ request('quantity')  *request('quantity')  * $formattedResult }} {{ __($currency) }} 
                              @else
                              {{request('quantity')  *  $formattedResult }} {{ __($currency) }} 
                              @endif
                           
                           @else
                              @if (session('direction') == 'rtl')
                              {{request('quantity')  * $item->price }} {{ __($currency) }} 
                              @else
                              {{request('quantity')  * $item->price }} {{ __($currency) }} 
                              @endif
                           
                           @endif
                          
                        </p>
                     </div>
                     @endforeach
                  </div>
                  @endif
                  <div class="flex justify-between">
                     <p class="font-poppins font-normal text-lg leading-7 text-gray-200">
                        {{ __('Total Tax amount') }}
                     </p>
                     <p class="font-poppins font-medium text-lg leading-7 text-gray-300 totaltax tax_total">
                     @if (session('direction') == 'rtl')
                     {{ request('quantity')  *$data->tax_total }} {{ __($currency) }} 
                     @else
                     {{  request('quantity')  * $data->tax_total }} {{ __($currency) }} 
                     @endif
                        
                     </p>
                  </div>
                  
                  
                  <div class="flex justify-between">
                     <p
                        class="font-poppins font-semibold text-xl leading-7 text-primary xlg:text-lg 1xl:text-xl">
                        {{ __('Total amount') }}
                     </p>
                     <p
                        class="font-poppins font-semibold text-2xl leading-7 text-primary xlg:text-lg 1xl:text-2xl subtotal  payment">

                        @if ($data->seatmap_id == null || $data->module->is_enable == 0)
                        
                        @if (session('direction') == 'rtl')
                        {{ ( request('quantity')  *  $data->price) + (request('quantity')  * $data->tax_total) }} {{ __($currency) }} 
                        @else
                        {{ (request('quantity')  *  $data->price)  + ( request('quantity')  *$data->tax_total) }} {{ __($currency) }} 
                        @endif
                        @endif
                     </p>
                  </div>
               </div>
            </div>
            @endif
         </div>
      </div>
   </div>
</div>

<!-- Bootstrap 5 CSS -->
<style>
   /* Modal container styles */
.modal {
  display: none;
  position: fixed;
  z-index: 1050;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: hidden;
  background-color: rgba(0, 0, 0, 0.5); /* Background overlay */
}

.modal.show {
  display: block;
  padding-right: 17px;
  overflow-x: hidden;
  overflow-y: auto;
}

.modal-dialog {
  position: relative;
  margin: 1.75rem auto;
  max-width: 500px;
  pointer-events: none;
}

.modal-content {
  position: relative;
  display: flex;
  flex-direction: column;
  background-color: #fff;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 0.3rem;
  outline: 0;
  pointer-events: auto;
}

.modal-header,
.modal-footer {
  display: flex;
  flex-shrink: 0;
  align-items: center;
  justify-content: space-between;
  padding: 1rem;
  border-bottom: 1px solid #e9ecef;
}

.modal-header {
  border-bottom: none;
}

.modal-footer {
  border-top: 1px solid #e9ecef;
}

.modal-title {
  margin-bottom: 0;
  line-height: 1.5;
  font-size: 1.25rem;
}

.modal-body {
  position: relative;
  flex: 1 1 auto;
  padding: 1rem;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1040;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal.fade .modal-dialog {
  transition: transform 0.3s ease-out;
}

.modal.fade .modal-dialog {
  transform: translateY(-50px);
}

.modal.show .modal-dialog {
  transform: translateY(0);
}

/* Close button */
.btn-close {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
}

.btn-close:before {
  content: "\00d7";
  font-size: 1.5rem;
  color: #000;
}

.modal-backdrop.show {
  opacity: 0.5;
}

   </style>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 JavaScript (Make sure it's Bootstrap 5 if you're using Bootstrap 5 modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Modal HTML -->
<div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Login Required</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="registerForm">
                @csrf 
                  
                        <input type="hidden" name="checkout" value="checkout">
                        <input type="hidden" value="user" checked name="user_type"> 
                       
                <div class="grid grid-cols-2 gap-5 xxsm:grid-cols-1">
                     

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
                    <input type="hidden" name="Countrycode" value="966"> 

                    <div class="">
                        <label for="number"
                            class="font-bold-big font-poppins font-medium text-base leading-6 text-black">{{ __('Contact Number') }}</label>
                        <div class="flex space-x-3">
                            <div class="w-[100%]">
                                <input type="number" name="phone" id=""
                                    class="font-bold-big w-full text-sm font-poppins font-normal text-black block p-3 z-20 rounded-lg border border-gray-light focus:outline-none"
                                    placeholder="{{ __('Number') }}">
                            </div>
                        </div>
                    </div> 
                    <div class=" ">
                        <label for="email"
                            class="font-bold-big font-poppins font-medium text-base leading-6 text-black">{{ __('Email Address') }}</label>
                        <input type="email" name="email" id="" required
                            class="font-bold-big w-full text-sm font-poppins font-normal text-black block p-3 z-20 rounded-lg border border-gray-light focus:outline-none"
                            placeholder="{{ __('Email Address') }}">
                    </div> 

                    <div id="errorMessages"></div>
                </div> 
            </form>
            <!-- <div class="pt-6 flex justify-center">
                <h1 class="font-bold-big font-poppins font-medium text-base leading-5 pt-4 text-left text-gray">
                    {{ __('Already have an account?') }}
                    <a href="{{ url('/user/login') }}"
                        class="font-bold-big text-primary text-medium text-base">{{ __('Login') }}</a>
                </h1>
            </div> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button  id="submitForm" class="btn btn-primary">Register</button>
      </div>
    </div>
  </div>
</div>
<script src="{{asset('frontend/js/payment-card.js')}}"></script> 
<script src="https://applepay.cdn-apple.com/jsapi/v1/apple-pay-sdk.js"></script>
<script>
   

   $(document).ready(function() {
    $('#submitForm').on('click', function(e) {
        e.preventDefault(); // Prevent the default form submission
        
        // Serialize form data
        var formData = $('#registerForm').serialize();
        
        // Send AJAX request
        $.ajax({
            url: "{{ url('user/register') }}", // Laravel route
            type: "POST",
            data: formData,
            success: function(response) {
               if (response.success) {
                // Display success message
                $('#responseMessage').html('<p class="text-green-500">' + response.message + '</p>');
                
                // Redirect after 3 seconds
                setTimeout(function() {
                    window.location.reload();
                }, 100);
            } else {
                // Display error message if registration failed
                $('#responseMessage').html('<p class="text-red-500">' + response.message + '</p>');
            }
            },
            error: function(xhr) {
                  // Handle validation errors or other failures 
                     let errors = xhr.responseJSON.errors;

                     // Clear any previous error messages
                     $('#errorMessages').html('');

                     // Loop through the errors and display them
                     $.each(errors, function(field, messages) {
                        if( messages[0] == "The email has already been taken.")
                        {
                            var event_id = $("#event-id").val();
                           var event_quantity = $("#event-quantity").val(); 
                              alert(event_id ,  event_quantity);
                             $('#errorMessages').append('<p>' + messages[0] + '. Click here to <a style="    color: #2b2b79;" href="https://ticketby.co/user/login?checkout=checkout/'+event_id+'?quantity='+event_quantity+'"> login </a></p>');
                        }
                        else
                        {
                             $('#errorMessages').append('<p>' + messages[0] + '</p>');
                        }
                       
                     }); 
            }

        });
    });
});

 
           
         

   </script>

<script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check for Apple Pay availability
            if (window.ApplePaySession && ApplePaySession.canMakePayments()) { 
                // Event listener for the Apple Pay button
                document.getElementById('applePayButton').addEventListener('click', function () { 


                  if ($("#is-login").val() == 0) { 
                     $("#login-error-payment").css("display", "block");

                     var event_id = $("#event-id").val();
                     var event_quantity = $("#event-quantity").val(); 

                     setTimeout(function() {
                        $("#registrationModal").modal("show"); 
                        return false;
                     }, 3000);
                     return false;
                  } else { 
                     $("#login-error-payment").css("display", "none"); 
                  }

                    const paymentRequest = {
                        countryCode: 'SA',
                        currencyCode: 'SAR',
                        supportedNetworks: ['visa', 'masterCard', 'amex','mada'],
                        merchantCapabilities: ['supports3DS'],
                        total: {
                            label: 'Darlana',
                            amount: $('#payment').val()
                        }
                    };

                    const session = new ApplePaySession(3, paymentRequest);

                    // Merchant validation
                    session.onvalidatemerchant = function (event) {
                        const validationURL = event.validationURL;
                        fetch('/validate-merchant', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ validationURL: validationURL })
                        })
                        .then(response => response.json())
                        .then(data => {
                            session.completeMerchantValidation(data);
                        })
                        .catch(error => console.error('Error during merchant validation:'));
                    };

                    // Payment authorization
                    session.onpaymentauthorized = function (event) {
                        const payment = event.payment;
                        fetch('{{route("createOrderUser")}}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            }, 
                            body: JSON.stringify({ token: payment.token,payment_type:'ApplePay',amount:$('#payment').val(),event_id:$('#event-id').val(),ticket_id:$('#ticket_id').val(),payment:$('#payment').val(),tax:$('#tax').val(),coupon_id:$('#coupon_id').val(),selectedSeatsId:$('#selectedSeatsId').val(),selectedSeats:$('#selectedSeats').val(),quantity:$('#event-quantity').val(),email:$('#email').val(),name:$('#name').val(),phone:$('#phone').val() })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Server response:', data);  
                            if (data.success == true) {
                                session.completePayment(ApplePaySession.STATUS_SUCCESS);
                                window.location.replace('/thankyou');

                            } else {
                                session.completePayment(ApplePaySession.STATUS_FAILURE); 
                            }
                        })
                        .catch(error => {
                            session.completePayment(ApplePaySession.STATUS_FAILURE);
                            console.error('Error processing payment:', error);
                        });
                    };

                    session.begin();
                });
            } else {
                console.log('Apple Pay is not available on this device/browser.');
            }
        });
    </script>
@endsection
