<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evenement;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB; // Add this import statement at the top of the file
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class EvenementController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 100)
    {

        $Evenements = Evenement::paginate($perPage);
        $Evenements->each(
            function ($Evenement, $key) {
                $user = $Evenement->user;
                $ville = $Evenement->ville;
            }
        );

        if ($request->page) {
            $Evenements = new LengthAwarePaginator($Evenements, count($Evenements), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'Evenements' => $Evenements,
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

            if ($validator->fails()) {
                return response()->json([
                    "success" => false,
                    "errorsValidation" => $validator->messages()
                ], 400);
            }
        }

        $Evenement = new Evenement();

        foreach ($langages as $language) {
            $Evenement->translateOrNew($language)->name = $request->input('name_' . $language);
            $Evenement->translateOrNew($language)->description = $request->input('description_' . $language);
        }

        $Evenement->date_debut = $request->input('date_debut');
        $Evenement->date_fin = $request->input('date_fin');
        $Evenement->user_id = $request->input('user_id');
        $Evenement->ville_id = $request->input('ville_id');

        try {
            $Evenement->save();
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Error occurred while saving the Evenement.",
                "error" => $e->getMessage()
            ], 500);
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
                    'legende' => 'evenements',
                    'type' => $fileExtension,
                    'src' => $fileSrc,
                    'path' => '/storage/' . $filePath
                ]);
                $media->save();

                // Associate the media with the presentation
                $Evenement->medias()->save($media);

                DB::commit();

                // Return a response indicating successful file upload
                return response()->json(['message' => 'File uploaded successfully']);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    "success" => false,
                    "message" => "Error occurred while saving the media.",
                    "error" => $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            "success" => true,
            "message" => "Insertion est effectuée avec succès",
        ], 200);
    }



    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $Evenement = Evenement::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "Evenement" => $Evenement,
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

            $Evenement = Evenement::findOrFail($request->id);
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {
                $Evenement->translateOrNew($language)->name = $request->input('name_' . $language);
                $Evenement->translateOrNew($language)->description = $request->input('description_' . $language);
            }

            $Evenement->date_debut = $request->input('date_debut');
            $Evenement->date_fin = $request->input('date_fin');
            $Evenement->ville_id = 1;
            $Evenement->active = $request->input('active');
            $Evenement->save();

            //file
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
                            'legende' => 'presentations',
                            'type' => $fileExtension,
                            'src' => $fileSrc,
                            'path' => '/storage/' . $filePath
                        ]);
                        $media->save();

                        // Associate the media with the presentation
                        $Evenement->medias()->save($media);

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
            }

            $Evenement->save();
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
        Evenement::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
