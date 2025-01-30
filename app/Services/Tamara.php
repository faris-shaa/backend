<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class Tamara
{
 /*   public $base_url = "https://api.tabby.ai/api/v2/";
    public $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhY2NvdW50SWQiOiI2ZmNmZjFmYS1iMzg5LTQ5MTctYjI3YS1iYjdlMzAxZjk2OTciLCJ0eXBlIjoibWVyY2hhbnQiLCJzYWx0IjoiNzY4YWIwZmY3YzhkYWUxYThjMDI2NDFhMDdjNzMxNmEiLCJyb2xlcyI6WyJST0xFX01FUkNIQU5UIl0sImlhdCI6MTcwMTgwODYzOCwiaXNzIjoiVGFtYXJhIn0.RYGdibYsCDAVrnZG5InagT4Whg_A7ZUqO5zhzzo5Fnqm7wbWCTkADLqN_r_ZyIuKXcYcLbiNo0im8xx7p5guCcCN6XovBRm59gfYhRd9eybNrGrLBkrB-g2E0I5lntyPeNHM0W1XWVgOXGEcz58DSl7hrB8e-TkFAa6xIXO4-XQlOXb_598Xp8LLKlBFzr8v5YfM4V05bGwmziXb15lZjSwpBqp5jUhIQgrdp0z1vG4X1zj7qlQDVQaEBS-ffd9mGkYxpN5Udu0WCCKdtbRZKZIKVnpeSj_pvdCYUYcc4kF3GX5JSxNxKdOCrysbfIuvjylfiv41fUPcUgx9GlVtQQ
";*/

    public $base_url = "https://api-sandbox.tamara.co";
    public $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhY2NvdW50SWQiOiI3ZjJjOTYwOS1kOTIwLTRkMzItOGQ1Zi1mM2YwYTFkZjg5MjgiLCJ0eXBlIjoibWVyY2hhbnQiLCJzYWx0IjoiZTI4NjFlY2YxYjk2ZGNjZGJlOTIzYzVkMTNkZmI5M2YiLCJyb2xlcyI6WyJST0xFX01FUkNIQU5UIl0sImlhdCI6MTczMTA1MDQwMSwiaXNzIjoiVGFtYXJhIn0.V7R3nuJXM_insVHnfpCnKHRWon4o-mNwpFWV7kMPmiDm5awK-MTkbaNYpuQAU2wnDLLwAUaIG0UEQQMbbj3Mzz7jMd1CF68jpIM8-c_yWi55VvYVTNV0m06G9cEUISdRCy9O-Y5EsKAHCrWGh7HFf-G-VijCwxPNlz8Dq5AgNhkulQHh6L0s_zmKvsxxZXeGQgtiMa2KLkph24FUms-tU5AeVfQ7nEcdi1PglnF3APqlZSrzhmEdGl2eMNgibGKIcHddLI8EPa1lM5xihx7wTi4jAnGrobLKiftx2_sd6HhYxUcmi2VRtQegjmVcFvYELeUx5jaH0ygip0jY7M0pDA  
";    

    public function makeCurlCall ($data)
    {
     

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api-sandbox.tamara.co/checkout');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
//dd($data['payment']);
$order_id = "#49943";
$data = [
    "total_amount" => ["amount" => $data['payment'] , "currency" => "SAR"],
    "shipping_amount" => ["amount" => 0, "currency" => "SAR"],
    "tax_amount" => ["amount" => 0, "currency" => "SAR"],
    "order_reference_id" => "2272",
    "order_number" => $order_id,
    "discount" => ["name" => "Voucher A", "amount" => ["amount" => 0, "currency" => "SAR"]],
    "items" => [
        [
            "name" => "Lego City 8601",
            "type" => "Digital",
            "reference_id" => "123",
            "sku" => "SA-12436",
            "quantity" => 1,
            "discount_amount" => ["amount" => 100, "currency" => "SAR"],
            "tax_amount" => ["amount" => 10, "currency" => "SAR"],
            "unit_price" => ["amount" => 490, "currency" => "SAR"],
            "total_amount" => ["amount" => 100, "currency" => "SAR"]
        ]
    ],
    "consumer" => [
        "email" => $data['user']['email'],
        "first_name" => $data['user']['name'],
        "last_name" => $data['user']['last_name'],
        "phone_number" => str_replace('+966', '', $data['user']['phone'])
    ],
    "country_code" => "SA",
    "description" => "Enter order description here.",
    "merchant_url" => [
        "cancel" => env('APP_URL')."failed",
        "failure" =>env('APP_URL')."failed",
        "success" => env('APP_URL')."thankyou",
        "notification" => "https://ticketby.co/tamara-notification"
    ],
    "payment_type" => "PAY_BY_INSTALMENTS",
    "instalments" => 3,
    "billing_address" => [
        "city" => "Riyadh",
        "country_code" => "SA",
        "first_name" => "Mona",
        "last_name" => "Lisa",
        "line1" => "3764 Al Urubah Rd",
        "line2" => "string",
        "phone_number" => "532298658",
        "region" => "As Sulimaniyah"
    ],
    "shipping_address" => [
        "city" => "Riyadh",
        "country_code" => "SA",
        "first_name" => "Mona",
        "last_name" => "Lisa",
        "line1" => "3764 Al Urubah Rd",
        "line2" => "string",
        "phone_number" => "532298658",
        "region" => "As Sulimaniyah"
    ],
    "platform" => "platform name here",
    "is_mobile" => false,
    "locale" => "ar_SA",
    "risk_assessment" => [
        "customer_age" => 21,
        "customer_dob" => "01-12-2000",
        "customer_gender" => "Female",
        "customer_nationality" => "SA",
        "is_premium_customer" => false,
        "is_existing_customer" => false,
        "is_guest_user" => false,
        "account_creation_date" => "12-06-2020",
        "platform_account_creation_date" => "12-06-2020",
        "date_of_first_transaction" => "12-06-2020",
        "is_card_on_file" => false,
        "is_COD_customer" => false,
        "has_delivered_order" => true,
        "is_phone_verified" => false,
        "is_fraudulent_customer" => false,
        "total_ltv" => 200,
        "total_order_count" => 15,
        "order_amount_last3months" => 2000,
        "order_count_last3months" => 10,
        "last_order_date" => "12-06-2020",
        "last_order_amount" => 2000,
        "reward_program_enrolled" => false,
        "reward_program_points" => 2000
    ],
    "additional_data" => [
        "delivery_method" => "Home Delivery",
        "pickup_store" => "Store A",
        "store_code" => "Branch A",
        "vendor_amount" => 0,
        "merchant_settlement_amount" => 0,
        "vendor_reference_code" => "AZ1234"
    ]
];

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhY2NvdW50SWQiOiI3ZjJjOTYwOS1kOTIwLTRkMzItOGQ1Zi1mM2YwYTFkZjg5MjgiLCJ0eXBlIjoibWVyY2hhbnQiLCJzYWx0IjoiZTI4NjFlY2YxYjk2ZGNjZGJlOTIzYzVkMTNkZmI5M2YiLCJyb2xlcyI6WyJST0xFX01FUkNIQU5UIl0sImlhdCI6MTczMTA1MDQwMSwiaXNzIjoiVGFtYXJhIn0.V7R3nuJXM_insVHnfpCnKHRWon4o-mNwpFWV7kMPmiDm5awK-MTkbaNYpuQAU2wnDLLwAUaIG0UEQQMbbj3Mzz7jMd1CF68jpIM8-c_yWi55VvYVTNV0m06G9cEUISdRCy9O-Y5EsKAHCrWGh7HFf-G-VijCwxPNlz8Dq5AgNhkulQHh6L0s_zmKvsxxZXeGQgtiMa2KLkph24FUms-tU5AeVfQ7nEcdi1PglnF3APqlZSrzhmEdGl2eMNgibGKIcHddLI8EPa1lM5xihx7wTi4jAnGrobLKiftx2_sd6HhYxUcmi2VRtQegjmVcFvYELeUx5jaH0ygip0jY7M0pDA',
]);

