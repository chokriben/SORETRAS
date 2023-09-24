<?php

header("Access-Control-Allow-Origin: https://srtk.com.tn");
// Allow specific headers
header("Access-Control-Allow-Headers: Content-Type");

// Allow specific HTTP methods
header("Access-Control-Allow-Methods: POST, GET");

// Respond to preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}


$url = "https://apis.cni.tn/eligibility/idEdu/Dn/1.0.0/checkEligibilityByIdEduDn";

$token = " eyJ4NXQiOiJOMkpqTWpOaU0yRXhZalJrTnpaalptWTFZVEF4Tm1GbE5qZzRPV1UxWVdRMll6YzFObVk1TlEiLCJraWQiOiJNREpsTmpJeE4yRTFPR1psT0dWbU1HUXhPVEZsTXpCbU5tRmpaalEwWTJZd09HWTBOMkkwWXpFNFl6WmpOalJoWW1SbU1tUTBPRGRpTkRoak1HRXdNQV9SUzI1NiIsImFsZyI6IlJTMjU2In0.eyJzdWIiOiJvc2FpZGFuZSIsImF1dCI6IkFQUExJQ0FUSU9OIiwiYXVkIjoiNU56TXdYM1Y2bGozbzMxQW04bE9wRzRFYnpNYSIsIm5iZiI6MTY5NDA3NzM5NCwiYXpwIjoiNU56TXdYM1Y2bGozbzMxQW04bE9wRzRFYnpNYSIsInNjb3BlIjoiZGVmYXVsdCIsImlzcyI6Imh0dHBzOlwvXC9sb2NhbGhvc3Q6OTQ0M1wvb2F1dGgyXC90b2tlbiIsImV4cCI6NTQ3ODM5NzM5NCwiaWF0IjoxNjk0MDc3Mzk0LCJqdGkiOiIyYjBiOTM2Ny1hYTU1LTQ4ODktYWRlZC1lYmIzYTg0YjkxN2EifQ.QHTZWXxKK40l4z-r4Aizy1-z_v_3cvoAcSuHu9YFBH1m_B6DPqQYJkH15Jt4AtYf5MX9Sbx2hi3aE7T7xk7uusuGlhHkAERcTMfZ0Hp5L64vJvriVIBG0wBgV4qENqaIO1MWINMcFJbCi4f6Dfz2Nsj3eAvl3QJcoJsZmrvEGyyRgmiBJjmprolpDdDel9jDaUPu4QaO7z2Rl0En4TOZqbKAZ0SyksvacsD3xCxGQqZZxdFqQI4XyySkDJhiz3VBT63ptr92aTvXt3SFknf2Yg-MtmVbkOJ5a6PDdfuz19r8TW15Z02xwtEKsZAzWg8VWMmcUbJInr4aapYxfUGbog";
/*
echo $_POST["numCin"];
echo '<br>';
echo $_POST["jourNaiss"];

echo $_POST["moisNaiss"];

echo $_POST["anneeNaiss"];
*/


$data = [
    'idEdu' => $_POST["numCin"],
    'jourNaiss' => $_POST["jourNaiss"],
    'moisNaiss' => $_POST["moisNaiss"],
    'anneeNaiss' => $_POST["anneeNaiss"]
];


$options = [
    'http' => [
        'header' => "Authorization: Bearer " . $token . "\r\n" .
            "Content-Type: application/json\r\n",
        'method' => 'POST',
        'content' => json_encode($data),
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === FALSE) {
    echo "Erreur : " . error_get_last()['message'];
} else {
    echo $response;
}
