<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recrutement;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;


class RecrutementController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 100)
    {

        $Recrutements = Recrutement::paginate($perPage);
        $Recrutements->each(
            function ($Recrutement, $key) {
                $user = $Recrutement->user;
                $ville = $Recrutement->ville;
            }
        );

        if ($request->page) {
            $Recrutements = new LengthAwarePaginator($Recrutements, count($Recrutements), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'Recrutements' => $Recrutements,
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
            $Recrutement = new Recrutement();


            foreach ($langages as $language) {
                $Recrutement->translateOrNew($language)->name = $request->input('name_' . $language);
                $Recrutement->translateOrNew($language)->description = $request->input('description_' . $language);
            }

            $Recrutement->date_debut = $request->input('date_debut');
            $Recrutement->date_fin = $request->input('date_fin');
            $Recrutement->user_id = $request->input('user_id');
            $Recrutement->ville_id = $request->input('ville_id');
            $Recrutement->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('Recrutements/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $Recrutement->medias()->save($media);
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
        $Recrutement = Recrutement::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "Recrutement" => $Recrutement,
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

            $Recrutement = Recrutement::findOrFail($request->id);
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {
                $Recrutement->translateOrNew($language)->name = $request->input('name_' . $language);
                $Recrutement->translateOrNew($language)->description = $request->input('description_' . $language);
            }

            $Recrutement->date_debut = $request->input('date_debut');
            $Recrutement->date_fin = $request->input('date_fin');
            $Recrutement->user_id = $request->input('user_id');
            $Recrutement->ville_id = $request->input('ville_id');
            $Recrutement->active = $request->input('active');
            $Recrutement->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('Recrutement/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $Recrutement->medias()->save($media);
            }

            $Recrutement->save();
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
        Recrutement::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
