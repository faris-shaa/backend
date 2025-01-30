<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        crossorigin="anonymous">
</head>

<body>
    <?php $primary_color = \App\Models\Setting::find(1)->primary_color; ?>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        .margintop {
            margin-top: 20px;
        }

        table#custometable {
            border: 0ch !important;
            width: 50%;

        }

        #custometable td {
            padding: 5px;
            border: 0px;
        }

        .specialchar {
            font-family: "DejaVu Sans Mono",
                monospace;
        }

        :root {
            --primary_color: <?php echo $primary_color; ?>;
            --light_primary_color: <?php echo $primary_color . '1a'; ?>;
            --middle_light_primary_color: <?php echo $primary_color . '85'; ?>;
        }
        .event-image {
            float: left;
            width: 200px;
            height: 200px;
            margin-right: 20px;
        }

        .qr-code {
            float: right;
            margin-left: 20px;
            text-align: right;
        }

        .media-body {
            clear: both;
        }
        /* Footer Styles */
        .footer {
            text-align: center;
            position: absolute;
            bottom: 0;
            width: 100%;
            margin-top: 30px;
            font-size: 12px;
            color: #6c757d;
        }

        .footer a {
            color: #6c757d;
            text-decoration: none;
        }
        /* Terms Section */
        .terms {
            /* page-break-before: always;  */
            margin-top: 30px;
            font-size: 12px;
            line-height: 1.6;
        }

        .terms p {
            text-align: justify;
            color: #6c757d;
        }
    </style>
    <div class="main-wrapper">
        <div class="p-5">
            <div class="section-body">
                <div class="invoice">
                    <div class="invoice-print">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="invoice-title">

                                <img src="https://ticketby.co/images/upload/6667236ab15c6.png" style="width:200px;height:70px;">
                                </div>
                                
                                <hr>
                                <div class="invoice-title">

                                    <h5 style="color: #6c757d">{{ __('Order') }} {{ $order->order_id }}</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 text-left">
                                        <address>
                                            <strong style="color: #6c757d">{{ __('Event') }}:</strong>
                                        </address>
                                        <!-- <div class="media">
                                            <div class="row">

                                                <div class="col-md-12">

                                                    <img alt="image" class="mr-3"
                                                        src="{{ public_path('images/upload/' . $order->event->image) }}"
                                                        width="200" height="200">
                                                    <div style="margin-left: 420px; top:50px">
                                                        @foreach ($order->ticket_data as $item)
                                                            <?php
                                                            $qr = QrCode::format('png')
                                                                ->size(150)
                                                                ->generate($item->ticket_number);
                                                            ?>
                                                            <img src="data:image/png;base64,{{ base64_encode($qr) }}"
                                                                alt="QR Code">
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="event-details">
                                            <div class="event-image">
                                                <img alt="image" src="{{ public_path('images/upload/' . $order->event->image) }}" width="200" height="200">
                                            </div>
                                            <div class="qr-code">
                                                @foreach ($order->ticket_data as $item)
                                                    <?php
                                                    $qr = QrCode::format('png')
                                                        ->size(150)
                                                        ->generate($item->ticket_number);
                                                    ?>
                                                    <img src="data:image/png;base64,{{ base64_encode($qr) }}" alt="QR Code">
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="media-body" style="margin-top:40px; margin-bottom:40px;">
                                            <div class="media-title mb-0">
                                                {{ $order->event->name }}
                                            </div>
                                            <div class="media-description text-muted">
                                                {{ $order->event->start_time->format('l') . ', ' . $order->event->start_time->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="row">

                                <div class="col-md-6 text-md-right" style="margin-left: 12px">
                                    <address>
                                        <strong>{{ __('Organizer') }}:</strong><br>
                                        <div style="color: #6c757d">
                                            {{ $order->organization->first_name . ' ' . $order->organization->last_name }}<br>
                                            {{ $order->organization->email }}<br>
                                            {{ $order->organization->country }}
                                        </div>

                                    </address>
                                </div>
                            </div>
                            <!-- event location -->
                            @if($order->event_id == 220)
                            <div class="row">
                                <div class="col-md-6 text-md-right" style="margin-left: 12px">
                                    <address>
                                        <strong>{{ __('Location') }}:</strong><br>
                                        <div style="color: #6c757d">
                                            <a href="https://maps.app.goo.gl/W48cJZeLe7rDBx5Q8?g_st=ic">https://maps.app.goo.gl/W48cJZeLe7rDBx5Q8?g_st=ic</a>
                                        </div>

                                    </address>
                                </div>
                            </div>
                            @endif
                            <!-- event location -->
                            <div class="row">
                                <div class="col-md-6" style="margin-left: 12px">
                                    <address>
                                        <strong>{{ __('Attendee') }}:</strong><br>
                                        <div style="color: #6c757d">
                                            @if(isset($pos_order) && isset($pos_order->customeremail))
                                            {{ $pos_order->customername }}<br>
                                            {{ $pos_order->customeremail }}<br>
                                            @else
                                            {{ $order->customer->name . ' ' . $order->customer->last_name }}<br>
                                            {{ $order->customer->email }}<br>
                                            @endif
                                            
                                        </div>
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right" style="margin-left: 12px">
                                    <address>
                                        <strong>{{ __('Order Date') }}:</strong><br>
                                        <div style="color: #6c757d">
                                            {{ $order->created_at->format('d F, Y') }}<br><br>
                                        </div>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 ">
                            <div class="section-title">{{ __('Order Summary') }}</div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md border-0">
                                    <th>#</th>
                                    <th class="text-center ">{{ __('Ticket Name') }}</th>
                                    <th class="text-center">{{ __('Ticket Number') }}</th>
                                    <th class="text-center">{{ __('Price') }}</th>
                                     @php
                                    $subtotal = 0 ; 
                                    @endphp
                                    @foreach ($order->ticket_data as $item)
                                    @php
                                   
                                    $ticket_name = App\Models\Ticket::where('id', $item->ticket_id)->first();
                                     $subtotal = $subtotal + $ticket_name->price ; 
                                    @endphp 
                                        <tr>
                                            <td><small>{{ $loop->iteration }}</small></td>
                                            <td class="text-center"><small>{{ $ticket_name->name }}</small></td>
                                            <td class="text-center"><small>{{ $item->ticket_number }}</small></td>
                                            <td class="text-center ">
                                                <small>{{  $ticket_name->price }} SAR</small>
                                            </td>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    @if ($order->payment_type != 'FREE')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="section-title">{{ __('Payment Method') }}</div>
                                <address>
                                    <strong>{{ $order->payment_type }}</strong><br>
                                    <span
                                        class="badge mt-1 mb-1  'badge-success' "> __('Paid') </span><br>
                                    <!-- <span
                                        class="badge mt-1 mb-1 {{ $order->payment_status == 1 ? 'badge-success' : 'badge-warning' }}">{{ $order->payment_status == 1 ? __('Paid') : __('Waiting') }}</span><br> -->
                                    {{ __('Token:') }}
                                    {{ $order->payment_token == null ? '-' : $order->payment_token }}<br>
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">

                                <table class="table" id="custometable"
                                    style="margin-bottom: 1rem;color: #212529;border-collapse: collapse; margin-left:45%; margin-top:-105px">
                                    <tr>
                                        <td class="small">
                                            {{ __('Subtotal') }}</td>
                                        <td class="small  ">
                                            {{ ($order->payment + $order->coupon_discount - $order->tax) }} SAR
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="small ">
                                            {{ __('Coupon Discount') }}</td>
                                        <td class="small  ">
                                            (-)
                                            {{ $order->coupon_discount }} SAR
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="small">
                                            {{ __('Tax') }}</td>
                                        <td class="small ">
                                            {{  $order->tax }} SAR
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="small">
                                            {{ __('Total') }}</td>
                                        <td class="small ">
                                            {{  $order->payment }} SAR
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Terms Section on a New Page -->
        @if(isset($order->org_details->terms_english))
        <div class="terms">
            <p>{!! $order->org_details->terms_english !!}</p>
        </div>
        @endif
        <!-- Footer with Phone Number and Email -->
        <div class="footer">
            <p>Phone: 556046094</p>
            <p>Email: <a href="mailto:{{ $order->organization->email }}">info@ticketby.co</a></p>
        </div>
    </div>

</body>

</html>
