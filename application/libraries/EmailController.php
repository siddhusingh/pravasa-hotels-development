<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';  // Correct path to autoload.php

use GuzzleHttp\Client;

class EmailController extends CI_Controller {

    private $clientId;
    private $tenantId;
    private $clientSecret;
    private $sender;

    public function __construct() {
        parent::__construct();

        // Configuration - replace with your own values
        $this->clientId = "1d001572-0cb3-449a-9cf4-ec2035589c73";
        $this->tenantId = "b451a4b4-eb95-4570-b075-b6ef46428dda";
        $this->clientSecret = $_ENV['AZURE_CLIENT_SECRET'];
        $this->sender = "noreply-idex@indiumsoft.com";
    }

    public function sendEmail($name="", $to="", $cc="", $subject="", $message="") {
        try {
            // Step 1: Get access token
            $accessToken = $this->getAccessToken();

            // Step 2: Send email

            $name="Umesh";

            $to="umesh.vishwakarma@indiumsoft.com";

            

            $subject="Test email";

            $message="this is a test email using new lib <h1>Bol bam</h1>";
           
             $greeting = "Dear $name, <br>";

             $final_msg = $greeting . $message;

             $this->sendMailGraphAPI($accessToken,$to,$cc,$subject,$final_msg);
            
             return true;



           
        } catch (Exception $e) {
            echo "Error sending mail: " . $e->getMessage();
        }
    }

     function getAccessToken() {
        // Step 1: Get the OAuth2 token from Microsoft Identity platform
        $client = new Client();
        $response = $client->post('https://login.microsoftonline.com/' . $this->tenantId . '/oauth2/v2.0/token', [
            'form_params' => [
                'client_id' => $this->clientId,
                'scope' => 'https://graph.microsoft.com/.default',
                'client_secret' => $this->clientSecret,
                'grant_type' => 'client_credentials'
            ]
        ]);

        $body = json_decode($response->getBody(), true);
        return $body['access_token'];  // Extract access token
    }

     function sendMailGraphAPI($accessToken,$to, $cc, $subject, $message) {
        // Step 2: Prepare email message
        $message = [
            'message' => [
                'subject' => "$subject",
                'body' => [
                    'content' => "$message",
                    'contentType' => "html"
                ],
                'toRecipients' => [
                    [
                        'emailAddress' => [
                            'address' => "$to"
                        ]
                    ]
                ],

               
            ]
        ];

        

    $ccArray = explode(",", $cc);

    // Check if the array is not empty and does not contain empty values
    if (!empty($ccArray) && $ccArray[0] != "") {  // Check if the array is not empty and the first element is not an empty string
        foreach ($ccArray as $ccAddress) {
            $message['message']['ccRecipients'][] = [
                'emailAddress' => [
                    'address' => $ccAddress
                ]
            ];
        }
    }

    

    

        // Step 3: Send the email using Graph API
        $client = new Client();
        $response = $client->post('https://graph.microsoft.com/v1.0/users/' . $this->sender . '/sendMail', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $message
        ]);

        if ($response->getStatusCode() == 202) {
            echo "Email sent successfully!";
        } else {
            throw new Exception("Failed to send email. HTTP Status: " . $response->getStatusCode());
        }
    }
}
