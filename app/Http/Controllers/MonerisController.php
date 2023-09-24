<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonerisController extends Controller
{
    public function payWithPaymentPage(Request $request)
    {
        $paymentSessionId = $request->input('paymentSessionId');
        $orderid = $request->input('orderid');

        // Process the payment using the received $paymentSessionId and $orderid
        // ... (Your payment processing code here)

        // Once the payment processing is complete, return the payment page URL
        // For example:
        return response()->json(['paymentURL' => 'https://tnpost.gateway.mastercard.com/payment-page']);
    }
    public function createSession(Request $request)
    {
        $code = $request->input("code");

        $abonnePrix = DB::table('abonnesprix')
            ->where('id', $code)
            ->first();
        $lastprice = $request->input("diff");

        $amount = $abonnePrix->prix + 1 + 3;
        $customer_fname = $request->input("fname");
        $customer_lname = $request->input("lname");
        $customer_email = $request->input("email");

        $customer_phone = $request->input("phone");
        $customer_Adresse = $request->input("adresse");
        $order_id = $request->input("orderid");
        //$order_amount = $amount  - $lastprice - 1 - 3;
        $order_amount = $amount;
        if ($lastprice != 0) {
            $order_amount = $lastprice;
        }
        $currency = $request->input("currency");

        $city = $request->input("city");
        $company = $request->input("company");
        $country = $request->input("country");
        $postcodeZip = $request->input("postcodeZip");
        $street = $request->input("street");
        $street2 = $request->input("street2");
        $name = $request->input("name");
        $phone = $request->input("phone");
        $stateProvince = "TUNIS";

        //name of the merchant
        $merchentid = 1751000728;
        $login = "merchant." . $merchentid;
        $password = '87244402abefc802e378f4977b544610';

        $returnUrl = "https://api.srtk.com.tn/api/recue_paiement?orderid=$order_id"; // Correct the URL if needed

        $order = array(
            "id" => $order_id,
            "currency" => $currency,
            "amount" => $order_amount,


        );

        $interaction = array("returnUrl" => $returnUrl, "operation" => "PURCHASE");
        $customer = array(
            //"firstName" => $customer_fname,
            //"lastName" => $customer_lname,
            "email" => $customer_email,
            //"phone" => $customer_phone,
            // "address" => array(
            //     "line1" => $customer_Adresse,
            //     // Add more address fields as needed
            // ),
            // Add any other customer-related fields here
        );

        $params = array(
            "apiOperation" => "CREATE_CHECKOUT_SESSION",
            "order" => $order,
            "interaction" => $interaction,
            "customer" => $customer,
        );

        $url = "https://tnpost.gateway.mastercard.com/api/rest/version/62/merchant/$merchentid/session/";


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url); //Url together with parameters
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_ENABLE_ALPN, false);
        curl_setopt($ch, CURLOPT_SSL_ENABLE_NPN, false);

        curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7); //Timeout after 7 seconds
        curl_setopt($ch, CURLOPT_POST, 1);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $result = curl_exec($ch);

        if ($result === FALSE) {
            printf("cUrl error (#%d): %s<br>\n", curl_errno($ch), htmlspecialchars(curl_error($ch)));
        }

        print_r($result);

        $result1 = json_decode($result, true);
        $result1 = json_decode($result, true);

        $sess_id = $result1['session']['id'];
        session(['session_id' => $sess_id]);
        session(['orderid' => $order_id]);

        curl_close($ch);
        //return response()->json(['session_id' => $sess_id, 'order_id' => $order_id]);
        // return redirect()->away('https://tnpost.gateway.mastercard.com/payment-page?session_id=' . $sess_id);
    }
}
