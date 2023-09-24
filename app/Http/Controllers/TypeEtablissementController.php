<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TypeEtablissement;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;


class TypeEtablissementController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 10)
    {

        $TypeEtablissements = TypeEtablissement::paginate($perPage);
        $TypeEtablissements->each(
            function ($TypeEtablissement, $key) {
                $etablissement = $TypeEtablissement->etablissement;
              
            }
        );

        if ($request->page) {
            $TypeEtablissements = new LengthAwarePaginator($TypeEtablissements, count($TypeEtablissements), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'TypeEtablissements' => $TypeEtablissements,
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
            $TypeEtablissement = new TypeEtablissement();


            foreach ($langages as $language) {
                $TypeEtablissement->translateOrNew($language)->labelle = $request->input('labelle_' . $language);
                
            }

           // $TypeEtablissement->etablissement_id = $request->input('etablissement_id');
            $TypeEtablissement->save();

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
                $TypeEtablissement->medias()->save($media);
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
        $TypeEtablissement = TypeEtablissement::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "TypeEtablissement" => $TypeEtablissement,
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

            $TypeEtablissement = TypeEtablissement::findOrFail($request->id);
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {
                $TypeEtablissement->translateOrNew($language)->labelle = $request->input('labelle_' . $language);
                
            }

            //$TypeEtablissement->etablissement_id = $request->input('etablissement_id');
            $TypeEtablissement->active = $request->input('active');
            $TypeEtablissement->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('TypeEtablissement/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $TypeEtablissement->medias()->save($media);
            }

            $TypeEtablissement->save();
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
        TypeEtablissement::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
