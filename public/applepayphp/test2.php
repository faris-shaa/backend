<?php 
  $fields = array(
    'requestname'=> 'virtual',
    'details'=> 'details_data',
    'billingAddress'=> array(),
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
    'identifier'=> 'identifier',
    'email'=> 'user_email',
    'name'=> 'user_name',
    'hash' => 'hash'
  );




$fields =  json_encode( $fields );
echo $fields . "</br>";

$fields = str_replace( 'details_data', 'naaaaaw', $fields );

echo $fields;
echo "22";