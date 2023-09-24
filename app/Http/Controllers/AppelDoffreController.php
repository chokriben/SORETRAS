<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppelDoffre;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB; // Add this import statement at the top of the file
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AppelDoffreController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 10)
    {

        $Appel_doffres = AppelDoffre::paginate($perPage);
        $Appel_doffres->each(
            function ($Appel_doffre, $key) {
                $user = $Appel_doffre->user;
                $ville = $Appel_doffre->ville;
            }
        );

        if ($request->page) {
            $Appel_doffres = new LengthAwarePaginator($Appel_doffres, count($Appel_doffres), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'Appel_doffres' => $Appel_doffres,
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

                'name_' . $language => 'max:255|required',
                'description_' . $language => 'max:255|required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {
            $Appel_doffre = new AppelDoffre();


            foreach ($langages as $language) {
                $Appel_doffre->translateOrNew($language)->name = $request->input('name_' . $language);
                $Appel_doffre->translateOrNew($language)->description = $request->input('description_' . $language);
            }

            $Appel_doffre->date_debut = $request->input('date_debut');
            $Appel_doffre->date_fin = $request->input('date_fin');
            $Appel_doffre->user_id = $request->input('user_id');
            $Appel_doffre->ville_id = $request->input('ville_id');
            $Appel_doffre->save();
            if ($request->hasFile('file')) {
                $allowedExtensions = ['jpg', 'png', 'jpeg', 'csv', 'txt', 'xls', 'xlsx', 'pdf'];
                $maxFileSize = 2048; // 2048 kilobytes (2 megabytes)

                $fileValidator = Validator::make($request->all(), [
                    'file' => 'mimes:' . implode(',', $allowedExtensions) . '|max:' . $maxFileSize,
                    // Ajoutez d'autres règles de validation pour les champs de votre formulaire si nécessaire
                ]);

                if ($fileValidator->fails()) {
                    return response()->json([
                        "success" => false,
                        "errorsValidation" => $fileValidator->messages()
                    ], 400);
                }



                if ($request->hasFile('file')) {
                    // Handle file upload and media saving
                    $file = $request->file('file');
                    $fileExtension = $file->getClientOriginalExtension();
                    $fileSrc = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('uploads', $fileSrc, 'public');

                    DB::beginTransaction();

                    try {
                        // Create and save the media instance
                        $media = new Media([
                            'legende' => 'Appel_doffre',
                            'type' => $fileExtension,
                            'src' => $fileSrc,
                            'path' => '/storage/' . $filePath
                        ]);
                        $media->save();

                        // Associate the media with the presentation
                        $Appel_doffre->medias()->save($media);

                        DB::commit();

                        // Return a response indicating successful file upload
                        return response()->json(['message' => 'File uploaded successfully']);
                    } catch (\Exception $e) {
                        DB::rollback();

                        return response()->json([
                            "success" => false,
                            "message" => "Error occurred while saving the media.",
                        ], 500);
                    }
                }


                $Appel_doffre->save();

                return response()->json(
                    [
                        "success" => true,
                        "message" => "insertion est effectuée avec success",
                    ],
                    200
                );
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $Appel_doffre = AppelDoffre::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "Appel_doffre" => $Appel_doffre,
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

            $Appel_doffre = AppelDoffre::findOrFail($request->id);
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {
                $Appel_doffre->translateOrNew($language)->name = $request->input('name_' . $language);
                $Appel_doffre->translateOrNew($language)->description = $request->input('description_' . $language);
            }

            $Appel_doffre->date_debut = $request->input('date_debut');
            $Appel_doffre->date_fin = $request->input('date_fin');
            $Appel_doffre->user_id = $request->input('user_id');
            $Appel_doffre->ville_id = $request->input('ville_id');
            $Appel_doffre->active = $request->input('active');
            $Appel_doffre->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('AppelDoffre/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $Appel_doffre->medias()->save($media);
            }

            $Appel_doffre->save();
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
        AppelDoffre::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
