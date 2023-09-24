<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;;

use App\Models\Ligne;
use App\Models\Media;
use App\Models\Abonne;
use App\Models\AbonnePrix;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

use TCPDF;


class AbonneController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public function updateEtat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'id' => 'required|exists:abonnes,id',
            'etat' => 'required|in:active,inactive,imprimer', // Modify this validation as needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        }

        $abonne = Abonne::findOrFail($request->id);
        $abonne->etat = $request->etat;
        $abonne->save();

        return response()->json([
            "success" => true,
            "message" => "Mise à jour de l'état effectuée avec succès",
        ], 200);
    }

    public function index(Request $request, $perPage = 99999999999999)
    {
        // $query = Abonne::query();
        $query = Abonne::query();


        if ($request->has('gare_id')) {
            if ($request->gare_id != NULL) {
                $query->where('abonnes.type_zone', $request->gare_id);
            }
        }
        if ($request->has('cin')) {
            $query->where('abonnes.cin', $request->cin);
        }

        if ($request->has('code')) {
            $query->where('abonnes.code', $request->code);
        }

        $abonnes = $query->paginate($perPage);

        if ($request->has('page')) {
            $abonnes->appends(['cin' => $request->cin, 'code' => $request->code]);
            $abonnes = new LengthAwarePaginator($abonnes, $abonnes->total(), $perPage, $request->page);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sélection effectuée avec succès',
            'abonnes' => $abonnes,
        ], 200);
    }

    public function indexs(Request $request, $perPage = 100)
    {
        // $query = Abonne::query();
        $query = Abonne::query();

        $query->where('abonnes.type_abonne', '!=', null);
        if ($request->has('gare_id')) {
            if ($request->gare_id != NULL) {
                $query->where('abonnes.type_zone', $request->gare_id);
            }
        }
        if ($request->has('cin')) {
            $query->where('abonnes.cin', $request->cin);
        }

        if ($request->has('code')) {
            $query->where('abonnes.code', $request->code);
        }

        $abonnes = $query->paginate($perPage);

        if ($request->has('page')) {
            $abonnes->appends(['cin' => $request->cin, 'code' => $request->code]);
            $abonnes = new LengthAwarePaginator($abonnes, $abonnes->total(), $perPage, $request->page);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sélection effectuée avec succès',
            'abonnes' => $abonnes,
        ], 200);
    }


    public function listabn(Request $request, $perPage = 100)
    {
        $abonnes = Abonne::where('cin', $request->cin)
            ->where('type_eleve', $request->type)
            ->paginate($perPage);

        if ($request->has('page')) {
            $abonnes->appends(['cin' => $request->cin, 'type' => $request->type]);
            $abonnes = new LengthAwarePaginator($abonnes, $abonnes->total(), $perPage, $request->page);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sélection effectuée avec succès',
            'abonnes' => $abonnes,
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */

    function generateUniqueCode()
    {
        $length = 8; // Longueur du code alphanumérique souhaitée
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        do {
            // Génère un code alphanumérique aléatoire de 8 caractères
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }

            // Vérifie si le code existe déjà dans la base de données
            $existingCode = Abonne::where('code', $code)->first();
        } while ($existingCode); // Continue de générer jusqu'à ce qu'un code unique soit trouvé

        // Enregistre le code dans la base de données pour s'assurer de son unicité
        //CodeUnique::create(['code' => $code]);

        return $code;
    }
    public function store(Request $request)
    {
        $csrf = $request->header('Csrf_token');
        $csrftoken = $request->input('csrf_token');
        $yes = '';
        $a = '';

        if ($csrftoken == $csrf) {
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {

                $validator = Validator::make($request->all(), [


                    'prenom_' . $language =>  'max:255|required',
                    //'nom_parent_' . $language =>  'max:255|required',
                    'adresse_' . $language =>  'max:255|required',
                    //'classe_' . $language =>  'max:255|required',
                    //'profession_' . $language =>  'max:255|required',

                ]);
                if ($validator->fails()) {
                    $validatorMessages[$language] = $validator->messages();
                }
            }

            if (!empty($validatorMessages)) {
                return response()->json([
                    "success" => false,
                    "errorsValidation" => $validatorMessages
                ], 400);
            } else {
                $Abonne = new Abonne();
                $code = $this->generateUniqueCode();
                foreach ($langages as $language) {
                    $Abonne->translateOrNew($language)->prenom = $request->input('prenom_' . $language);
                    $Abonne->translateOrNew($language)->nom_parent = $request->input('nom_parent_' . $language);
                    $Abonne->translateOrNew($language)->adresse = $request->input('adresse_' . $language);
                    $Abonne->translateOrNew($language)->classe = $request->input('classe_' . $language);
                    $Abonne->translateOrNew($language)->profession = $request->input('profession_' . $language);
                }
                $Abonne->type_institut = $request->input('type_institut');
                $Abonne->type_inscrit = $request->input('type_inscrit');
                $Abonne->etat = $request->input('etat');
                $Abonne->prix = $request->input('prix') + 3;
                $Abonne->type_paiment = $request->input('type_paiment');
                $Abonne->ligne_id = $request->input('ligne_id');

                $ligne = Ligne::find($Abonne->ligne_id);
                if ($ligne) {
                    $a = strval($ligne->cod);
                    $a = $a[0];
                }
                if ($Abonne->type_paiment == 'annuel') {
                    $yes = '1' . $a;
                } else {
                    $yes = $a;
                }
                $abonnetyp = AbonnePrix::where('id', $yes)->first();


                $Abonne->type_paiment = $abonnetyp->nom;
                $Abonne->type_abonne = $request->input('type_abonne');
                $Abonne->type_eleve = $request->input('type_eleve');
                $Abonne->type_zone = $request->input('type_zone');
                $Abonne->type_validite = $request->input('type_validite');
                $Abonne->type_periode = $request->input('type_periode');
                $Abonne->Etablissement_id = $request->input('Etablissement_id');
                $Abonne->date_fin = $request->input('date_fin');
                $Abonne->date_naissance = $request->input('date_naissance');
                $Abonne->num_telephone = $request->input('num_telephone');
                $Abonne->email = $request->input('email');
                $Abonne->date_emission = $request->input('date_emission');
                $Abonne->date_debut = $request->input('date_debut');
                $Abonne->date_naissance = $request->input('date_naissance');
                $Abonne->cin = $request->input('cin');
                $Abonne->id_uniq = $request->input('id_unique');
                $Abonne->code = $code;
                $Abonne->src = $request->input('image');
                $Abonne->order_id = $request->input('orderid');
                try {
                    $Abonne->save();
                } catch (\Illuminate\Database\QueryException $e) {
                    $error = $e->errorInfo[1];
                    if ($error == 1062) { // Error code for duplicate entry
                        return response()->json([
                            "success" => false,
                            "message" => "Le CIN existe déjà!",

                        ], 409); // Use 409 Conflict status code for uniqueness violation
                    } else {
                        return response()->json([
                            "success" => false,
                            "message" => "Erreur lors de l'insertion des données.",
                        ], 500); // Use 500 Internal Server Error for other errors
                    }
                }


                return response()->json(
                    [
                        "success" => true,
                        "message" => "insertion est effectuée avec success",
                        "code" => $code,
                    ],
                    200
                );
            }
        } else {
            return response()->json([
                'error' => "unknown status",
                //'token' => $user->createToken('API Token')->plainTextToken
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function recoverPassword($cin)
    {
        $Abonne = Abonne::where('cin', $cin)->first();

        if (!$Abonne) {
            return response()->json([
                "success" => false,
                "message" => "Abonne non trouvé."
            ], 404);
        }

        // Récupérer le champ "code" de l'abonné
        $codeAbonnement = $Abonne->code;

        // Envoi du code par SMS
        $myMobile = '216' . $Abonne->num_telephone;
        $mySms = "Votre code d'abonnement est : $codeAbonnement";
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
            "message" => "Le code a été  envoyé par SMS."
        ], 200);
    }

    public function insertMultipleAbonnes(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'etablissement' => 'required|integer',
            'ligne_id' => 'required|integer',
            // Ajoutez ici les règles de validation pour les données reçues
            // par exemple : 'prenom' => 'required|string',
            //              'nom_parent' => 'required|string',
            //              ...
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        }
        // Avant la création de $abonneData


        try {
            $code = $this->generateUniqueCode();

            $abonneData = [
                'prenom' => $data['prenom'] ?? null,
                'nom_parent' => $data['nom_parent'] ?? null,
                'classe' => $data['classe'] ?? null,
                'adresse' => $data['adresse'] ?? null,
                'email' => $data['email'] ?? null,
                'date_naissance' => $data['date_naissance'] ?? null,
                'Etablissement_id' => $data['etablissement'] ?? null,
                'ligne_id' => $data['ligne_id'] ?? null,
                'type_zone' => $data['gare_id'] ?? null,
                'prix' => $data['prix'] ?? null,
                'type_periode' => $data['type_periode'] ?? null,
                'src' => $data['image'] ?? null,
                'type_inscrit' => $data['type_inscrit'] ?? null,
                'num_telephone' => $data['num_telephone'] ?? null,
                'cin' => $data['cin'] ?? null,
                'unique_id' => $data['unique_id'] ?? null,

                'code' => $code,

            ];

            Abonne::create($abonneData);

            return response()->json([
                "success" => true,
                "message" => "Insertion des données réussie.",
                "code" => $code,
                "src" => $data['image'] ?? null,
                "Etablissement_id" =>  $data['etablissement'],
                "ligne_id" =>  $data['ligne_id'] ?? null,

                'type_zone' => $data['gare_id'] ?? null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Erreur lors de l'insertion des données : " . $e->getMessage(),
            ], 500);
        }
    }



    public function show(Request $request)
    {
        $Abonne = Abonne::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "Abonne" => $Abonne,
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $langages = app(Locales::class)->all();

        foreach ($langages as $language) {

            $validator = Validator::make($request->all(), [

                // 'prenom_' . $language =>  'max:255|required',
                //'nom_parent_' . $language =>  'max:255|required',
                // 'adresse_' . $language =>  'max:255|required',
                //'classe_' . $language =>  'max:255|required',
                // 'profession' . $language =>  'max:255|required',
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {

            $Abonne = Abonne::findOrFail($request->id);
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {

                $Abonne->translateOrNew($language)->prenom = $request->input('prenom_' . $language);
                $Abonne->translateOrNew($language)->nom_parent = $request->input('nom_parent_' . $language);
                $Abonne->translateOrNew($language)->adresse = $request->input('adresse_' . $language);
                $Abonne->translateOrNew($language)->classe = $request->input('classe_' . $language);
                $Abonne->translateOrNew($language)->profession = $request->input('profession_' . $language);
            }


            $Abonne->type_institut = $request->input('type_institut');
            $Abonne->type_inscrit = $request->input('type_inscrit');
            $Abonne->etat = $request->input('etat');
            $Abonne->prix = $request->input('prix');
            $Abonne->type_paiment = $request->input('type_paiment');

            $Abonne->type_abonne = $request->input('type_abonne');
            $Abonne->type_eleve = $request->input('type_eleve');
            $Abonne->type_zone = $request->input('type_zone');
            $Abonne->type_validite = $request->input('type_validite');
            $Abonne->type_periode = $request->input('type_periode');
            $Abonne->Etablissement_id = $request->input('Etablissement_id');
            $Abonne->ligne_id = $request->input('ligne_id');
            $Abonne->date_fin = $request->input('date_fin');
            $Abonne->date_debut = $request->input('date_debut');
            $Abonne->date_naissance = $request->input('date_naissance');
            $Abonne->num_telephone = $request->input('num_telephone');
            $Abonne->email = $request->input('email');
            $Abonne->date_emission = $request->input('date_emission');
            $Abonne->date_naissance = $request->input('date_naissance');
            $Abonne->cin = $request->input('cin');
            $Abonne->active = $request->input('active');
            $Abonne->order_id = $request->input('orderid');
            $Abonne->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('abonnes/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $Abonne->medias()->save($media);
            }

            $Abonne->save();
            return response()->json(
                [
                    "success" => true,
                    "message" => "Modification est effectuée avec success",
                ],
                200
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Abonne::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }


    public function abonne_by_gare(Request $request)
    {
        $gare = $request->input('gare_id');
        $typpai = $request->input('typespaiement');
        $typpers = $request->input('typpers');
        $abonnes = Abonne::where('type_zone', $gare)
            ->where('type_paiment', 'LIKE',  $typpai)
            ->where('type_eleve',  $typpers)

            ->get();


        return view('impression_abonne', compact('abonnes'));
    }
}
