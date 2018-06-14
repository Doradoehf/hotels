<?php

$params = array(
    'task' => 'check', // check
    'ticket_number' => 'VFIICE072', // number
    'secret_code' => 'Icelandairhotels', // Unkown
    'seller_number' => '108', // Unkown
    'ternimal_number' => 'Herad', // Unkown
    'global_language' => 8 // 8 or 2
);

$url = "http://test.orlof.is/midja/interface/product_use_action.php";

// Curl request

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, count($params));
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);

echo $response;