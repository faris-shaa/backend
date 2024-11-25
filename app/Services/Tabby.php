<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\AppUser;
class Tabby
{
    /*public $base_url = "https://api.tabby.ai/api/v2/";
    public $pk_test = "pk_test_30283897-1a23-4896-947f-f01ec34e3213";
    public $sk_test = "sk_test_c16bfa5d-d183-4ca2-80b4-b70e400eab4f";*/


    public $base_url = "https://api.tabby.ai/api/v2/";
    public $pk_test = "pk_1a9cc24e-acdb-4516-8ebb-8abb93fe5115";
    public $sk_test = "sk_dc27f5c7-dff3-4196-9530-b549e9badb24";


    public function createSession($data)
    {
        $body = $this->getConfig($data);
        
        $http = Http::withToken($this->sk_test)->baseUrl($this->base_url);

        $response = $http->post('checkout',$body);

        return $response->object();
    }

    public function makeCurlCall ($data)
    {
        
        $curl = curl_init();
         $user = AppUser::where('id',$data['customer_id'])->first();
         
        $data_fields = [
            "payment" => [
                "amount" => $data['payment'],
                "currency" => "SAR",
                "description" => "string",
                "buyer" => [
                    "phone" => "57778874841",
                    "email" => $user->email,
                    "name" => $user->name,
                    "dob" => "2019-08-24"
                ],
                "buyer_history" => [
                    "registered_since" => "2019-08-24T14:15:22Z",
                    "loyalty_level" => 0,
                    "wishlist_count" => 0,
                    "is_social_networks_connected" => true,
                    "is_phone_number_verified" => true,
                    "is_email_verified" => true
                ],
                "order" => [
                    "tax_amount" => "0.00",
                    "shipping_amount" => "0.00",
                    "discount_amount" => "0.00",
                    "updated_at" => "2019-08-24T14:15:22Z",
                    "reference_id" => "string",
                    "items" => [
                        [
                            "title" => "string",
                            "description" => "string",
                            "quantity" => 1,
                            "unit_price" => "0.00",
                            "discount_amount" => "0.00",
                            "reference_id" => "string",
                            "image_url" => "http://example.com",
                            "product_url" => "http://example.com",
                            "gender" => "Male",
                            "category" => "string",
                            "color" => "string",
                            "product_material" => "string",
                            "size_type" => "string",
                            "size" => "string",
                            "brand" => "string"
                        ]
                    ]
                ],
                "order_history" => [
                    [
                        "purchased_at" => "2019-08-24T14:15:22Z",
                        "amount" => "0.00",
                        "payment_method" => "card",
                        "status" => "new",
                        "buyer" => [
                            "phone" => "string",
                            "email" => "user@example.com",
                            "name" => "string",
                            "dob" => "2019-08-24"
                        ],
                        "shipping_address" => [
                            "city" => "string",
                            "address" => "string",
                            "zip" => "string"
                        ],
                        "items" => [
                            [
                                "title" => "string",
                                "description" => "string",
                                "quantity" => 1,
                                "unit_price" => "0.00",
                                "discount_amount" => "0.00",
                                "reference_id" => "string",
                                "image_url" => "http://example.com",
                                "product_url" => "http://example.com",
                                "ordered" => 0,
                                "captured" => 0,
                                "shipped" => 0,
                                "refunded" => 0,
                                "gender" => "Male",
                                "category" => "string",
                                "color" => "string",
                                "product_material" => "string",
                                "size_type" => "string",
                                "size" => "string",
                                "brand" => "string"
                            ]
                        ]
                    ]
                ],
                "shipping_address" => [
                    "city" => "string",
                    "address" => "string",
                    "zip" => "string"
                ],
                "meta" => [
                    "order_id" => "#1234",
                    "customer" => "#customer-id"
                ],
                "attachment" => [
                    "body" => json_encode([
                        "flight_reservation_details" => [
                            "pnr" => "TR9088999",
                            "itinerary" => [],
                            "insurance" => [],
                            "passengers" => [],
                            "affiliate_name" => "some affiliate"
                        ]
                    ]),
                    "content_type" => "application/vnd.tabby.v1+json"
                ]
            ],
            "lang" => "en",
           "merchant_code" => "SA",
            //"merchant_code" => "your merchant_code"
            "merchant_urls" => [
                "success" => env('APP_URL')."thankyou",
                "cancel" => env('APP_URL')."failed",
                "failure" => env('APP_URL')."failed"
            ]
        ];


        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.tabby.ai/api/v2/checkout',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer sk_dc27f5c7-dff3-4196-9530-b549e9badb24',
             //'Authorization: Bearer sk_test_c16bfa5d-d183-4ca2-80b4-b70e400eab4f',
                'Content-Type: application/x-www-form-urlencoded' // This depends on your server's expected content type
            ),
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($data_fields),
        ));

        $response = curl_exec($curl);
        $responseData = json_decode($response, true);
        //dd($responseData['configuration'] , $responseData);
        if(!isset($responseData['configuration']['available_products']['installments'][0]))
        {
            $data['status'] = 500 ; 
            $data['error_message'] = $responseData['configuration']['products']['installments']['rejection_reason'] ; 
             return $data;
        }
        else
        {
            $data['status'] = 201; 
            $data['url'] = $responseData['configuration']['available_products']['installments'][0]['web_url'] ; 
             return $data;
             
        }
       
        
       

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
                "success" => $data['success-url'],
                "cancel" => $data['cancel-url'],
                "failure" => $data['failure-url'],
            ]
        ];

        return $body;
    }
}
