@extends('front.master', ['activePage' => 'checkout'])
@section('title', __('Checkout'))
@section('content')
{{-- content --}}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script async crossorigin
    src="https://applepay.cdn-apple.com/jsapi/1.latest/apple-pay-sdk.js"></script>


<script async crossorigin
        src="https://applepay.cdn-apple.com/jsapi/1.latest/apple-pay-sdk.js"
        ></script>
<style>
apple-pay-button {
  --apple-pay-button-width: 150px;
  --apple-pay-button-height: 30px;
  --apple-pay-button-border-radius: 3px;
  --apple-pay-button-padding: 0px 0px;
  --apple-pay-button-box-sizing: border-box;
}

.apple-pay-btn-wrapper {
    width: 100%;
    min-height: 50px; /* Increased height */
    height: var(--apple-pay-button-height, 50px); /* Increased height */
}

.apple-pay-button {
    display: inline-block;
    -webkit-appearance: -apple-pay-button;
    appearance: -apple-pay-button;
    apple-pay-button-type: buy;
    apple-pay-button-style: white; /* Change from 'black' to 'white' */
    height: 44px;
    width: 100%;
    --apple-pay-button-width: 150px;
    --apple-pay-button-height: 30px;
    --apple-pay-button-border-radius: 3px;
    --apple-pay-button-padding: 0px 0px;
    --apple-pay-button-box-sizing: border-box;
}

</style>

@php
$lang = session('direction') == 'rtl' ? 'ar' : 'en';

@endphp
@php $ticket_amount = 0 ;

@endphp
@foreach( $data->ticket_details as $ticket)
@php $ticket_ids =  implode(', ', request('ids')); $ticket_quantities =  implode(', ', request('quantities')) ; 

if($ticket_ids == 140 && $ticket->selected_quantity > 1)
{
    $ticket_amount = $ticket_amount + (200* $ticket->selected_quantity);
}
else
{
    $ticket_amount = $ticket_amount + ($ticket->price * $ticket->selected_quantity);    
}

@endphp


@endforeach


