<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>
    @page {
      size: 8.27in 11.69in;
      margin: 0;
    }

    body {
      font-family: Arial, sans-serif;
      background: #F8F8F8;
      margin: 0;
      padding: 0;
      width: 8.27in;
      height: 11.69in;
      box-sizing: border-box;
      overflow: hidden;
    }

    .ticket-container {
      width: 100%;
      height: 100%;
      max-height: 11.4in;
      border-radius: 10px;
    }

    .header-section {
      margin-bottom: 10px;
      padding: 0 20px;
    }

    .order-number {
      font-weight: bold;
      font-size: 24px !important;
    }

    .electronic-ticket {
      font-size: 20px;
      text-align: center;
      color: #713992;
      font-weight: bold;
    }

    .festival-banner img {
      width: 100%;
      height: 250px;
    }

    .primary-color {
      color: #713992;
    }

    .bold {
      font-weight: bold;
    }

    p {
      margin-bottom: 0;
    }

    .event-title {
      color: #8C8C8C;
      font-size: 14px;
    }

    .event-description {
      font-size: 24px  !important;
      text-decoration:none !important;
      color: #713992;
      font-weight: bold;
    }

    .table-container {
      display: table;
      width: 100%;
    }

    .table-row {
      display: table-row;
    }

    .table-cell {
      display: table-cell;
      vertical-align: middle;
      padding: 5px;
    }

    .footer {
      display: table;
      width: 100%;
      color: #999999;
      margin-top: 20px;
      font-size: 12px;
    }

    .footer span {
      display: table-cell;
    }

    .container {
      padding: 0 20px;
    }

    .outlined-text {
      font-size: 18px;
      color: black;
      position: relative !important;
    }

    .outline-shadow {
      text-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    }

    .outline-stroke {
      -webkit-text-stroke: 1px #000000;
    }

    .outline-layered {
      position: relative;
      color: #333333;
    }

    .outline-layered::before {
      content: attr(data-text);
      position: absolute;
      left: 0;
      top: 0;
      z-index: -1;
      -webkit-text-stroke: 4px black;
      color: transparent;
    }
  </style>
</head>

