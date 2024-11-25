
<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2" >
  <div style="margin:50px auto;width:70%;padding:20px 0">
    <div style="border-bottom:1px solid #eee">
      <a href="https://ticketby.co/" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600"><img style="height:40px" src="https://ticketby.co/images/upload/6667236ab15c6.png"></a>
    </div>
  
    <!-- <p style="font-size:1.1em">Hi, {{$user->name}} {{$user->last_name}} </p>
    <p>your 1 ticket booked for {{$event->name }} on {{$event->start_time}} successfully.</p> -->
    <p> Hello
    @if(isset($user->name))
    {{$user->name}} {{$user->last_name}},
    @else
    {{$user->customername}},
    @endif
  </p>


<p>Thank you for booking a ticket for {{$event->name }}  Your ticket is attached to this email, and you can use it for entry.</p>

@if($event->is_repeat == 1)
<p>Date of event is {{$order->event_book_date}} and time is {{$time_slot->start_time}} to {{$time_slot->end_time}} </p>
@endif

<p>To explore upcoming events and manage your bookings with ease, we invite you to download our app here: <a href="https://play.google.com/store/apps/details?id=com.ticketby.app&hl=en_IN"> Click here </a>.</p>


<p>We hope you enjoy the event and have a wonderful experience. If you have any questions, feel free to contact us.</p>


<p>Best regards,</p>


TicketBy


MOBILE NUMBER : +966556046094</p>

  

    <p style="font-size:0.9em;">Regards,<br />TicketBy</p>
    <hr style="border:none;border-top:1px solid #eee" />
    <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
      <p>TicketBy</p>
      <!-- <p>1600 Amphitheatre Parkway</p>
      <p>California</p> -->
    </div>
  </div>
</div>
