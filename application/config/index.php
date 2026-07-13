<?php

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://control.msg91.com/api/v5/flow/",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'template_id' => '6593d133d6fc056374712882',
    'short_url' => '0'
    'recipients' => [
        [
                'mobiles' => '918224006280',
                'VAR1' => '9878',
                'VAR2' => 'VALUE2'
        ]
    ]
  ]),
  CURLOPT_HTTPHEADER => [
    "accept: application/json",
    "authkey:411916Aj9UlotUXtz65a27bebP1",
    "content-type: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}