<?php

require_once ( __DIR__ . '/order_data.php');

function create_session( $url ){
  
  echo '11 - create session in virtula.php<br/>';
  
  $fields = array(
    'merchant_key' => merchant_key,
    'operation' => 'purchase',
    'methods' => array('card' , 'applepay' ),
    'order' => array(
      'number' => number,
      'amount' => amount,
      'currency' => currency,
      'description' => description
    ),
    'cancel_url' => 'https://mywebsite.com/cancel',
    'success_url' => 'https://mywebsite.com/success',
    'customer' => array(
       'name' => 'Mohamed Yassin',
       'email' => 'mhmd.yassin07@gmail.com'
    ),
    'billing_address' => array(
       'country' => 'US',
       'state' => 'CA',
       'city' => 'Los Angeles',
       'address' => 'Moor Building 35274',
       'zip' => '123456',
       'phone' => '347771112233'
    ),
    'recurring_init' => 'true',
    'hash' => hash
  );
  $fields =  json_encode( $fields );

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $fields,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json',
      'Cookie: PHPSESSID=je4hpmt01bsb126tbs5ukqq8u4'
    ),
  ));
  
  $response = curl_exec($curl);
  $response = json_decode( $response , true );

  
  echo "<pre>";
  print_r( $response ) ;  
  echo "</pre>";


  curl_close($curl);
  if( isset( $response['redirect_url'] ) ){
    define( 'token',  str_replace('https://pay.expresspay.sa/auth/' , '', $response['redirect_url'] ) );
  }else {
    print_r( $response );
  }
}


function make_virtual_request( $link, $echo = true ){
  if ($echo ) {
    echo 'make virtual request in virtula.php<br/>';
  }
  $curl = curl_init();
  $fields = array(
    // 'requestname'=> 'virtual',
    'billingAddress'=> array(
      'city' => 'mit ghamr',
      'district' => 'my District',
      'house_number' => '123',
    ),
    'brand' => 'applepay',
    'browserInfo'=> array(
        'colorDepth'=> 32,
        'javaEnabled'=> false,
        'javaScriptEnabled'=> true,
        'language'=> 'en-US',
        'screenHeight'=> 926,
        'screenWidth'=> 428,
        'timeZoneOffset'=> -180,
        'userAgent'=> 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_1_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.1 Mobile/15E148 Safari/604.1',
        'platform'=> 'iPhone'
    ),
    'detail_num' => 2, 
    'details'=> 'details_data',
    'identifier'=> identifier,
    'email'=> user_email,
    'name'=> user_name,
    'hash' => hash
  );
  

  $fields =  json_encode( $fields );
  //$fields = str_replace( '"details_data"', $_REQUEST['paymentToken'], $fields );
  $fields = str_replace( '"details_data"', "\"".escapeJsonString($_REQUEST['paymentToken'])."\"", $fields );

  if ($echo ) {
    echo '****************'.user_email.'***********<br/>';
    // echo $fields . '<br/>';
    echo $_REQUEST['paymentToken'];
    echo '<br/>***************************<br/>';
    echo escapeJsonString($_REQUEST['paymentToken']);
    echo '<br/>***************************<br/>';
    echo $fields;
    echo '<br/>';
  }
  $curl = curl_init();
  curl_setopt_array( $curl, array(
    CURLOPT_URL => $link,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $fields,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json',
      'Cookie: PHPSESSID=avv04pbd41puuvjdloq2nghmfo',
      'Token: '. token,
    ),
  ));

  if ($echo) {
    echo 'testing adnan hashmi dbuging';
  }

  $response = curl_exec($curl);
  $response = json_decode( $response ,  true );

  if( $echo ){
    echo "<pre>";
    print_r( $response ) ;  
    echo "</pre>";
  }
  echo "ssssssssss";
  curl_close($curl);
}

function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
  $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
  $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
  $result = str_replace($escapers, $replacements, $value);
  return $result;
}



// step 1:  create session 
//create_session( 'https://a693762e8cfcbfb363098bcaec4b5e41.m.pipedream.net' );    
create_session( 'https://pay.expresspay.sa/api/v1/session' );
//  step 2: send data to express pay

//make_virtual_request( 'https://67d31278c347da14deb47e296dc1a652.m.pipedream.net' , $echo = false );
//make_virtual_request( 'https://a693762e8cfcbfb363098bcaec4b5e41.m.pipedream.net' , $echo = false );
make_virtual_request( 'https://pay.expresspay.sa/processing/purchase/virtual' );