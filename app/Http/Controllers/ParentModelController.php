<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;;

use App\Models\Media;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;


class ParentModelController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 10)
    {


        $Parent = ParentModel::paginate($perPage);
        $Parent->each(
            function ($Parent, $key) {
            }
        );

        if ($request->page) {
            $Parent = new LengthAwarePaginator($Parent, count($Parent), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'Parents' => $Parent,
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */

    function generateUniqueCode()
    {
        $length = 5; // Longueur du code alphanumérique souhaitée
        $characters = '0123456789SRTK';
        $code = '';

        do {
            // Génère un code alphanumérique aléatoire de 8 caractères
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }

            // Vérifie si le code existe déjà dans la base de données
            $existingCode = ParentModel::where('code', $code)->first();
        } while ($existingCode); // Continue de générer jusqu'à ce qu'un code unique soit trouvé

        // Enregistre le code dans la base de données pour s'assurer de son unicité
        //CodeUnique::create(['code' => $code]);

        return $code;
    }



    public function store(Request $request)
    {


        $langages = app(Locales::class)->all();
        $validator = Validator::make($request->all(), [
            // Add the 'unique' validation rule for 'cin' field
            'cin' => 'required|unique:parent_models,cin|max:255',
            // Rest of the validation rules...
        ]);
        foreach ($langages as $language) {

            $validator = Validator::make($request->all(), [

                'name_' . $language =>  'max:255|required',
                'prenom_' . $language =>  'max:255|required',

            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            // Generate a unique code
            $Parent = new ParentModel();
            $code = $this->generateUniqueCode();
            $Parent->code = $code;
            $Parent->cin = $request->input('cin');

            // Check if the CIN already exists in the database
            $existingParent = ParentModel::where('cin', $Parent->cin)->first();
            if ($existingParent) {
                return response()->json([
                    "success" => false,
                    "message" => "CIN déjà utilisé."
                ], 400);
            }

            // SMS Integration
            $myMobile = '216' . $request->input('num_telephone');

            $mySms = "Votre code d'activation est :$code";
            $mySender = 'SRTK';
            $myDate = now()->format('d/m/Y');
            $myTime = now()->format('H:i');

            $Url_str = "http://bulksms.bipsms.tn/Api/Api.aspx?fct=sms&key=ZlMIPGkwetdk8kxoDLsLnkQcG7i9kORiLOF/FdH7ND0PqtTTvCmPkmWFsdH2Cqr7zWcs44hF3/zMhlgUboSHqHh0FlAG9NVe&mobile=216XXXXXXXX&sms=Hello+World&sender=YYYYYYY&date=jj/mm/aaaa&heure=hh:mm";

            $Url_str = str_replace("216XXXXXXXX", $myMobile, $Url_str);
            $Url_str = str_replace("Hello+World", urlencode($mySms), $Url_str);
            $Url_str = str_replace("YYYYYYY", $mySender, $Url_str);
            $Url_str = str_replace("jj/mm/aaaa", $myDate, $Url_str);
            $Url_str = str_replace("hh:mm", $myTime, $Url_str);

            // Debugging: Output the final URL for verification
            echo "Final URL: $Url_str";

            // Use a library or method to make an HTTP GET request to the $Url_str
            // Example using Guzzle HTTP client:
            $httpClient = new \GuzzleHttp\Client();
            try {
                $response = $httpClient->get($Url_str);
                // Debugging: Output the response
                echo "API Response: " . $response->getBody();
            } catch (\Exception $e) {
                // Debugging: Output any exceptions or errors
                echo "Error: " . $e->getMessage();
            }




            foreach ($langages as $language) {
                $Parent->translateOrNew($language)->name = $request->input('name_' . $language);
                $Parent->translateOrNew($language)->prenom = $request->input('prenom_' . $language);
            }



            $Parent->jour_naissance = $request->input('jour_naissance');
            $Parent->num_telephone = $request->input('num_telephone');
            $Parent->mois_naissance = $request->input('mois_naissance');
            $Parent->email = $request->input('email');
            $Parent->annee_naissance = $request->input('annee_naissance');
            $Parent->cin = $request->input('cin');

            $Parent->password = Hash::make($request->input('password'));





            $Parent->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('Actualites/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $Parent->medias()->save($media);
            }

            return response()->json(
                [
                    "success" => true,
                    "message" => "insertion est effectuée avec success",
                    //"code" => $code,
                ],
                200
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function recoverPassword($cin)
    {
        $parent = ParentModel::where('cin', $cin)->first();

        if (!$parent) {
            return response()->json([
                "success" => false,
                "message" => "Parent non trouvé."
            ], 404);
        }

        // Génération d'un mot de passe aléatoire
        $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

        // Mettre à jour le mot de passe de l'utilisateur dans la base de données
        $parent->password = Hash::make($newPassword);
        $parent->save();

        // Envoi du mot de passe par SMS
        $myMobile = '216' . $parent->num_telephone;
        $mySms = "Votre nouveau mot de passe est : $newPassword";
        $mySender = 'SRTK';
        $myDate = now()->format('d/m/Y');
        $myTime = now()->format('H:i');

        $Url_str = "http://bulksms.bipsms.tn/Api/Api.aspx?fct=sms&key=ZlMIPGkwetdk8kxoDLsLnkQcG7i9kORiLOF/FdH7ND0PqtTTvCmPkmWFsdH2Cqr7zWcs44hF3/zMhlgUboSHqHh0FlAG9NVe&mobile=216XXXXXXXX&sms=Hello+World&sender=YYYYYYY&date=jj/mm/aaaa&heure=hh:mm";

        $Url_str = str_replace("216XXXXXXXX", $myMobile, $Url_str);
        $Url_str = str_replace("Hello+World", urlencode($mySms), $Url_str);
        $Url_str = str_replace("YYYYYYY", $mySender, $Url_str);
        $Url_str = str_replace("jj/mm/aaaa", $myDate, $Url_str);
        $Url_str = str_replace("hh:mm", $myTime, $Url_str);

        $httpClient = new \GuzzleHttp\Client();
        try {
            $response = $httpClient->get($Url_str);
            // Debugging: Output the response
            echo "API Response: " . $response->getBody();
        } catch (\Exception $e) {
            // Debugging: Output any exceptions or errors
            echo "Error: " . $e->getMessage();
        }
        // Réponse JSON
        return response()->json([
            "success" => true,
            "message" => "Nouveau mot de passe généré et envoyé par SMS."
        ], 200);
    }
    public function show(Request $request)
    {
        $Parent = ParentModel::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "Parent" => $Parent,
            ],
            200
        );
    }
    public function select_parent($cin)
    {
        $parent = ParentModel::select('id', 'active', 'num_telephone', 'email', 'cin')

            ->where('cin', $cin)
            ->first();
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "Parent" => $parent,
            ],
            200
        );
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $parentId = '';

        $parent = ParentModel::select('id', 'password') // Sélectionnez également le mot de passe
            ->where('cin', $request->id)
            ->first();

        if (!$parent) {
            return response()->json([
                "success" => false,
                "message" => "Parent not found",
            ], 404);
        }

        $parentId = $parent->id;
        $currentPassword = $parent->password; // Obtenez le mot de passe actuel

        $validator = Validator::make($request->all(), [
            'email' => 'max:255|required',
            'password' => 'max:255|required',
            'oldPassword' => 'required', // Add validation for old password
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            if (Hash::check($request->input('oldPassword'), $currentPassword)) {
                // Si l'ancien mot de passe est correct, effectuez la mise à jour
                $parent = ParentModel::findOrFail($parentId);

                $parent->password = Hash::make($request->input('password'));
                $parent->email = $request->input('email');

                // ...

                $parent->save();

                return response()->json(
                    [
                        "success" => true,
                        "message" => "Modification est effectuée avec succès",
                    ],
                    200
                );
            } else {
                // Si l'ancien mot de passe est incorrect
                return response()->json([
                    "success" => false,
                    "message" => "L'ancien mot de passe est incorrect",
                ], 400);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        ParentModel::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
