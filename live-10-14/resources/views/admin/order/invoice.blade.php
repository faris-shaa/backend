@extends('master')

@section('content')
    <section class="section">
        @include('admin.layout.breadcrumbs', [
        'title' => __('Order Detail'),
        'headerData' => __('Orders') ,
        'url' => 'orders' ,
        ])

        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <h3>{{ __('Order') }} {{ $order->order_id }}</h3>
                                <div class="invoice-number">
                                    <a class="btn btn-primary" target="_blank"
                                        href="{{ url('order-invoice-print/' . $order->id) }}"><i class="fas fa-download"
                                            id="print_invoice"></i>{{ __('Print') }}</a>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <address  class="remove-mb">
                                        <strong>{{ __('Event') }}:</strong>
                                    </address>
                                    <div class="media mb-3">
                                        <img alt="image" class="mr-3"
                                            src="{{ url('images/upload/' . $order->event->image) }}" width="50"
                                            height="50">
                                        <div class="media-body">
                                            <div class="media-title mb-0">
                                                {{ $order->event->name }}
                                            </div>
                                            <div class="media-description text-muted">
                                                {{ $order->event->start_time->format('l') . ', ' . $order->event->start_time->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6 text-md-right">
                                    @if (Auth::user()->hasRole('admin'))
                                        <address>
                                            <strong>{{ __('Organizer') }}:</strong><br>
                                            {{ $order->organization->first_name . ' ' . $order->organization->last_name }}<br>
                                            {{ $order->organization->email }}<br>
                                            {{ $order->organization->country }}
                                        </address>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">

                                    <address>
                                        <strong>{{ __('Attendee') }}:</strong><br>
                                        {{ $order->customer->name . ' ' . $order->customer->last_name }}<br>
                                        {{ $order->customer->phone }}<br>
                                        {{ $order->customer->email }}<br>
                                    </address>


                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>{{ __('Order Date') }}:</strong><br>
                                        {{ $order->created_at->format('d F, Y') }}<br><br>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="section-title">{{ __('Order Summary') }}</div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">{{ __('Ticket Name') }}</th>
                                        <th class="text-center">{{ __('Ticket Number') }}</th>
                                        <th class="text-center">{{ __('Price') }}</th>
                                        <th class="text-center">{{ __('Code') }}</th>
                                    </tr>
                                    @php
                                    $subtotal = 0 ; 
                                    @endphp
                                    @foreach ($order->ticket_data as $item)
                                    @php
                                    $subtotal = $subtotal + $order->ticket->price ; 
                                    @endphp

                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $order->ticket->name }}</td>
                                            <td class="text-center">{{ $item->ticket_number }}</td>
                                            <td class="text-center">
                                            @if (session('direction') == 'rtl')
                                            {{$order->ticket->price ." " .   $currency   }}
                                            @else
                                            {{  $order->ticket->price  ." " . __($currency)}}
                                            @endif</td>
                                            <td class="text-center"><a href="{{ url('get-code/' . $item->id) }}"><i
                                                        class="fas fa-print"></i></a></td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-8">
                                    <div class="section-title">{{ __('Payment Method') }}</div>
                                    <address>
                                        <strong>{{ $order->payment_type }}</strong><br>
                                        @if ($order->payment_type != 'FREE' && $order->payment_type != 'LOCAL')
                                            <span
                                                class="badge mt-1 mb-1 {{ $order->payment_status == 1 ? 'badge-success' : 'badge-warning' }}">{{ $order->payment_status == 1 ? 'Paid' : 'waiting' }}</span><br>
                                            <!-- {{ __('Token:') }} {{ $order->payment_token == null ? '-' : $order->payment_token }}<br> -->
                                        @endif
                                    </address>
                                </div>
                                @if (session('direction') == 'rtl')
                                <div class="col-lg-4 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">{{  $subtotal }}</div>
                                        <div class="invoice-detail-value" >
                                        {{ __('Subtotal') }}  
                                        </div>
                                    </div>
                                    <br>
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">(-) {{   round(((  $subtotal * $order->coupon_discount ) / 100  ), 2) ." " . $currency  }}</div>
                                        <div class="invoice-detail-value">{{ __('Coupon Discount') }}
                                        </div>
                                    </div>
                                    <br>
                                    @foreach ($order->tax_data as $item)
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">(+) {{  $item->price ." " . $currency }}</div>
                                            <div class="invoice-detail-value">{{ $item->taxName }}</div>
                                        </div>
                                        <br>
                                    @endforeach
                                    
                                    
                                    <hr class="mt-2 mb-2">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">{{  $order->payment ." " . $currency  }}</div>
                                        <div class="invoice-detail-value invoice-detail-value-lg">
                                        {{ __('Total') }}</div>
                                    </div>
                                </div>
                                @else
                                <div class="col-lg-4 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">{{ __('Subtotal') }}</div>
                                        <div class="invoice-detail-value"> 
                                            @if (session('direction') == 'rtl')
                                                {{ $currency ." " . $subtotal }}
                                            @else
                                            {{  $subtotal ." " .  __($currency ) }}
                                            @endif
                                            
                                        </div>
                                    </div>
                                   
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">{{ __('Coupon Discount') }}</div>
                                        <div class="invoice-detail-value">(-) 
                                        @if (session('direction') == 'rtl')
                                        {{ $currency ."  " .    round(((  $subtotal * $order->coupon_discount ) / 100  ), 2)}}
                                            @else
                                            {{   round(((  $subtotal * $order->coupon_discount ) / 100  ), 2)." " . __($currency) }}
                                            @endif
                                        </div>
                                    </div>
                                     <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">{{ __('Tax') }}</div>
                                        <div class="invoice-detail-value">(+) 
                                        @if (session('direction') == 'rtl')
                                        {{ $currency ."  " . $order->tax }}
                                            @else
                                            {{  $order->tax ." " . __($currency) }}
                                            @endif
                                        </div>
                                    </div>

                                    @foreach ($order->tax_data as $item)
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">{{ $item->taxName }}</div>
                                            <div class="invoice-detail-value">(+) 
                                            @if (session('direction') == 'rtl')
                                            {{ $currency ."  " . $item->price }}
                                            @else
                                            {{ $item->price ." " . __($currency) }}
                                            @endif</div>
                                        </div>
                                    @endforeach
                                    <hr class="mt-2 mb-2">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">{{ __('Total') }}</div>
                                        <div class="invoice-detail-value invoice-detail-value-lg">
                                        @if (session('direction') == 'rtl')
                                            {{ $currency ."  " . $order->payment  }}
                                            @else
                                            {{ $order->payment ." " . __($currency) }}
                                            @endif</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
@endsection
