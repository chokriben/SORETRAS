<?php

$merchantID = 'merchant.1751000728';
$password = '87244402abefc802e378f4977b544610';
$orderID = '';
foreach ($abonnes as $a) {
    $orderID = $a->order_id;
}
$url = "https://tnpost.gateway.mastercard.com/api/rest/version/62/merchant/1751000728/order/$orderID";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$merchantID:$password");

$response = curl_exec($ch);
$i = 0;
if ($response === false) {
    echo 'Erreur de requête cURL : ' . curl_error($ch);
} else {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode === 200) {
        $responseData = utf8_encode($response);

        $responseArray = json_decode($responseData, true);
        foreach ($responseArray['transaction'] as $result) {
            $res = $result;
            $i++;
        }

        print_r($res['response']['gatewayCode']);
    } else {
        echo "Erreur HTTP : $httpCode";
    }
}

curl_close($ch);
