@if(isset($language) && $language == "English" )
<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2" >
  <div style="margin:50px auto;width:70%;padding:20px 0">
    <div style="border-bottom:1px solid #eee">
      <a href="https://ticketby.co/" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600"><img style="height:40px" src="https://ticketby.co/images/upload/6667236ab15c6.png"></a>
    </div>
  
    <p style="font-size:1.1em">Hi, </p>
    <p> Ticket has been booked by {{$user->name}} {{$user->last_name}} for {{$event->name }} with payment mode as {{$order->payment_type}}</p>
   
   
    <p style="font-size:0.9em;">Regards,<br />TicketBy</p>
    <hr style="border:none;border-top:1px solid #eee" />
    <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
      <p>TicketBy</p>
      <!-- <p>1600 Amphitheatre Parkway</p>
      <p>California</p> -->
    </div>
  </div>
</div>
@else
<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2; direction:rtl;" >
  <div style="margin:50px auto;width:70%;padding:20px 0">
    <div style="border-bottom:1px solid #eee">
      <a href="https://ticketby.co/" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600"><img style="height:40px" src="https://ticketby.co/images/upload/6667236ab15c6.png"></a>
    </div>
  
    <p style="font-size:1.1em">يا هلابك, </p>
    <p>تم حجز التذكرة بواسطة {{$user->name}} {{$user->last_name}} لحضور {{$event->name}} مع طريقة الدفع {{$order->payment_type}}</p>
 
    
  
    <p style="font-size:0.9em;">Regards,<br />TicketBy</p>
    <hr style="border:none;border-top:1px solid #eee" />
    <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
      <p>TicketBy</p>
      <!-- <p>1600 Amphitheatre Parkway</p>
      <p>California</p> -->
    </div>
  </div>
</div>

@endif