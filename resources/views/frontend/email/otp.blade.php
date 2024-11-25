@if(isset($language) && $language == "English")
<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2" >
  <div style="margin:50px auto;width:70%;padding:20px 0">
    <div style="border-bottom:1px solid #eee">
      <a href="https://ticketby.co/" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600"><img style="height:40px" src="https://ticketby.co/images/upload/6667236ab15c6.png"></a>
    </div>
  
    <p style="font-size:1.1em">Hi, {{$name}}</p>
    <p>Thank you for choosing TicketBy. Use the following OTP to complete your process with Ticketby</p>
   
    <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">{{$otp}}</h2>
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
  
    <p style="font-size:1.1em">يا هلابك, {{$name}}</p>
    <p>شكرًا لك على اختيار TicketBy. استخدم كلمة المرور لمرة واحدة (OTP) التالية لإكمال العملية الخاصة بك مع Ticketby</p>
 
    
    <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">{{$otp}}</h2>
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