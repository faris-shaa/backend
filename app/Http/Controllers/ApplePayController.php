<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApplePayController extends Controller
{
    public function validateMerchant(Request $request)
    {
        $validationURL = $request->input('validationURL'); 

        // $validationURL = $request->input('validationURL');

        // // Call EdfaPay API to validate the merchant
        // $response = Http::post('https://api.edfapay.com/validate-merchant', [
        //     'merchant_id' => 'd857c073-c58c-49dc-906b-24fa667dc306',
        //     'api_key' => 'a92f7b7f0d869d3e676c5facda5262ae',
        //     'validationURL' => $validationURL,
        // ]);

        // return response()->json($validationURL, 200);
        
        
        //Set Apple Pay Merchant settings
        $merchantIdentifier = 'merchant.com.wasltec.Applepaywasltec';  // Your merchant identifier
        $displayName = 'Dar Lana';          // Display name of your store
        $domainName = 'ticketby.co';            // Your website's domain
        $certificatePath = storage_path('certs/ticketby_merchant_id.pem');  // Path to your Apple Pay certificate
        $certificateKey = storage_path('certs/ticketby_merchant.key');    // Path to your Apple Pay key
        $certificateKeyPassword = 'TicketBy@2024';  // Password for the certificate key
        

        $response = Http::withOptions([
            'cert' => $certificatePath,
            'ssl_key' => [$certificateKey, $certificateKeyPassword],
            'verify' => true,  // Enable SSL verification for production
        ])->withHeaders([
            'Content-Type' => 'application/json',
        ])->post($validationURL, [
            'merchantIdentifier' => $merchantIdentifier,
            'domainName' => $domainName,
            'displayName' => $displayName,
        ]);

        
        return response()->json($response->json(), 200);

        print_r($response);
        exit;

        if ($response->successful()) {
            // Send the successful validation response back to the client
            return response()->json($response->json(), 200);
        } else {
            return response()->json(['error' => 'Merchant validation failed'], 500);
        }
            try {} catch (\Exception $e) {
            return response()->json(['error' => 'Exception during merchant validation: ' . $e->getMessage()], 500);
        }
    }

    public function processPayment(Request $request)
    { 
        $data = $request->token;  
        
        $publicKeyHash = $data['transactionIdentifier']; 
        //$publicKeyHash = 'merchant.com.wasltec.Applepaywasltec'; 

        

        $identifier = $publicKeyHash;  // Replace with actual identifier
        $order_id = 'hdhdhshadh';  // Replace with actual order ID
        $order_amount = '0.11';  // Replace with actual order amount
        $order_currency = 'SAR';  // Replace with actual order currency
        $password = 'a92f7b7f0d869d3e676c5facda5262ae';  // Replace with your actual password

        // Concatenate the strings
        // $concatenated_string = $identifier . $order_id . $order_amount . $order_currency . $password;

        // // Reverse the concatenated string
        // $reversed_string = strrev($concatenated_string);

        // // Convert the reversed string to uppercase
        // $upper_string = strtoupper($reversed_string);

        // // Calculate the MD5 hash
        // $hash = md5($upper_string); 

        $hash = md5(strtoupper(strrev($identifier.$order_id.$order_amount.$order_currency.$password)));

        // $paymentDataArray = json_decode(json_encode($data), true);

        // // Encode the array as JSON without escaping slashes
        // $paymentDataJson = json_encode($paymentDataArray, JSON_UNESCAPED_SLASHES);

        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        // CURLOPT_URL => 'https://api.edfapay.com/applepay/orders/s2s/sale',
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => 'POST',
        // CURLOPT_POSTFIELDS => http_build_query(array(
        //     'action' => 'SALE',
        //     'client_key' => 'd857c073-c58c-49dc-906b-24fa667dc306', // Replace with your actual client key
        //     'brand' => 'applepay',
        //     'order_id' => 'ABC12345',
        //     'order_amount' => '0.11',
        //     'order_currency' => 'SAR',
        //     'order_description' => 'Test Order',
        //     'payer_first_name' => 'Adnan',
        //     'payer_last_name' => 'Hashmi',
        //     'payer_address' => 'john.doe@example.com',
        //     'payer_country' => 'SA',
        //     'payer_state' => 'Riyadh',
        //     'payer_city' => 'Riyadh',
        //     'payer_zip' => '123221',
        //     'payer_email' => 'adnanh@expresspay.sa',
        //     'payer_phone' => '966565897862',
        //     'payer_birth_date' => '1987-12-12',
        //     'payer_ip' => '176.44.76.100',
        //     'return_url' => 'https://ticketby.co',
        //     'identifier' => $publicKeyHash,
        //     'hash' => $hash, // Replace with the correct hash
        //     'parameters' => $paymentDataArray
        // )),    
        // CURLOPT_HTTPHEADER => array(
        //     'Cookie: PHPSESSID=6hahu1ps9sji5vjeg2pru3no8v',
        //     'Content-Type: application/x-www-form-urlencoded' // This is commonly used for form data, adjust if needed
        // ),
    
        // )); 

        // \Log::info('Request Body:',array(
        //     'action' => 'SALE',
        //     'client_key' => 'd857c073-c58c-49dc-906b-24fa667dc306', // Replace with your actual client key
        //     'brand' => 'applepay',
        //     'order_id' => 'ABC12345',
        //     'order_amount' => '0.11',
        //     'order_currency' => 'SAR',
        //     'order_description' => 'Test Order',
        //     'payer_first_name' => 'Adnan',
        //     'payer_last_name' => 'Hashmi',
        //     'payer_address' => 'john.doe@example.com',
        //     'payer_country' => 'SA',
        //     'payer_state' => 'Riyadh',
        //     'payer_city' => 'Riyadh',
        //     'payer_zip' => '123221',
        //     'payer_email' => 'adnanh@expresspay.sa',
        //     'payer_phone' => '966565897862',
        //     'payer_birth_date' => '1987-12-12',
        //     'payer_ip' => '176.44.76.100',
        //     'return_url' => 'https://ticketby.co',
        //     'identifier' => $publicKeyHash,
        //     'hash' => $hash, // Replace with the correct hash
        //     'parameters' => $paymentDataArray 
        // ));

        // $response = curl_exec($curl);

        // Check for errors
        // if (curl_errno($curl)) {
        //     return response()->json(curl_error($curl), 200); 
        // } else { 
        //     return response()->json($response, 200); 
        // }

        // Close cURL session
        //curl_close($curl);

        //$escapedJsonString = addslashes(json_encode($data));

        // Add forward slashes for escaping in JSON data
        //$escapedJsonString = str_replace('/', '\/', $escapedJsonString);

        //$decodedBody = json_decode(json_encode($data), true);
        
        // Re-encode to remove extra escaping for logging purposes
        //$encodedBody = json_encode($decodedBody, JSON_PRETTY_PRINT);
        
        // $paymentData = [
        //     // Your payment data as an associative array
        //     'paymentData' => [
        //         'data' => @$data['paymentData']['data'],
        //         'signature' => @$data['paymentData']['signature'],
        //         'header' => [
        //             'publicKeyHash' => @$data['paymentData']['header']['publicKeyHash'],
        //             'ephemeralPublicKey' => @$data['paymentData']['header']['ephemeralPublicKey'],
        //             'transactionId' => @$data['paymentData']['header']['transactionId'],
        //         ],
        //         'version' => @$data['paymentData']['version'],
        //     ],
        //     'paymentMethod' => [
        //         'displayName' => @$data['paymentMethod']['displayName'],
        //         'network' => @$data['paymentMethod']['network'],
        //         'type' => @$data['paymentMethod']['type'],
        //     ],
        //     'transactionIdentifier'  => @$data['transactionIdentifier'],
        // ];

        $paymentData = '{"paymentData":{"data":"'.@$data['paymentData']['data'].'","signature":"'.@$data['paymentData']['signature'].'","header":{"publicKeyHash":"'.@$data['paymentData']['header']['publicKeyHash'].'","ephemeralPublicKey":"'.@$data['paymentData']['header']['ephemeralPublicKey'].'","transactionId":"'.@$data['paymentData']['header']['transactionId'].'"},"version":"'.@$data['paymentData']['version'].'"},"paymentMethod":{"displayName":"'.@$data['paymentMethod']['displayName'].'","network":"'.@$data['paymentMethod']['network'].'","type":"'.@$data['paymentMethod']['type'].'"},"transactionIdentifier":"'.@$data['transactionIdentifier'].'"}';
            
        // $paymentDataArray = json_decode(json_encode($data), true);

        // // Encode the array as JSON without escaping slashes
        // $paymentDataJson = json_encode($paymentDataArray, JSON_UNESCAPED_SLASHES);


        

        
        // $response = Http::withHeaders([
        //     'Cookie' => 'PHPSESSID=6hahu1ps9sji5vjeg2pru3no8v',
        //     'Content-Type' => 'application/x-www-form-urlencoded',
        // ])->post('https://api.edfapay.com/applepay/orders/s2s/sale', [
        //     'action' => 'SALE',
        //     'client_key' => 'd857c073-c58c-49dc-906b-24fa667dc306', // Replace with your actual client key
        //     'brand' => 'applepay',
        //     'order_id' => 'hdhdhshadh',
        //     'order_amount' => '0.11',
        //     'order_currency' => 'SAR',
        //     'order_description' => 'Test Order',
        //     'payer_first_name' => 'Adnan',
        //     'payer_last_name' => 'Hashmi',
        //     'payer_address' => 'john.doe@example.com',
        //     'payer_country' => 'SA',
        //     'payer_state' => 'Riyadh',
        //     'payer_city' => 'Riyadh',
        //     'payer_zip' => '123221',
        //     'payer_email' => 'adnanh@expresspay.sa',
        //     'payer_phone' => '966565897862',
        //     'payer_birth_date' => '1987-12-12',
        //     'payer_ip' => '176.44.76.100',
        //     'return_url' => 'https://ticketby.co',
        //     'identifier' => $publicKeyHash,
        //     'hash' => $hash, // Replace with the correct hash
        //     'parameters' => $paymentDataArray, 
        //     'payer_address2' => 'test',
        //     'payer_middle_name' => 'test',
        // ]);

        $response = Http::withHeaders([
            'Cookie' => 'PHPSESSID=6hahu1ps9sji5vjeg2pru3no8v',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])
                ->asForm()
                ->post('https://api.edfapay.com/applepay/orders/s2s/sale', [
                    'action' => 'SALE',
                    'client_key' => 'd857c073-c58c-49dc-906b-24fa667dc306', // Replace with your actual client key
                    'brand' => 'applepay',
                    'order_id' => 'hdhdhshadh',
                    'order_amount' => '0.11',
                    'order_currency' => 'SAR',
                    'order_description' => 'Test Order',
                    'payer_first_name' => 'Adnan',
                    'payer_last_name' => 'Hashmi',
                    'payer_address' => 'john.doe@example.com',
                    'payer_country' => 'SA',
                    'payer_state' => 'Riyadh',
                    'payer_city' => 'Riyadh',
                    'payer_zip' => '123221',
                    'payer_email' => 'adnanh@expresspay.sa',
                    'payer_phone' => '966565897862',
                    'payer_birth_date' => '1987-12-12',
                    'payer_ip' => '176.44.76.100',
                    'return_url' => 'https://ticketby.co',
                    'identifier' => $publicKeyHash,
                    'hash' => $hash, // Replace with the correct hash
                    'parameters' => $paymentData, 
                    'payer_address2' => 'test',
                    'payer_middle_name' => 'test',
                ]);
        
        \Log::info('Request Body:',  [
            'action' => 'SALE',
            'client_key' => 'd857c073-c58c-49dc-906b-24fa667dc306', // Replace with your actual client key
            'brand' => 'applepay',
            'order_id' => 'hdhdhshadh',
            'order_amount' => '0.11',
            'order_currency' => 'SAR',
            'order_description' => 'Test Order',
            'payer_first_name' => 'Adnan',
            'payer_last_name' => 'Hashmi',
            'payer_address' => 'john.doe@example.com',
            'payer_country' => 'SA',
            'payer_state' => 'Riyadh',
            'payer_city' => 'Riyadh',
            'payer_zip' => '123221',
            'payer_email' => 'adnanh@expresspay.sa',
            'payer_phone' => '966565897862',
            'payer_birth_date' => '1987-12-12',
            'payer_ip' => '176.44.76.100',
            'return_url' => 'https://ticketby.co',
            'identifier' => $publicKeyHash,
            'hash' => $hash, // Replace with the correct hash
            'parameters' => $paymentData,
            'payer_address2' => 'test',
            'payer_middle_name' => 'test',
        ]);
        
        return response()->json($response->json(), 200);
        
        // Get the response body
        $body = $response->body();
        
        return response()->json($body, 200);

        $response = curl_exec($curl);

        curl_close($curl);
        return response()->json($response, 200);
        echo $response;
    }

    
}
