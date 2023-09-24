<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunicationMedia;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;


class CommunicationMediaController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 10)
    {

        $communicationMedias = CommunicationMedia::paginate($perPage);
        $communicationMedias->each(
            function ($communicationMedia, $key) {
                $user = $communicationMedia->user;
            }
        );

        if ($request->page) {
            $communicationMedias = new LengthAwarePaginator($communicationMedias, count($communicationMedias), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'communicationMedias' => $communicationMedias,
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
            $communicationMedia = new CommunicationMedia();


            foreach ($langages as $language) {
                $communicationMedia->translateOrNew($language)->name = $request->input('name_' . $language);
                $communicationMedia->translateOrNew($language)->description = $request->input('description_' . $language);
            }


            $communicationMedia->user_id = $request->input('user_id');

            $communicationMedia->save();

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
                $communicationMedia->medias()->save($media);
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
        $communicationMedia = CommunicationMedia::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "communicationMedia" => $communicationMedia,
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

            $communicationMedia = CommunicationMedia::findOrFail($request->id);
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {
                $communicationMedia->translateOrNew($language)->name = $request->input('name_' . $language);
                $communicationMedia->translateOrNew($language)->description = $request->input('description_' . $language);
            }
            $communicationMedia->user_id = $request->input('user_id');
            $communicationMedia->active = $request->input('active');
            $communicationMedia->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('CommunicationMedia/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $communicationMedia->medias()->save($media);
            }

            $communicationMedia->save();
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
        CommunicationMedia::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
