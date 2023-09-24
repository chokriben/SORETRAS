<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ville;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;


class VilleController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 10)
    {

        $Villes = Ville::paginate($perPage);
        $Villes->each(
            function ($Ville, $key) {
                $user = $Ville->user;
                $ville = $Ville->ville;
            }
        );

        if ($request->page) {
            $Villes = new LengthAwarePaginator($Villes, count($Villes), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'Villes' => $Villes,
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

                'name_' . $language =>  'max:255|required',
                'description_' . $language =>  'max:255|required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            $Ville = new Ville();


            foreach ($langages as $language) {
                $Ville->translateOrNew($language)->name = $request->input('name_' . $language);
                $Ville->translateOrNew($language)->description = $request->input('description_' . $language);
            }

          
            //$Ville->gouvernorat_id = $request->input('gouvernorat_id');
            //$Ville->station_id = $request->input('station_id');
            $Ville->save();

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
                $Ville->medias()->save($media);
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
        $Ville = Ville::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "Ville" => $Ville,
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

                'name_' . $language =>  'max:255|required',
                'description_' . $language =>  'max:255|required',
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {

            $Ville = Ville::findOrFail($request->id);
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {
                $Ville->translateOrNew($language)->name = $request->input('name_' . $language);
                $Ville->translateOrNew($language)->description = $request->input('description_' . $language);
            }

           // $Ville->gouvernorat_id = $request->input('gouvernorat_id');
            //$Ville->station_id = $request->input('station_id');
            $Ville->active = $request->input('active');
            $Ville->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('Ville/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $Ville->medias()->save($media);
            }

            $Ville->save();
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
        Ville::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
