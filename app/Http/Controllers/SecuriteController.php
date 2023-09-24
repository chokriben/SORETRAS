<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Securite;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;


class SecuriteController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 10)
    {

        $Securites = Securite::paginate($perPage);
        $Securites->each(
            function ($Securite, $key) {
                $user = $Securite->user;
                $ville = $Securite->ville;
            }
        );

        if ($request->page) {
            $Securites = new LengthAwarePaginator($Securites, count($Securites), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'Securites' => $Securites,
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
        $Securite = Securite::first(); // Get the first row from the `settings` table
    
        if (!$Securite) { // If there is no row in the `settings` table
            $Securite = new Securite(); // Create a new instance of the `Setting` model
        }

        foreach ($langages as $language) {

            $validator = Validator::make($request->all(), [

                'name_' . $language =>  'max:255|required',
                'description_' . $language =>  'max:255|required',
            ]);
        

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } 
                $Securite->translateOrNew($language)->name = $request->input('name_' . $language);
                $Securite->translateOrNew($language)->description = $request->input('description_' . $language);
            }

         
           $Securite->libelle = $request->input('libelle');
            $Securite->save();

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
                $Securite->medias()->save($media);
            }

            return response()->json(
                [
                    "success" => true,
                    "message" => "insertion est effectuée avec success",
                ],
                200
            );
        }
    

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $Securite = Securite::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "Securite" => $Securite,
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

            $Securite = Securite::findOrFail($request->id);
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {
                $Securite->translateOrNew($language)->name = $request->input('name_' . $language);
                $Securite->translateOrNew($language)->description = $request->input('description_' . $language);
            }

                      
           $Securite->libelle = $request->input('libelle');
            $Securite->active = $request->input('active');
            $Securite->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('Securite/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $Securite->medias()->save($media);
            }
   
            $Securite->save();
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
  
}