<div class="container mt-12 md:mt-16 ">
    <div class="grid grid-cols-12 xl:gap-14">
        <div id="ticketorder"
            class="col-span-12 lg:col-span-7 bg-primary_color_o10_1 bg-opacity-5 rounded-2xl border border-primary_color_o10_1 p-2 md:p-4">
            <div class="mb-4 flex gap-1 flex-wrap">
                <h3 class="font-bold md:text-h3 text-h5">{{ __('Payment methods') }}</h3>
                <p class="text-h5 xl:text-h4 text-gray_9"> {{ __('How would you like to pay?') }}</p>
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
            @csrf
            <input type="hidden" id="razor_key" name="razor_key"
                value="{{ \App\Models\PaymentSetting::find(1)->razorPublishKey }}">
            <input type="hidden" id="stripePublicKey" name="stripePublicKey"
                value="{{ \App\Models\PaymentSetting::find(1)->stripePublicKey }}">
            <input type="hidden" value="{{ $data->ticket_per_order }}" name="tpo" id="tpo">
            @php 
            $timeSlotIds = request()->query('time_slot_id', []);
            $slotEventDate = request()->query('slot-event-date');
            @endphp


            <input type="hidden" value="{{ !is_null(request('time_slot_id'))  ? implode(',',request('time_slot_id')  ) : null }}" name="time_slot_id" id="time_slot_id"> 
            <input type="hidden" value="{{  request('slot-event-date') }}" name="slot-event-date" id="slot-event-date"> 
            <input type="hidden" value="{{ $ticket_ids}}" name="ticket_ids" id="ticket_ids">
            <input type="hidden" value="{{ $ticket_quantities }}" name="ticket_quantities" id="ticket_quantities">
            <input type="hidden" value="{{ $data->available_qty }}" name="available" id="available">
            <input type="hidden" name="price" id="ticket_price" value="{{ $data->price }}">
            <input type="hidden" name="tax" id="tax_total" value="{{$data->tax_total}}">
            <input type="hidden" name="tax_total_price" id="tax_total_price" value="{{$data->tax_total}}">

            <input type="hidden" name="payment" id="payment"
                value="{{$data->total_amount}}">

            <input type="hidden" name="ticket_total" id="ticket_total"
                value=" {{$ticket_amount}}">
            @php
            $price = (request('quantity') * $data->price) + ( request('quantity') * $data->tax_total);
            if ($data->currency_code == 'USD' || $data->currency_code == 'EUR' || $data->currency_code == 'INR') {
            $price = $price * 100;
            }
            @endphp
            <input type="hidden" name="stripe_payment" id="stripe_payment"
                value="{{$data->total_amount}}">
            <input type="hidden" name="currency_code" id="currency_code" value="{{ $data->currency_code }}">
            <input type="hidden" name="currency" id="currency" value="{{ __($currency) }}">
            <input type="hidden" name="payment_token" id="payment_token">
            <input type="hidden" name="ticket_id" id="ticket_id" value="{{ $ticket->id }}">
            
            <input type="hidden" name="selectedSeats" id="selectedSeats">
            <input type="hidden" name="selectedSeatsId[]" id="selectedSeatsId">
            <input type="hidden" name="coupon_id" id="coupon_id" value="">
            <input type="hidden" name="coupon_discount" id="coupon_discount" value="0">
            <input type="hidden" name="ticket-price-amount" id="ticket-price-amount" value="">
            <input type="hidden" name="add_ticket" value="">
            <input type="hidden" name="event_id" value="{{ $data->event->id }}" id="event_id">
            @foreach( $data->ticket_details as $ticket)
            <input type="hidden" name="event_id" value="{{ $data->event->id }}" id="event-id">
            <input type="hidden" name="ticketname" id="ticketname" value="{{ $ticket->name }}">
            @endforeach
            @if ($data->available_qty > 0)
            <!-- edafpay  -->
            <div class="mb-4">
                <div
                    class="bg-gray_f  w-full rounded-2xl border border-primary_color_o10_1 p-24-16 flex justify-between items-center payments">
                    <div class="flex gap-4 items-center">
                        <div class=" border border-gray_9 rounded-md py-1 px-3 flex  items-center justify-center">
                            <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.59375 8.9375C4.94922 8.9375 5.25 9.23828 5.25 9.59375C5.25 9.97656 4.94922 10.25 4.59375 10.25H3.28125C2.89844 10.25 2.625 9.97656 2.625 9.59375C2.625 9.23828 2.89844 8.9375 3.28125 8.9375H4.59375ZM9.84375 8.9375C10.1992 8.9375 10.5 9.23828 10.5 9.59375C10.5 9.97656 10.1992 10.25 9.84375 10.25H6.78125C6.39844 10.25 6.125 9.97656 6.125 9.59375C6.125 9.23828 6.39844 8.9375 6.78125 8.9375H9.84375ZM14 0.625C14.957 0.625 15.75 1.41797 15.75 2.375V11.125C15.75 12.1094 14.957 12.875 14 12.875H1.75C0.765625 12.875 0 12.1094 0 11.125V2.375C0 1.41797 0.765625 0.625 1.75 0.625H14ZM14 1.9375H1.75C1.50391 1.9375 1.3125 2.15625 1.3125 2.375V3.25H14.4375V2.375C14.4375 2.15625 14.2188 1.9375 14 1.9375ZM14.4375 5.875H1.3125V11.125C1.3125 11.3711 1.50391 11.5625 1.75 11.5625H14C14.2188 11.5625 14.4375 11.3711 14.4375 11.125V5.875Z" fill="#FBF9FD" />
                            </svg>
                        </div>
                        <div class="h6">{{__('Cards')}}</div>
                    </div>
                    <div class="relative">
                        <input id="Edafpay" type="radio" value="EDAFPAY"
                            name="payment_type"
                            class="h-5 w-5  border border-gray-light  hover:border-gray-light focus:outline-none mt-15 opacity-0 z-10 relative" selected="selected">
                        <span class="top-0 border-2 w-5 h-5 rounded-full border-bottom border-gray_6 flex items-center justify-center absolute">
                            <span class="hidden">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="0.5" y="0.5" width="19" height="19" rx="9.5" fill="#A986BF" />
                                    <rect x="0.5" y="0.5" width="19" height="19" rx="9.5" stroke="#A986BF" />
                                    <path d="M13.3337 7.5L8.75033 12.0833L6.66699 10" stroke="#001B11" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="edafpay card-pay hidden bg-gray_f  w-full rounded-2xl border border-primary_color_8 p-24-16 mt-2">
                    <form>
                        <div class='cd-holder mb-4'>
                            <label for='cd-holder-input' class="mb-1 block text-gray_b5 md:text-h5 text-h6 ">{{ __('Card Holder') }}</label>
                            <input class='bg-gray_b12 bg-opacity-30 p-1 md:p-16-16 w-full focus:border-primary_color_6 outline-0 rounded-lg border border-primary_color_o10_1' type='text' id='cd-holder-input' />
                        </div>
                        <div class='cd-numbers mb-4'>
                            <label class="mb-1 block text-gray_b5 md:text-h5 text-h6 ">{{ __('Card Number') }}</label>
                            <div id="cd-numbergnate" class='fields flex bg-gray_b12 bg-opacity-30  w-full  outline-0 rounded-lg border border-primary_color_o10_1 focus:border-primary_color_6 outline-0 '>
                                <input type="text" maxlength="19" class="bg-transparent bg-opacity-30  text-center  w-full p-1 md:p-16-16  outline-0 rounded-lg border border-primary_color_0 m-0">
                                <input type='hidden' class='' maxlength="4" id="cd-number-1" />
                                <input type='hidden' class='' maxlength="4" id="cd-number-2" />
                                <input type='hidden' class='' maxlength="4" id="cd-number-3" />
                                <input type='hidden' class='' maxlength="4" id="cd-number-4" />
                            </div>
                        </div>
                        <div class='flex gap-1'>
                            <div class='flex-1 '>
                                <label class=' mb-1 block text-gray_b5 md:text-h5 text-h6 '>{{ __('Expiry date') }}</label>
                                <div class="flex bg-gray_b12 bg-opacity-30  w-full  outline-0 rounded-lg border border-primary_color_o10_1">
                                    <input type="hidden" id='month' value="" maxlength="2">
                                    <input type="hidden" id='year' value="" maxlength="2">
                                    <input type="text" placeholder="MM/YY" name="expiry-date" id="expiry-date" class="bg-transparent text-center p-1 md:p-16-16 w-full  focus:border-primary_color_6 outline-0 rounded-lg border border-primary_color_0 m-0" maxlength="5">
                                </div>
                            </div>
                            <div class='flex-1 '>
                                <label class="mb-1 block text-gray_b5 md:text-h5 text-h6 " for='cvc'>{{ __('CCV') }}</label>
                                <input class="bg-gray_b12 bg-opacity-30 p-1 md:p-16-16 w-full focus:border-primary_color_6 outline-0 rounded-lg border border-primary_color_o10_1" type='text' id='cvc' />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="payment-error text-red mt-1 text-h6 flex items-center gap-1" style="text-align: center; display:none;">
                    <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 10.25C9.40625 10.25 9.75 10.5938 9.71875 11C9.71875 11.4375 9.40625 11.75 9 11.75C8.59375 11.75 8.25 11.4375 8.25 11C8.25 10.5938 8.5625 10.25 9 10.25ZM9 9C8.71875 9 8.5 8.78125 8.5 8.5V4C8.5 3.75 8.75 3.5 9 3.5C9.21875 3.5 9.46875 3.75 9.46875 4V8.5C9.46875 8.78125 9.25 9 9 9ZM16.75 11.4375C17.0625 11.9688 17.0625 12.5938 16.75 13.125C16.4375 13.6875 15.875 14 15.25 14H2.75C2.09375 14 1.53125 13.6875 1.21875 13.125C0.90625 12.5938 0.90625 11.9688 1.21875 11.4375L7.46875 0.875C7.78125 0.34375 8.34375 0 9 0C9.625 0.03125 10.1875 0.34375 10.5 0.875L16.75 11.4375ZM15.875 12.625C16.0312 12.4062 16 12.1562 15.875 11.9375L9.625 1.375C9.5 1.15625 9.25 1.03125 9 1C8.96875 1 9 1 9 1C8.71875 1 8.46875 1.15625 8.34375 1.375L2.09375 11.9375C1.96875 12.1562 1.9375 12.4062 2.09375 12.625C2.21875 12.875 2.46875 13 2.75 13H15.2188C15.5 13 15.75 12.875 15.875 12.625Z" fill="#E55E73" />
                    </svg>
                    <span class="error-message"> {{ __('This is error') }} </span>
                </div>
            </div>
            <!-- edafpay -->
            <!-- epay  -->
            @if($data->event->id == 151)
            @endif 

            <div class="  mb-4">
                <div
                    class="bg-gray_f  w-full rounded-2xl border border-primary_color_o10_1 p-24-16 flex justify-between items-center payments ">
                    <div class="flex gap-4 items-center">
                        <div class=" border border-gray_9 rounded-md py-1 px-3 flex  items-center justify-center">
                            <svg width="29" height="12" viewBox="0 0 29 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.06188 1.54754C6.39494 1.13093 6.62093 0.571597 6.56136 0C6.07382 0.0242562 5.47886 0.321685 5.13441 0.738572C4.82511 1.09562 4.55139 1.67838 4.62277 2.22599C5.16999 2.27346 5.71683 1.95243 6.06188 1.54754Z" fill="white" />
                                <path d="M6.55468 2.33105C5.75987 2.2837 5.08407 2.78214 4.70449 2.78214C4.32474 2.78214 3.74348 2.35492 3.11482 2.36642C2.29658 2.37846 1.53736 2.8411 1.12213 3.57691C0.268093 5.04891 0.896757 7.23242 1.72726 8.43125C2.13055 9.02434 2.61666 9.67744 3.25709 9.65396C3.86222 9.6302 4.09942 9.2621 4.83494 9.2621C5.56997 9.2621 5.78363 9.65396 6.42417 9.64208C7.08847 9.6302 7.50375 9.04866 7.90704 8.45496C8.36973 7.77882 8.55913 7.126 8.57101 7.09014C8.55908 7.07826 7.29009 6.59142 7.27832 5.13168C7.26633 3.90942 8.27464 3.32799 8.3221 3.29197C7.75277 2.4499 6.8631 2.35492 6.55468 2.33105Z" fill="white" />
                                <path d="M13.4746 0.680176C15.2022 0.680176 16.4051 1.871 16.4051 3.60474C16.4051 5.34467 15.1774 6.54168 13.4313 6.54168H11.5186V9.58343H10.1367V0.680176H13.4746ZM11.5186 5.38169H13.1042C14.3074 5.38169 14.9922 4.7339 14.9922 3.61093C14.9922 2.48807 14.3074 1.84635 13.1104 1.84635H11.5186V5.38169Z" fill="white" />
                                <path d="M16.7656 7.73882C16.7656 6.60347 17.6356 5.90628 19.1782 5.81992L20.955 5.71505V5.21534C20.955 4.49345 20.4675 4.06154 19.6533 4.06154C18.8819 4.06154 18.4006 4.43168 18.2835 5.01173H17.0249C17.0989 3.83936 18.0983 2.97559 19.7026 2.97559C21.2758 2.97559 22.2815 3.80853 22.2815 5.11036V9.58356H21.0043V8.51618H20.9736C20.5972 9.23812 19.7766 9.69462 18.9252 9.69462C17.6541 9.69462 16.7656 8.90483 16.7656 7.73882ZM20.9549 7.15269V6.6406L19.3569 6.73928C18.561 6.79487 18.1106 7.14656 18.1106 7.70185C18.1106 8.26942 18.5795 8.63967 19.2953 8.63967C20.2269 8.63956 20.9549 7.9979 20.9549 7.15269Z" fill="white" />
                                <path d="M23.4894 11.9692V10.8894C23.5879 10.9141 23.81 10.9141 23.9211 10.9141C24.5381 10.9141 24.8713 10.655 25.0748 9.98863C25.0748 9.97626 25.1921 9.59374 25.1921 9.58755L22.8477 3.09058H24.2912L25.9326 8.37208H25.9572L27.5985 3.09058H29.0051L26.574 9.92067C26.0189 11.4941 25.3772 12 24.0322 12C23.9211 12 23.5879 11.9876 23.4894 11.9692Z" fill="white" />
                            </svg>
                        </div>
                        <div class="h6">Apple Pay</div>
                    </div>
                    <div class="relative">
                        <input id="EPAY" type="radio" value="EPAY"
                            name="payment_type"
                            class="opacity-0 h-5 w-5  border border-gray-light  hover:border-gray-light focus:outline-none mt-15 z-10 relative" selected="selected">
                        <span class="top-0 border-2 w-5 h-5 rounded-full border-bottom border-gray_6 flex items-center justify-center absolute">
                            <span class="hidden">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="0.5" y="0.5" width="19" height="19" rx="9.5" fill="#A986BF" />
                                    <rect x="0.5" y="0.5" width="19" height="19" rx="9.5" stroke="#A986BF" />
                                    <path d="M13.3337 7.5L8.75033 12.0833L6.66699 10" stroke="#001B11" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
                <div   class="epay  hidden bg-gray_f  w-full rounded-2xl border border-primary_color_8 p-24-16 mt-2" style="display: none;"> 
                    <div class="mb-4" id="applePayContainer" > 
                        <div class="epay w-full rounded-2xl mt-2">
                            <div class="apple-pay-button-div">
                                <div id="applePayButton"></div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           

            <!-- epay  -->
            <!-- stc  -->
            <div class="hidden mb-4">
                <div
                    class="bg-gray_f  w-full rounded-2xl border border-primary_color_o10_1 p-24-16 flex justify-between items-center payments ">
                    <div class="flex gap-1">
                        <svg width="58" height="18" viewBox="0 0 58 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M37.3577 12.9453V4.20904H38.8392V4.90954C39.29 4.34592 39.9584 4.03992 40.7877 4.03992C42.4947 4.03992 43.7185 5.40067 43.7185 7.34917C43.7185 9.29767 42.4867 10.6827 40.7877 10.6827C39.9503 10.6827 39.29 10.3605 38.8392 9.79692V12.9452L37.3577 12.9453ZM38.8393 6.54417V8.15454C39.1533 8.80679 39.7169 9.28179 40.5302 9.28179C41.5125 9.28179 42.1728 8.54904 42.1728 7.34129C42.1728 6.14967 41.5125 5.42492 40.5302 5.42492C39.7169 5.43292 39.1533 5.87579 38.8393 6.54417Z" fill="#02AA7C" />
                            <path d="M44.6768 8.70205C44.6768 7.63918 45.337 6.89843 46.432 6.76155L48.9523 6.43943V6.12543C48.9523 5.74705 48.759 5.54568 48.4048 5.54568H45.2404V4.20105H48.4611C49.7414 4.20105 50.4419 4.82905 50.4419 5.9403V10.4895H48.9443V9.72468C48.5095 10.3367 47.7929 10.6828 46.8669 10.6828C45.5625 10.6828 44.6768 9.88568 44.6768 8.70205ZM47.2291 9.47492C47.986 9.47492 48.6865 9.04818 48.9441 8.42018V7.63105L47.0198 7.8968C46.4884 7.9693 46.1904 8.25105 46.1904 8.71005C46.1905 9.20118 46.5689 9.47492 47.2291 9.47492Z" fill="#02AA7C" />
                            <path d="M51.5208 11.6007H52.5998C53.0104 11.6007 53.1956 11.4316 53.3325 11.0451L53.5258 10.4894L51.239 4.20093H52.785L54.2101 8.58118H54.2424L55.6031 4.20093H57.1249L54.6208 11.4234C54.2826 12.4138 53.8316 12.9452 52.7286 12.9452H51.5209L51.5208 11.6007Z" fill="#02AA7C" />
                            <path d="M6.31 17.2692C8.03313 17.2692 9.45025 16.7297 10.3842 15.828C11.0847 15.1355 11.4954 14.2015 11.4954 13.1226C11.4954 12.1483 11.133 11.2707 10.4648 10.6023C9.7965 9.93396 8.83825 9.44283 7.6305 9.20933L5.64975 8.82283C4.8285 8.66983 4.3615 8.25921 4.3615 7.68758C4.3615 6.93871 5.08612 6.42346 6.24562 6.42346C6.97025 6.42346 7.59025 6.65696 8.00088 7.06758C8.2585 7.34946 8.43562 7.71171 8.492 8.12233L11.3504 7.47821C11.2699 6.65696 10.8834 5.93221 10.2956 5.33646C9.35362 4.41046 7.91225 3.82271 6.21338 3.82271C4.64325 3.82271 3.33087 4.33808 2.421 5.15933C1.648 5.88396 1.21325 6.85821 1.21325 7.94521C1.21325 8.89533 1.51925 9.70046 2.13925 10.3125C2.75925 10.9325 3.661 11.3915 4.84462 11.6812L6.80125 12.1482C7.78363 12.3817 8.21838 12.7441 8.21838 13.3882C8.21838 14.1853 7.49375 14.6523 6.31013 14.6523C5.45663 14.6523 4.76412 14.3705 4.32937 13.9035C4.02337 13.5975 3.83825 13.1788 3.814 12.7198L0.875 13.3641C0.9555 14.2417 1.36612 15.0147 1.98612 15.6347C2.96038 16.6572 4.53863 17.2692 6.31 17.2692ZM28.066 17.2692C29.9743 17.2692 31.4398 16.5767 32.4461 15.5945C33.2433 14.8215 33.7344 13.9197 33.9921 12.9937L30.9808 11.9872C30.8519 12.4542 30.5942 12.9453 30.1836 13.3238C29.6925 13.7908 29.0241 14.121 28.066 14.121C27.1884 14.121 26.3671 13.7828 25.7712 13.195C25.1754 12.575 24.8131 11.6732 24.8131 10.5378C24.8131 9.37833 25.1755 8.50071 25.7712 7.88071C26.359 7.28483 27.1643 6.97896 28.0419 6.97896C28.9679 6.97896 29.612 7.28496 30.079 7.75196C30.4655 8.13846 30.699 8.62958 30.852 9.12071L33.9198 8.09008C33.6862 7.18833 33.1951 6.28646 32.4785 5.53771C31.4479 4.53121 29.9502 3.81458 27.9695 3.81458C26.1418 3.81458 24.4911 4.50708 23.3075 5.69871C22.1239 6.90646 21.3993 8.58933 21.3993 10.546C21.3993 12.5026 22.1481 14.1773 23.3559 15.3932C24.5312 16.5767 26.206 17.2692 28.066 17.2692ZM17.655 17.2692C18.9916 17.2692 19.9497 16.8586 20.3604 16.4962V13.7103C20.0464 13.9438 19.4344 14.2257 18.6292 14.2257C18.0576 14.2257 17.6469 14.0968 17.341 13.8151C17.0834 13.5575 16.9545 13.1146 16.9545 12.5268V0.730957H13.5486V4.20933H20.3524V7.51058H13.5486V13.3642C13.5486 14.5478 13.911 15.506 14.5551 16.1743C15.2797 16.8827 16.3425 17.2692 17.655 17.2692Z" fill="white" />
                        </svg>
                        <h5 class="h6">{{ __('Pay for your order using your mobile number registered in STC Pay') }}</h5>
                    </div>
                    <div>
                        <input id="STC" type="radio" value="STC"
                            name="payment_type"
                            class="h-5 w-5  border border-gray-light  hover:border-gray-light focus:outline-none mt-15" selected="selected">
                    </div>
                </div>
                <div class="stc   hidden bg-gray_f  w-full rounded-2xl border border-primary_color_8 p-24-16 mt-2">
                    <h1>test</h1>
                </div>
            </div>
            <!-- stc  -->
            <!-- Tabby  -->
            <!-- <div class="mb-4">
                <div
                    class="bg-gray_f  w-full rounded-2xl border border-primary_color_o10_1 p-24-16 flex justify-between items-center payments ">
                    <div class="flex gap-4 items-center">
                        <div class=" border border-gray_9 rounded-md py-1 px-3 flex  items-center justify-center">
                            <svg width="28" height="12" viewBox="0 0 28 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M25.8315 11.3397H2.16847C0.972227 11.3397 0 10.3675 0 9.16832V2.355C0 1.15875 0.972227 0.186523 2.16847 0.186523H25.8315C27.0307 0.186523 28 1.15875 28 2.355V9.16868C28 10.3675 27.0278 11.3397 25.8315 11.3397Z" fill="url(#paint0_linear_2077_259)" />
                                <path d="M24.2265 3.72461L22.7305 9.44283L22.7246 9.45424H23.8933L25.3948 3.72461H24.2265Z" fill="#292929" />
                                <path d="M5.69473 6.97827C5.5406 7.05956 5.33056 7.10701 5.13155 7.10701C4.71147 7.10701 4.4731 7.0397 4.44809 6.70091V6.67847C4.44809 6.66449 4.44515 6.65051 4.44515 6.63653V5.65033L4.44809 5.53262V4.83517H4.44515V4.53832L4.44809 4.42061V3.74781L3.40597 3.88502C4.11187 3.74781 4.51541 3.19015 4.51541 2.63543V2.2937H3.34417V3.89348L3.27686 3.91298V6.87711C3.31622 7.70918 3.86505 8.20505 4.76444 8.20505C5.08373 8.20505 5.43393 8.13221 5.70283 8.01156L5.70834 8.00861V6.96907L5.69473 6.97827Z" fill="#292929" />
                                <path d="M5.87621 3.51001L2.58984 4.01691V4.84898L5.87621 4.34209V3.51001Z" fill="#292929" />
                                <path d="M5.87621 4.72949L2.58984 5.23639V6.03205L5.87621 5.52515V4.72949Z" fill="#292929" />
                                <path d="M9.56533 5.11277C9.51788 4.18836 8.94072 3.63916 7.99903 3.63916C7.45829 3.63916 7.00988 3.8492 6.7075 4.24427C6.40476 4.63934 6.24512 5.21907 6.24512 5.9224C6.24512 6.62573 6.40476 7.20841 6.7075 7.60053C7.01024 7.99561 7.45829 8.20565 7.99903 8.20565C8.94035 8.20565 9.52045 7.65387 9.56533 6.72358V8.11884H10.7366V3.73406L9.56533 3.91321M9.62676 5.9224C9.62676 6.74344 9.19527 7.27278 8.5313 7.27278C7.8449 7.27278 7.43585 6.76846 7.43585 5.9224C7.43585 5.07341 7.8449 4.56357 8.5313 4.56357C8.86458 4.56357 9.14488 4.69231 9.33837 4.93914C9.52597 5.18303 9.62676 5.52182 9.62676 5.9224Z" fill="#292929" />
                                <path d="M14.1496 3.64002C13.2054 3.64002 12.6282 4.18922 12.5833 5.11657V2.46069L11.4121 2.63984V8.12006H12.5833V6.72481C12.6282 7.6551 13.208 8.20687 14.1496 8.20687C15.2536 8.20687 15.912 7.35236 15.912 5.92363C15.912 4.4949 15.2532 3.64002 14.1496 3.64002ZM13.617 7.27364C12.9501 7.27364 12.5215 6.7443 12.5215 5.92326C12.5215 5.52267 12.6223 5.18352 12.8099 4.94258C13.0034 4.69612 13.2834 4.567 13.617 4.567C14.3034 4.567 14.7125 5.07427 14.7125 5.92584C14.7125 6.76932 14.3034 7.27364 13.617 7.27364Z" fill="#292929" />
                                <path d="M19.0998 3.64002C18.1556 3.64002 17.5784 4.18922 17.5335 5.11657V2.46069L16.3623 2.63984V8.12006H17.5335V6.72481C17.5784 7.6551 18.1581 8.20687 19.0998 8.20687C20.2038 8.20687 20.8622 7.35236 20.8622 5.92363C20.8622 4.4949 20.2038 3.64002 19.0998 3.64002ZM18.5701 7.27364C17.9032 7.27364 17.4747 6.7443 17.4747 5.92326C17.4747 5.52267 17.5755 5.18352 17.7631 4.94258C17.9566 4.69612 18.2365 4.567 18.5701 4.567C19.2565 4.567 19.6656 5.07427 19.6656 5.92584C19.6656 6.76932 19.2565 7.27364 18.5701 7.27364Z" fill="#292929" />
                                <path d="M20.8623 3.72461H22.1119L23.129 8.11196H22.0082L20.8623 3.72461Z" fill="#292929" />
                                <defs>
                                    <linearGradient id="paint0_linear_2077_259" x1="6.6213e-05" y1="5.76313" x2="27.9999" y2="5.76313" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#3BFF9D" />
                                        <stop offset="1" stop-color="#3BFFC8" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                        <div class="h6">Tabby</div>
                        <p class="text-gray_6 text-h6 font-medium">{{__('Split in up to 4 payments')}}</p>
                    </div>
                    <div class="relative">
                        <input id="Tabby" type="radio" value="TABBY"
                            name="payment_type"
                            class="h-5 w-5  border border-gray-light  hover:border-gray-light focus:outline-none mt-15 opacity-0 z-10 relative" selected="selected">
                        <span class="top-0 border-2 w-5 h-5 rounded-full border-bottom border-gray_6 flex items-center justify-center absolute">
                            <span class="hidden">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="0.5" y="0.5" width="19" height="19" rx="9.5" fill="#A986BF" />
                                    <rect x="0.5" y="0.5" width="19" height="19" rx="9.5" stroke="#A986BF" />
                                    <path d="M13.3337 7.5L8.75033 12.0833L6.66699 10" stroke="#001B11" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="tabby  hidden bg-white-gradient  w-full rounded-2xl border border-primary_color_8 p-24-16 mt-2">
                    <div id="tabbyCard"></div>
                    <?php
                    $language = "en";
                    if (session('direction') == 'rtl') {
                        $language = "ar";
                    }
                    ?>
                    <script src="https://checkout.tabby.ai/tabby-card.js"></script>
                    <script>
                        function initializeTabbyCard() {

                            var payment = $('#payment').val();
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
                    </script>
                    <button class="text-dark" type="button" data-tabby-info="installments" data-tabby-price="{{ request('quantity')  *($data->price + $data->tax_total)}}" data-tabby-currency="SAR" id="tabby-button">Click here to know more</button>
                    <script src="https://checkout.tabby.ai/tabby-promo.js"></script>
                    <script>
                        new TabbyPromo({});
                    </script>
                </div>
            </div> -->
            <!-- Tabby  -->
            <!-- Tamara  -->
            <div class="@if(auth()->guard('appuser')->user()->email != "hitarth.rc@gmail.com") hidden @endif mb-4">
                <!-- <div class=" hidden mb-4"> -->
                <div
                    class="bg-gray_f  w-full rounded-2xl border border-primary_color_o10_1 p-24-16 flex justify-between items-center payments ">
                    <div class="flex gap-1">
                        <img src="{{asset('frontAssets/images/Tamara.svg')}}" alt="">
                        <h5 class="h6">{{ __('4 interest - free payments') }}</h5>
                    </div>
                    <div>
                        <input id="TAMARA" type="radio" value="TAMARA"
                            name="payment_type"
                            class="h-5 w-5  border border-gray-light  hover:border-gray-light focus:outline-none mt-15" selected="selected">
                    </div>
                </div>
                <div class="tamara   hidden bg-gray_f  w-full rounded-2xl border border-primary_color_8 p-24-16 mt-2">
                    <h1>test</h1>
                </div>
            </div>
            <!-- Tamara  -->
            @endif
        </div>
        <div
            class="mt-4 col-span-12 lg:col-span-5   rounded-2xl  bg-primary_color_o10_1 bg-opacity-5 border border-primary_color_o10_1   p-2 md:p-4 h-fit">
            <div class="mb-4">
                <h3 class="font-bold text-h5 md:text-h3">{{ __('Order Summary') }}</h3>
            </div>
            <div class="bg-gray_f p-16-16 rounded-lg flex gap-3 items-center border border-primary_color_o10_1">
                <div class="h-8 w-8">
                    <img src="{{ url('images/upload/' . $data->event->image) }}"
                        alt="event img" class="rounded-lg w-full h-full object-cover">
                </div>
                <div class="">
                    <h5 class="">
                        {{ $lang == 'ar' ? $data->event->name_arabic : $data->event->name }}
                    </h5>
                </div>
            </div>
            <div class="mt-4  bg-gray_f   rounded-lg  border border-primary_color_o10_1 p-16-16 ">
                <h4 class="mb-2 text-primary_color_6"> {{ __('Tickets') }}</h4>
                @foreach( $data->ticket_details as $ticket)
                <div class="border-b-1 border-primary_color_o10_1 pb-1">
                    <div
                        class=" f-bri gap-3   w-full  flex justify-between items-center flex-wrap  ">
                        <div class="font-medium"> {{$ticket->name}}</div>
                        <div
                            class=" f-bri   me-auto font-medium">
                            X {{$ticket->selected_quantity}}
                        </div>
                        <div class="f-bri font-medium">{{ $ticket->price }} {{$data->currency}}</div>
                    </div>
                    <div>
                        <div class="font-medium text-gray_b text-h7"> {{request('slot-event-date')}}</div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <div
                    class="flex items-center    py-3 px-2 border border-dashed border-primary_color_5 rounded-lg border">
                    <input type="text" value="" name="coupon_code" id="coupon_id" placeholder="{{__('Promo code')}}"
                        class=" w-full focus:border-primary_color_6 outline-0  bg-transparent  " name="" id="">
                    <button type="button" id="apply" name="apply" class="rounded-lg bg-primary_color_8 py-1 px-4 text-center  h6 ">{{ __('Apply') }}
                    </button>
                </div>
                <div class="couponerror"></div>
            </div>
            <div class="mt-4 bg-gray_f p-16-16 rounded-lg  border border-primary_color_o10_1">
                <h4 class="mb-2 text-primary_color_6"> {{__('Transaction Details')}}</h4>
                <ul class="">

                    <li class="mb-2 flex justify-between  ">
                        <span class="h7"> {{ __('Promo code') }}</span>
                        <span class="text-green h6 discount">-0 {{$data->currency}} </span>
                    </li>
                    <li class="mb-2 flex justify-between  ">
                        <span class="h7"> {{ __('Tickets Amount') }}</span>
                        <span class=" h6  ticket-price-amount ">

                            {{$ticket_amount}}
                            {{$data->currency}}
                        </span>
                    </li>
                    <li class="mb-2 flex justify-between  ">
                        <span class="h7"> {{ __('Tax amount') }}</span>
                        <span class="h6 totaltax tax_total ">
                            {{ $data->tax_total }} {{ __($currency) }}
                        </span>
                    </li>

                    <li class="mb-2 flex justify-between  ">
                        <span class="h7"> {{ __('Service fee') }}</span>
                        <span class="h6">0 {{$data->currency}}</span>
                    </li>
                    <li class="mb-2 flex justify-between   font-bold  ">
                        <span class="h7"> {{ __('Total') }} </span>
                        <span class="h6  payment">{{$data->total_amount}} {{$data->currency}}</span>
                    </li>
                </ul>
            </div>
            <div class="mt-3">
                <button type="submit" id="EDAFPAY_form_submit"
                    class=" form_submit font-poppins mt-4 py-2 rounded-lg bg-primary_color_8 text-white  w-full block text-center">
                    <div id="formtext">
                        {{ __('Pay Now') }} <span class="  ticket-price-amount">{{$data->total_amount}} {{$data->currency}} </span>
                    </div>
                    <div id="formloader"
                        class="hidden mx-auto animate-spin rounded-full border-t-2 border-blue-500 border-solid h-7 w-7">
                    </div>
                </button>
                <button type="submit" id="TABBY_form_submit"
                    class="hidden form_submit font-poppins mt-4 py-2 rounded-lg bg-primary_color_8 text-white  w-full block text-center">
                    <div id="formtext">
                        {{ __('Pay Now') }} <span>{{$data->total_amount}} {{$data->currency}} </span>
                    </div>
                    <div id="formloader"
                        class="hidden mx-auto animate-spin rounded-full border-t-2 border-blue-500 border-solid h-7 w-7">
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>















<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="{{asset('frontend/js/payment-card.js')}}"></script>
<script src="https://applepay.cdn-apple.com/jsapi/v1/apple-pay-sdk.js"></script>
<script>
    $(document).ready(function() {
        $('#cd-numbergnate input[type="text"]').on('input', function() {
            var fullNumber = $(this).val().replace(/\s+/g, ''); // Remove any existing spaces

            // Add spaces between each group of four digits
            var formattedNumber = fullNumber.match(/.{1,4}/g)?.join(' ') || fullNumber;

            // Set the formatted value back to the visible text input
            $(this).val(formattedNumber);

            // Split the input into chunks of four digits
            var num1 = fullNumber.substring(0, 4);
            var num2 = fullNumber.substring(4, 8);
            var num3 = fullNumber.substring(8, 12);
            var num4 = fullNumber.substring(12, 16);

            // Set the values into the hidden inputs
            $('#cd-number-1').val(num1);
            $('#cd-number-2').val(num2);
            $('#cd-number-3').val(num3);
            $('#cd-number-4').val(num4);
        });
    });



    $(document).ready(function() {
        $('input[name="payment_type"]').change(function() {
            $('input[name="payment_type"]').each(function() {
                if ($(this).is(':checked')) {
                    $(this).siblings('span').find('span').removeClass('hidden');
                } else {
                    $(this).siblings('span').find('span').addClass('hidden');
                }
            });
        });
    });


    // expiry-date input
    $(document).ready(function() {
        $('#expiry-date').on('input', function() {
            var value = $(this).val().replace(/\D/g, ''); // Remove non-digit characters
            if (value.length > 0 && (value[0] !== '0' && value[0] !== '1')) {
                value = value.slice(1); // Ensure the first digit is 0 or 1
            }
            if (value.length > 1 && parseInt(value.slice(0, 2)) > 12) {
                value = value.slice(0, 1); // Ensure the first two digits do not exceed 12
            }
            if (value.length > 2) {
                value = value.slice(0, 2) + '/' + value.slice(2); // Add slash after first two digits
            }
            $(this).val(value);

            // Split the input into month and year
            var parts = value.split('/');
            if (parts.length === 2) {
                $('#month').val(parts[0]);
                $('#year').val(parts[1]);
            }
        });
    });

    $(document).ready(function() {
        $('.fields input').on('input', function() {
            let currentField = $(this);
            let nextField = currentField.next('input');

            if (currentField.val().length === 4) {
                nextField.prop('disabled', false).focus();
            }
        });

        $('.fields input').on('keydown', function(e) {
            let currentField = $(this);
            let prevField = currentField.prev('input');

            if (e.key === 'Backspace' && currentField.val().length === 0) {
                currentField.prop('disabled', true);
                prevField.focus();
            }
        });

        $('#cd-number-1').focus();
    });

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
                        }, 3000);
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
                        $('#errorMessages').append('<p>' + messages[0] + '</p>');
                    });
                }

            });
        });
    });
