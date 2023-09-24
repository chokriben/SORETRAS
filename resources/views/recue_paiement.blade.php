<?php //name of the merchant

$merchantID = 'merchant.1751000728';
$password = '87244402abefc802e378f4977b544610';
$order_id = $_GET['orderid'];
//$resinicator = $_GET['resultIndicator'];

//$url = "https://test-tnpost.mtf.gateway.mastercard.com/api/rest/version/62/merchant/$merchentid/order/$order_id";

//$response = file_get_contents($url);

$url = "https://tnpost.gateway.mastercard.com/api/rest/version/62/merchant/1751000728/order/$order_id";

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

        if ($res['response']['gatewayCode'] == 'APPROVED') {
            try {
                // if ($response !== false) {
                // Gérer l'erreur
                //return ['error' => $body['error']];
                // } else {
                // La réponse contient des données de commande
                $st1 = DB::table('abonnes')
                    ->select('*')
                    ->where('order_id', $order_id)
                    ->get();
                // print_r($st1);
                if ($st1[0]->date_paiement == null) {
                    DB::table('abonnes')
                        ->where('order_id', $order_id)
                        ->update(['date_paiement' => now(), 'etat' => 'paye']);

                    $code = $st1[0]->code;

                    header("Location: https://srtk.com.tn/imprimerabonnes?code=$code");
                    exit();
                } else {
                    $code = $st1[0]->code;
                    header("Location: https://srtk.com.tn/imprimerabonnes?code=$code");
                    exit();
                }

                //  }
            } catch (\Exception $e) {
                // Gérer les erreurs, par exemple en enregistrant le message d'erreur
                // dans les logs ou en renvoyant une réponse d'erreur appropriée.
                return ['error' => $e->getMessage()];
            }
        } else {
            echo 'echec de paiement.';
        }
    } else {
        echo "Erreur HTTP : $httpCode";
    }
}

curl_close($ch);
