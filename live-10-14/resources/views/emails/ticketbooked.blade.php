
<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2" >
  <div style="margin:50px auto;width:70%;padding:20px 0">
    <div style="border-bottom:1px solid #eee">
      <a href="https://ticketby.co/" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600"><img style="height:40px" src="https://ticketby.co/images/upload/6667236ab15c6.png"></a>
    </div>
  
    <!-- <p style="font-size:1.1em">Hi, {{$user->name}} {{$user->last_name}} </p>
    <p>your 1 ticket booked for {{$event->name }} on {{$event->start_time}} successfully.</p> -->
    <p>Dear {{$user->name}} {{$user->last_name}},
 
Thank you for your booking with TicketBy! We are excited to have you at our upcoming event.
Attached is your QR code, which you will need to present at the entrance for a smooth check-in.
For any assistance or if you have any questions, please don't hesitate to contact our technical support team at +966556046094
We look forward to seeing you there!
 
 
Best regards,
TicketBy</p>

    <img src="https://ticketby.co/qrcodes/qr-{{$qr_id}}.png">

    <p style="font-size:0.9em;">Regards,<br />TicketBy</p>
    <hr style="border:none;border-top:1px solid #eee" />
    <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
      <p>TicketBy</p>
      <!-- <p>1600 Amphitheatre Parkway</p>
      <p>California</p> -->
    </div>
  </div>
</div>