</script>

<script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check for Apple Pay availability

            const applePayButton = document.getElementById('applePayButton'); 


            if (window.ApplePaySession && ApplePaySession.canMakePayments()) { 
                // Event listener for the Apple Pay button

        //         const button = document.createElement('apple-pay-button');
        // button.setAttribute('buttonstyle', 'white'); // White button
        // button.setAttribute('type', 'buy'); // Can be 'plain' or 'buy'
        // button.style.width = "100%"; // Ensure full width
        // button.style.height = "44px"; // Ensure appropriate height
        
        // // Append the button to the applePayButton div
        // applePayButton.appendChild(button);



        const button = document.createElement('apple-pay-button');
        button.setAttribute('buttonstyle', 'white'); // White button
        button.setAttribute('type', 'buy'); // Can be 'plain' or 'buy'
        button.id = 'applePayActualButton';
        button.style.width = "100%";
        button.style.height = "44px"; 
        
        applePayButton.appendChild(button);

        button.addEventListener('click', function () { 

 


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
                            body: JSON.stringify({
                                token: payment.token,
                                payment_type: 'ApplePay',
                                amount: $('#payment').val(),
                                event_id: $('#event_id').val(),
                                ticket_id: $('#ticket_id').val(),
                                payment: $('#payment').val(),
                                tax: $('#tax_total').val(),
                                coupon_id: $('#coupon_id').val(),
                                slot_event_date: $("#slot-event-date").val(),
                                time_slot_id: $("#time_slot_id").val(),
                                selectedSeatsId: $('#selectedSeatsId').val(),
                                selectedSeats: $('#selectedSeats').val(),
                                quantity: $('#event-quantity').val(),
                                email: $('#email').val(),
                                name: $('#name').val(),
                                phone: $('#phone').val(),
                                totalAmountTax: $('#totalAmountTax').val(),
                                totalPersTax: $('#totalPersTax').val(),
                                flutterwave_key: $('#flutterwave_key').val(),
                                is_login: $('#is-login').val(),
                                razor_key: $('#razor_key').val(),
                                stripePublicKey: $('#stripePublicKey').val(),
                                tpo: $('#tpo').val(),
                                ticket_ids: $('#ticket_ids').val(),
                                ticket_quantities: $('#ticket_quantities').val(),
                                available: $('#available').val(), 
                                tax_total_price: $('#tax_total_price').val(),
                                ticket_total: $('#ticket_total').val(),
                                stripe_payment: $('#stripe_payment').val(),
                                currency_code: $('#currency_code').val(),
                                currency: $('#currency').val(),
                                payment_token: $('#payment_token').val(),
                                coupon_discount: $('#coupon_discount').val(),
                                ticket_price_amount: $('#ticket-price-amount').val(),
                                add_ticket: $('input[name="add_ticket"]').val(),
                                ticketname: $('#ticketname').val(),
                            }
)
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


<script>
                // Function to detect Safari browser
                function isSafari() {
                    const userAgent = navigator.userAgent;
                    const isSafariBrowser = /Safari/.test(userAgent) && !/Chrome/.test(userAgent);
                    return isSafariBrowser;
                }

                // Show the entire mb-4 container if the browser is Safari
                if (isSafari()) {
                    document.getElementById('applePayContainer').style.display = 'block';
                } else {
                    document.getElementById('applePayContainer').style.display = 'none';
                }
            </script>
@endsection