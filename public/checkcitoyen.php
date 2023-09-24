<?php

//header("Access-Control-Allow-Origin: https://srtk.com.tn");
// Allow specific headers
header("Access-Control-Allow-Origin: https://srtk.com.tn");

header("Access-Control-Allow-Headers: Content-Type");

// Allow specific HTTP methods
header("Access-Control-Allow-Methods: POST, GET");

// Respond to preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}


$url = "https://api.cni.tn/checkCitizen/cinDatNaiss/1.0.0/checkCitizenByCinDnAr";

$token = " eyJ4NXQiOiJOMkpqTWpOaU0yRXhZalJrTnpaalptWTFZVEF4Tm1GbE5qZzRPV1UxWVdRMll6YzFObVk1TlEiLCJraWQiOiJNREpsTmpJeE4yRTFPR1psT0dWbU1HUXhPVEZsTXpCbU5tRmpaalEwWTJZd09HWTBOMkkwWXpFNFl6WmpOalJoWW1SbU1tUTBPRGRpTkRoak1HRXdNQV9SUzI1NiIsImFsZyI6IlJTMjU2In0.eyJzdWIiOiJpYW1pcmEiLCJhdXQiOiJBUFBMSUNBVElPTiIsImF1ZCI6IlkyV2JCbDFOTTBPZHNJOFJvU0ViRXBBWDgzNGEiLCJuYmYiOjE2OTM5Mjk2MTgsImF6cCI6IlkyV2JCbDFOTTBPZHNJOFJvU0ViRXBBWDgzNGEiLCJzY29wZSI6ImRlZmF1bHQiLCJpc3MiOiJodHRwczpcL1wvbG9jYWxob3N0Ojk0NDNcL29hdXRoMlwvdG9rZW4iLCJleHAiOjkwMDAwMDE2OTM5Mjk2MTgsImlhdCI6MTY5MzkyOTYxOCwianRpIjoiYTJjY2ExZTgtZjkzNy00YzA2LWI2NTMtYTFmMjU4OWNmZDFiIn0.DCcCz5DGeklJwpmQ7yx18KfUnbQnK1VDO4ZGao2P0-29nMyTlJ43i6TDL5IJI2-x_t6ZKntdS-1ItoV76F7NbM3uluuhfjN6ROUgWcsEyzIAuknYYbVuAH-td2Y-O2FrN7DYJAyb4UG2WAy4Q-u5o7ZWAvsCA7ZL1fCj_vQjd2d2V_qF1SAN4Zx7uwCEcmYGdcSltn2-GDc7PU3EL7MwTsbwzHQoQhFWp4LkN8SDjDtM7VTZCKQbj9Zx-dRajk2UMfRYeHJPtwj4wE7PQkcaEpPT7eIM8sW4Zon2By5yqg_DTpMCxaFPX2Qnao617aAqGK4STjFTh5mVldM5mpiduQ";





$data = [
    'cin' => $_POST["numCin"],
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
