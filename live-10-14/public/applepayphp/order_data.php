<?php

// enable debug
ini_set( 'display_errors', true );
ini_set( 'error_reporting', E_ALL);
error_reporting(E_ALL);

// Constants
$accounts_list = array(
    array( // 0 :  old
        'merchant_key'  => '44afb96c-b69f-11ed-8f30-c26e76afc7c6',
        'password'      => '',
    ),
    array( // 1 :  darlana live
        'merchant_key'  => 'b5abdab4-5c46-11ed-a7be-8e03e789c25f',
        'password'      => 'cdb715a1b482b2af375785d70e8005cd',
    ),
    array( // 2 :  darlana test
        'merchant_key'  => 'b5abd802-5c46-11ed-b679-8e03e789c25f',
        'password'      => 'cdb715a1b482b2af375785d70e8005cd',
    ),
    array( // 3 :  Shahbandr Pay Dev	 live
        'merchant_key'  => '4f6bebde-f892-11ed-b2dd-46933864ec97',
        'password'      => 'bd95b3cdb27c3116996c3cf14663e517',
    ),
    array( // 4 :  Shahbandr Pay Dev	 test
        'merchant_key'  => '4f6be99a-f892-11ed-962e-46933864ec97',
        'password'      => 'bd95b3cdb27c3116996c3cf14663e517',
    )
);
$account = 1;
define( 'merchant_key'      , $accounts_list[$account]['merchant_key'] );
define( 'password'          , $accounts_list[$account]['password'] );

// order data
define( 'number'        , '12345' );
// define( 'amount'        , '1.00' );
// define( 'currency'      , 'SAR' );
define( 'amount'        , '0.01' );
define( 'currency'      , 'SAR' );
define( 'description'   , 'GIFT' );
define( 'identifier'    , '31ECAB922512F9EB5AC08F35C7D7BCBBE8D615639EF483965A06073634CE680E' );
define( 'user_name'     , 'Adnan' );
define( 'user_email'    , 'adnanh@expresspay.sa' );
define( 'hash'          , sha1( md5(strtoupper( number . amount . currency . description . password ))) );
