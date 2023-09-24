<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VeriffamilleController extends Controller
{
    public function yourMethod(Request $request)
    {
        $token = 'eyJ4NXQiOiJOMkpqTWpOaU0yRXhZalJrTnpaalptWTFZVEF4Tm1GbE5qZzRPV1UxWVdRMll6YzFObVk1TlEiLCJraWQiOiJNREpsTmpJeE4yRTFPR1psT0dWbU1HUXhPVEZsTXpCbU5tRmpaalEwWTJZd09HWTBOMkkwWXpFNFl6WmpOalJoWW1SbU1tUTBPRGRpTkRoak1HRXdNQV9SUzI1NiIsImFsZyI6IlJTMjU2In0.eyJzdWIiOiJpYW1pcmEiLCJhdXQiOiJBUFBMSUNBVElPTiIsImF1ZCI6InRSal9Qak5QeHdnNENQVzJRc1h0bmhfNDZFMGEiLCJuYmYiOjE2OTM5MjIyODgsImF6cCI6InRSal9Qak5QeHdnNENQVzJRc1h0bmhfNDZFMGEiLCJzY29wZSI6ImRlZmF1bHQiLCJpc3MiOiJodHRwczpcL1wvbG9jYWxob3N0Ojk0NDNcL29hdXRoMlwvdG9rZW4iLCJleHAiOjU0NzgyNDIyODgsImlhdCI6MTY5MzkyMjI4OCwianRpIjoiMTc2ODNlZjktODdiYy00ODdlLWJkOWEtM2Y4NzU0OTE5ZTE5In0.IoZZyP-1s2EpkC5ogOF9hcgy5NxJAFqUKm4NbB1wlJ0W2lfckR9w2ET9jQljbcN16nqIPfaosjayW_yiYeCDpsIEOWIZvvwimoSjkfROkalH5t1nBVCQMwKQRx_UZqdj2lGJ5zkPMOioNY71Ec0ksukdkgUzrZ1NXO9VNCkRSGJPChE9cs5iATdZ2rK-7tLd_n5IdlNa9Xc5KqXT3yQbfZ5gCdDIBUNhnuTqXnNw4A2Nicg6pgWIEckNxN7j7xeqnhDOOaRMWNjsq2RVvgoUQ6yuUXDGLxZA98ITX45GiAa_1AmRGvJ0nS-8y7ZrF8YBZH-8D4RRVP9_ft_r64xVNw';
        $cniApiUrl = 'https://api.cni.tn/checkCitizen/cinDatNaiss/1.0.0/checkCitizenByCinDnAr';

        $data = [
            'cin' => '11144931',
            'jourNaiss' => '25',
            'moisNaiss' => '6',
            'anneeNaiss' => '2000'
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($cniApiUrl, $data);

        if ($response->failed()) {
            return response()->json(['error' => 'Erreur HTTP : ' . $response->status()], $response->status());
        } else {
            return $response->body();
        }
    }
}