<body>
  @if(!empty($order->ticket_data))
  @foreach ($order->ticket_data as $item)
  <div class="ticket-container">
    <div class="header-section">
      <div style="display: table; width: 100%;">
        <div style="display: table-row; width: 100%;">
          <div class="" style="display: table-cell; width: 25%; text-align: left;">
            <p class="order-number outlined-text outline-shadow outline-layered outline-stroke">Ticket Number</p>
            <p class="order-number outlined-text outline-shadow outline-layered outline-stroke" style="margin-top:10px">
              {{ $order->order_id }}</p>
          </div>
          <div style="display: table-cell; width: 50%; text-align: center; vertical-align: middle;">
            <p class="electronic-ticket"> {{ $order->event->name }}</p>
          </div>
          <div style="display: table-cell; width: 25%; text-align: right; vertical-align: middle">
            <img src="https://ticketby.co/images/pdf-logo.png"
              style="width:150px; height:50px; margin-top: 20px;" alt="ticketby logo">
          </div>
        </div>
      </div>
    </div>
    <div class="festival-banner text-center">
      @if($order->organization_id ==199)
      <img src="https://ticketby.co/upload/ticket.jpeg" alt="BANNER IMAGE">
      @elseif($order->event_id == 257)
      <img src="https://ticketby.co/upload/nancy.jpeg" alt="BANNER IMAGE">
      @else
      <img src="https://ticketby.co/images/default-pdf-background.png" alt="BANNER IMAGE">
      @endif
    </div>

    <div class="table-container container">
      <div class="table-row">
        <div class="table-cell" style="width: 60%;">
          <p class="bold" style="font-size: 24px; color:#222222; margin-top: 10px;">
            
            @if(isset($pos_order) && isset($pos_order->customeremail))
            {{ $pos_order->customername }}
            @else
            {{ $order->customer->name . ' ' . $order->customer->last_name }}
            @endif
        </p>
          <p class=" primary-color bold" style="font-size: 24px; margin-top: 10px;">{{ $order->event->start_time->format('M d') }} - {{
            $order->event->end_time->format('M d, Y') }}</p>

          <div style=" margin-top: 10px; border-radius: 10px;">
            <div class="table-container">
              @if($order->payment_token)
              <div class="table-row">
                <div class="table-cell bold" style="width: 33%;">Transaction #:</div>
                <div class="table-cell" style="width: 67%;"> {{ $order->payment_token }}</div>
              </div>
              @endif
              <div class="table-row">
                <div class="table-cell bold">Issued On:</div>
                <div class="table-cell"> {{ $order->created_at->format('M d, Y')}}</div>
              </div>
              @if( $order->tax != 0 )
              <div class="table-row">
                <div class="table-cell bold">VAT:</div>
                <div class="table-cell">{{ $order->tax}}</div>
              </div>
              @endif
              <div class="table-row">
                <div class="table-cell bold">Price (inc. VAT):</div>
                <div class="table-cell">{{ $order->payment}}</div>
              </div>
            </div>
            <p class="primary-color bold" style="margin-top: 8px; font-size: 14px;">Credit card used should be presented
              to validate this ticket.</p>
          </div>
          <div style="margin: 10px 0;">
            <p class="event-title">Event Location</p>
            <p class="event-description"><a href="{{ $order->event->address_url }}"  class="event-description">{{ $order->event->address }}</a></p>
            <br>
            <p class="event-title">Ticket Type</p>
            <p class="event-description" style="font-family:noto ;">{{  App\Models\Ticket::where('id', $item->ticket_id)->first()->name }}</p>
          </div>
         
        </div>

        <div class="table-cell" style="width: 40%; text-align: center; vertical-align: top;">
          <?php
            $qr = QrCode::format('png')
                ->size(250)
                ->backgroundColor(248, 248, 248)
                ->generate($item->ticket_number);
          ?>
          <img src="data:image/png;base64,{{ base64_encode($qr) }}" alt="QR Code"
            style="display: block; margin-bottom: 10px; width: 250px; height: 250px;">
        </div>
      </div>
    </div>

    <div class="container">
      <p style="color:#E52828; font-weight: bold; font-size: 12px;">Rules and Regulations</p>
      <p style="color: #222222; font-size: 12px; text-align: justify;">
        <span class="primary-color bold">1.</span>Tickets are non-refundable, non-changeable, and non-transferable.
        <span class="primary-color bold">2.</span>Upon entry, the organizer or the entry
        official has the right to request a valid photo identification document (original), such as a national ID,
        driver's license, or passport, ensuring that the name matches the one on the debit/credit card used for
        purchase.
        <span class="primary-color bold">3.</span>Online ticket resale is prohibited.
        <span class="primary-color bold">4.</span>Customers must ensure they meet the required age to enter the event.
        <span class="primary-color bold">5.</span>Customers must abide by any dress code set by the Event Organizer, if
        applicable.
        <span class="primary-color bold">6.</span>Ticketby is not liable to
        refund tickets if entry is declined by the Event Organizer for any reason.
        <span class="primary-color bold">7.</span>Customers are responsible for
        attending the event at the correct time; non-attended events will not be refunded.
        <span class="primary-color bold">8.</span>Exchanging of seats, tickets,
        or categories is not allowed—one person per ticket.
        <span class="primary-color bold">9.</span>The ticket is valid only for the specified show and cannot
        be reused for multiple events.
        <span class="primary-color bold">10.</span>The Event Organizer reserves the right to refuse entry once the show
        has
        started. No re-entry allowed.
        <span class="primary-color bold">11.</span>The event artist lineup is subject to change at any time.
        <span class="primary-color bold">12.</span>Ticket resale is
        strictly prohibited; tickets purchased by ticket resellers will be canceled and not refunded.
      </p>
    </div>
    <div style="padding: 0 25px;">
      <div class="primary-color bold"
        style="margin: 1.5rem 0; padding: 15px; border-radius: 10px; font-size: 14px; text-transform: uppercase;">
        <p style="margin-top:0px !important;">This is your final ticket, please save it on your mobile and present it to
          the
          concerned team upon check in.
        </p>
      </div>
    </div>
    <div class="footer container">
      <div style="display: table-row;">
        <div style="display: table-cell; width: 33.33%; text-align: left;">info@ticketby.co</div>
        <div style="display: table-cell; width: 33.33%; text-align: center;">www.ticketby.com.sa</div>
        <div style="display: table-cell; width: 33.33%; text-align: right;">instagram.com/ticketbyksa</div>
      </div>
    </div>
  </div>
  @endforeach
  @endif
</body>

</html>