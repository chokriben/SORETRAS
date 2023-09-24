<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeAbonnement;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;


class TypeAbonnementController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 10)
    {

        $TypeAbonnements = TypeAbonnement::paginate($perPage);
        $TypeAbonnements->each(
            function ($TypeAbonnement, $key) {
                $abonnement = $TypeAbonnement->abonnement;
               
            }
        );

        if ($request->page) {
            $TypeAbonnements = new LengthAwarePaginator($TypeAbonnements, count($TypeAbonnements), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'TypeAbonnements' => $TypeAbonnements,
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

                'labelle_' . $language =>  'max:255|required',
              
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            $TypeAbonnement = new TypeAbonnement();


            foreach ($langages as $language) {
                $TypeAbonnement->translateOrNew($language)->labelle = $request->input('labelle_' . $language);
                
            }

           // $TypeAbonnement->abonnement_id = $request->input('abonnement_id');
            $TypeAbonnement->save();

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
                $TypeAbonnement->medias()->save($media);
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
        $TypeAbonnement = TypeAbonnement::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "TypeAbonnement" => $TypeAbonnement,
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

                'labelle_' . $language =>  'max:255|required',
            
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {

            $TypeAbonnement = TypeAbonnement::findOrFail($request->id);
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {
                $TypeAbonnement->translateOrNew($language)->labelle = $request->input('labelle_' . $language);
                
            }

           // $TypeAbonnement->abonnement_id = $request->input('abonnement_id');
            $TypeAbonnement->active = $request->input('active');
            $TypeAbonnement->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('TypeAbonnement/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $TypeAbonnement->medias()->save($media);
            }

            $TypeAbonnement->save();
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
        TypeAbonnement::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
