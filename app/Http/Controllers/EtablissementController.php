<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use App\Models\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\EtablissementTranslation;

class EtablissementController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 1500)
    {

        $etablissements = etablissement::paginate($perPage);
        $etablissements->each(
            function ($etablissement, $key) {
                $abonne = $etablissement->abonne;
            }
        );

        if ($request->page) {
            $etablissements = new LengthAwarePaginator($etablissements, count($etablissements), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'etablissements' => $etablissements,
            ],
            200
        );
    }

    /**
     * fetchAndInsertDataFromAPI
     */

    function fetchAndDisplayAPIResult()
    {

        // API endpoint URL
        $apiEndpoint = 'https://api.srtk.com.tn/etab.php';

        // API key

        // Create an array of HTTP headers
        $headers = array(
            'http' => array()
        );

        // Create a stream context with the headers
        $context = stream_context_create($headers);

        // Make the API request and get the response
        $response = file_get_contents($apiEndpoint, false, $context);

        // Check if the request was successful
        if ($response === false) {
            echo "Failed to fetch data from the API.";
            return;
        }
        // Parse the JSON response
        $responseData = json_decode($response, true);

        // Check if the response data has the "data" key
        if (isset($responseData['data'])) {
            // Get the data array from the response
            $data = $responseData['data'];

            // Insert data into the etablissement and etablissement_trans tables
            foreach ($data as $item) {

                $result = DB::select("select * from etablissement_translations where labelle ='" . $item['libetablissement'] . "'");
                if (count($result) > 0) {
                } else {

                    $etablissement = new Etablissement();
                    $etablissement->codeetab = $item['codeetab'];
                    $etablissement->typeetablissement = $item['typeetablissement'];
                    $etablissement->codetype = $item['codetype'];
                    $etablissement->year = date('Y');
                    // Set other properties as needed
                    $etablissement->save();

                    $etablissementTrans = new EtablissementTranslation();
                    $etablissementTrans->etablissement_id = $etablissement->id;
                    $etablissementTrans->locale = 'ar';
                    $etablissementTrans->labelle = $item['libetablissement'];
                    // Set other properties as needed
                    $etablissementTrans->save();

                    if ($item['libetablissementfr'] != '') {
                        $etablissementTrans = new EtablissementTranslation();
                        $etablissementTrans->etablissement_id = $etablissement->id;
                        $etablissementTrans->locale = 'fr';
                        $etablissementTrans->labelle = $item['libetablissementfr'];
                        // Set other properties as needed
                        $etablissementTrans->save();
                        $etablissementTrans = new EtablissementTranslation();
                        $etablissementTrans->etablissement_id = $etablissement->id;
                        $etablissementTrans->locale = 'en';
                        $etablissementTrans->labelle = $item['libetablissementfr'];
                        // Set other properties as needed
                        $etablissementTrans->save();
                    }
                }
            }

            echo "Data inserted successfully.";
        } else {
            echo "No data found in the API response.";
        }
    }
    public function store(Request $request)
    {
        $langages = app(Locales::class)->all();

        foreach ($langages as $language) {
            $validator = Validator::make($request->all(), [
                'labelle_' . $language =>  'max:255|required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            $etablissement = new Etablissement();

            foreach ($langages as $language) {
                $etablissement->translateOrNew($language)->labelle = $request->input('labelle_' . $language);
            }

            $typeEtablissement = $request->input('typeetablissement');

            if ($typeEtablissement == 'Etatique_formation') {
                $etablissement->codetype = 70;
            } elseif ($typeEtablissement == 'Prive_formation') {
                $etablissement->codetype = 60;
            } elseif ($typeEtablissement == 'Etatique_Primaire') {
                $etablissement->codetype = 10;
            } elseif ($typeEtablissement == 'Prive_Primaire') {
                $etablissement->codetype = 12;
            } elseif ($typeEtablissement == 'Etatique_lycee') {
                $etablissement->codetype = 40;
            } elseif ($typeEtablissement == 'Etatique_College') {
                $etablissement->codetype = 20;
            } elseif ($typeEtablissement == 'Prive_Secondaire') {
                $etablissement->codetype = 32;
            }



            $etablissement->year = date('Y');
            $etablissement->typeetablissement = $typeEtablissement;
            $etablissement->codeetab = $request->input('codeetab');

            $etablissement->save();

            return response()->json(
                [
                    "success" => true,
                    "message" => "Insertion est effectuée avec succès",
                ],
                200
            );
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $etablissement = etablissement::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "etablissement" => $etablissement,
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $etablissement = Etablissement::findOrFail($id);

        $validator = Validator::make($request->all(), [
            // Define validation rules here if needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            // Update the 'active' field if provided in the request
            if ($request->has('active')) {
                $etablissement->active = $request->input('active');
            }

            // Save the changes
            $etablissement->save();

            return response()->json([
                "success" => true,
                "message" => "Modification est effectuée avec succès",
            ], 200);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        etablissement::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
