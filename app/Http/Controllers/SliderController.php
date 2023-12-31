<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;
use Illuminate\Pagination\LengthAwarePaginator;


class SliderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $perPage = 10)
    {

        $Sliders = Slider::paginate($perPage);
        $Sliders->each(
            function ($Slider, $key) {
                $user = $Slider->user;
            }
        );

        if ($request->page) {
            $Sliders = new LengthAwarePaginator($Sliders, count($Sliders), $perPage, $request->page);
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'Sliders' => $Sliders,
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
            $Slider = new Slider();


            foreach ($langages as $language) {
                $Slider->translateOrNew($language)->name = $request->input('name_' . $language);
                $Slider->translateOrNew($language)->description = $request->input('description_' . $language);
            }

           
            $Slider->user_id = $request->input('user_id');
          
            $Slider->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('sliders/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $Slider->medias()->save($media);
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
        $Slider = Slider::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "Slider" => $Slider,
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

            $Slider = Slider::findOrFail($request->id);
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {
                $Slider->translateOrNew($language)->name = $request->input('name_' . $language);
                $Slider->translateOrNew($language)->description = $request->input('description_' . $language);
            }

            
            $Slider->user_id = $request->input('user_id');
            $Slider->active = $request->input('active');
            $Slider->save();

            //file
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $allowedPhotoExtension = ['jpg', 'png', 'jpeg', 'gif'];
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $checkPhoto = in_array($extension, $allowedPhotoExtension);

                if ($checkPhoto) {
                    $filename = $file->store('Slider/photos', 'ftp');
                    $media = new Media(
                        [
                            'legende' => 'legende',
                            'type' => '1',
                            'src' => $filename
                        ]
                    );
                }
                $Slider->medias()->save($media);
            }

            $Slider->save();
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
        Slider::findOrFail($request->id)->delete();

        return response()->json(
            [
                "success" => true,
                "message" => "suppression est effectuée avec success",
            ],
            200
        );
    }
}
