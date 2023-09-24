<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
;
use App\Models\Media;
use App\Models\Visiteur;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;


class VisiteurController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 10)
    {

        $Visiteurs = Visiteur::paginate($perPage);
        $Visiteurs->each(
            function ($Visiteur, $key) {
                $TypeAbonne = $Visiteur->TypeAbonne;
                $Abonnement = $Visiteur->Abonnement;
                $Etablissement = $Visiteur->Etablissement;
            }
        );

        if ($request->page) {
            $Visiteurs = new LengthAwarePaginator($Visiteurs, count($Visiteurs), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'Visiteurs' => $Visiteurs,
            ],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $langages = app(Locales::class)->all();

        foreach ($langages as $language) {

            $validator = Validator::make($request->all(), [

                'nom_' . $language =>  'max:255|required',
                'prenom_' . $language =>  'max:255|required',
                'nom_parent_' . $language =>  'max:255|required',
                'prenom_parent_' . $language =>  'max:255|required',
                'adresse_' . $language =>  'max:255|required',
                'classe_' . $language =>  'max:255|required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            $Visiteur = new Visiteur();


            foreach ($langages as $language) {
                $Visiteur->translateOrNew($language)->nom = $request->input('nom_' . $language);
                $Visiteur->translateOrNew($language)->prenom= $request->input('prenom_' . $language);
                $Visiteur->translateOrNew($language)->nom_parent = $request->input('nom_parent_' . $language);
                $Visiteur->translateOrNew($language)->prenom_parent = $request->input('prenom_parent_' . $language);
                $Visiteur->translateOrNew($language)->adresse = $request->input('adresse_' . $language);
                $Visiteur->translateOrNew($language)->classe = $request->input('classe_' . $language);
            }

            $Visiteur->Etablissement_id = $request->input('Etablissement_id');
            $Visiteur->Abonnement_id = $request->input('Abonnement_id');
            $Visiteur->TypeAbonne_id = $request->input('TypeAbonne_id');
            $Visiteur->identifiant_ministere = $request->input('identifiant_ministere');
            $Visiteur->date_naissance = $request->input('date_naissance');
            $Visiteur->num_telephone = $request->input('num_telephone');
            $Visiteur->photo_url = $request->input('photo_url');
            $Visiteur->email = $request->input('email');
            $Visiteur->date_emission = $request->input('date_emission');
            $Visiteur->cin_parent = $request->input('cin_parent');
            $Visiteur->cin = $request->input('cin');
            
            
            
            $Visiteur->save();

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
                $Visiteur->medias()->save($media);
            }

            return response()->json(
                [
                    "success" => true,
                    "message" => "insertion est effectuée avec success",
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
        $Visiteur = Visiteur::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "Visiteur" => $Visiteur,
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
                'nom_' . $language =>  'max:255|required',
                'prenom_' . $language =>  'max:255|required',
                'nom_parent_' . $language =>  'max:255|required',
                'prenom_parent_' . $language =>  'max:255|required',
                'adresse_' . $language =>  'max:255|required',
                'classe_' . $language =>  'max:255|required',
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {

            $Visiteur = Visiteur::findOrFail($request->id);
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {
                $Visiteur->translateOrNew($language)->nom = $request->input('nom_' . $language);
                $Visiteur->translateOrNew($language)->prenom= $request->input('prenom_' . $language);
                $Visiteur->translateOrNew($language)->nom_parent = $request->input('nom_parent_' . $language);
                $Visiteur->translateOrNew($language)->prenom_parent = $request->input('prenom_parent_' . $language);
                $Visiteur->translateOrNew($language)->adresse = $request->input('adresse_' . $language);
                $Visiteur->translateOrNew($language)->classe = $request->input('classe_' . $language);
            }

            $Visiteur->Etablissement_id = $request->input('Etablissement_id');
            $Visiteur->Abonnement_id = $request->input('Abonnement_id');
            $Visiteur->TypeAbonne_id = $request->input('TypeAbonne_id');
           // $Visiteur->prenom = $request->input('prenom');
            $Visiteur->identifiant_ministere = $request->input('identifiant_ministere');
            $Visiteur->date_naissance = $request->input('date_naissance');
           // $Visiteur->adresse = $request->input('adresse');
            $Visiteur->num_telephone = $request->input('num_telephone');
            //$Visiteur->class = $request->input('class');
            $Visiteur->photo_url = $request->input('photo_url');
            $Visiteur->email = $request->input('email');
            $Visiteur->date_emission = $request->input('date_emission');
            //$Visiteur->nom_parent = $request->input('nom_parent');
            //$Visiteur->prenom_parent = $request->input('prenom_parent');
            $Visiteur->cin_parent = $request->input('cin_parent');
            $Visiteur->cin = $request->input('cin');
            $Visiteur->active = $request->input('active');
            $Visiteur->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('Visiteur/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $Visiteur->medias()->save($media);
            }

            $Visiteur->save();
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
        Visiteur::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