$response = curl_exec($ch);


$responseData = json_decode($response, true);

$update_order_id = Order::where('order_id',$order_id)->update(['tamara_order_id'=>$responseData['order_id']]) ;
return $responseData['checkout_url'];


    } 

    public function getSession($payment_id)
    {
        $http = Http::withToken($this->sk_test)->baseUrl($this->base_url);

        $url = 'checkout/'.$payment_id;

        $response = $http->get($url);

        return $response->object();
    }

    public function getConfig($data)
    {
        $body= [];

        $body = [
            "payment" => [
                "amount" => $data['amount'],
                "currency" => $data['currency'],
                "description" =>  $data['description'],
                "buyer" => [
                    "phone" => $data['buyer_phone'],
                    "email" => $data['buyer_email'],
                    "name" => $data['full_name']
                ],
                "shipping_address" => [
                    "city" => $data['city'],
                    "address" =>  $data['address'],
                    "zip" => $data['zip'],
                ],
                "order" => [
                    "tax_amount" => "0.00",
                    "shipping_amount" => "0.00",
                    "discount_amount" => "0.00",
                    "updated_at" => now(),
                    "reference_id" => $data['order_id'],
                    "items" => 
                        $data['items']
                    ,
                ],
                "buyer_history" => [
                    "registered_since"=> $data['registered_since'],
                    "loyalty_level"=> $data['loyalty_level'],
                ],
            ],
            "lang" => app()->getLocale(),
            "merchant_code" => "your merchant_code",
            "merchant_urls" => [
                "success" => env('APP_URL')."thankyou",
                "cancel" => env('APP_URL')."failed",
                "failure" => env('APP_URL')."failed"
            ]
        ];

        return $body;
    }
}
