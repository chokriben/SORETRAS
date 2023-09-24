<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ligne;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;


class LigneController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 1500)
    {

        $Lignes = Ligne::paginate($perPage);
        $Lignes->each(
            function ($Ligne, $key) {
            }
        );

        if ($request->page) {
            $Lignes = new LengthAwarePaginator($Lignes, count($Lignes), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'Lignes' => $Lignes,
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

            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            $Ligne = new Ligne();
            $insertedId = $Ligne->id;



            foreach ($langages as $language) {
                $Ligne->translateOrNew($language)->name = $request->input('name_' . $language);
            }
            $Ligne->cod = $request->input('code');
            $Ligne->gare_id = $request->input('gare');

            $Ligne->save();
            DB::table('ligne_station')->insert([
                'ligne_id' => $Ligne->id,
                'station_dep_id' => $request->input('stationdep'),
                'station_fin_id' => $request->input('stationar'),
                'gare_id' => $request->input('gare'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();


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
        $Ligne = Ligne::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "Ligne" => $Ligne,
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

            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {

            $Ligne = Ligne::findOrFail($request->id);
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {
                $Ligne->translateOrNew($language)->name = $request->input('name_' . $language);
            }

            // $Ligne->gouvernorat_id = $request->input('gouvernorat_id');
            //$Ligne->station_id = $request->input('station_id');
            $Ligne->active = $request->input('active');
            $Ligne->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('Ligne/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $Ligne->medias()->save($media);
            }

            $Ligne->cod = $request->input('code');
            $Ligne->gare_id = $request->input('gare');

            $Ligne->save();
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
        Ligne::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
