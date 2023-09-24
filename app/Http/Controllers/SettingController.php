<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use \Astrotomic\Translatable\Locales;

class SettingController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $setting = Setting::first();

        return response()->json(
            [
                'success' => true,
                'message' => 'selection est effectuée avec success',
                'setting' => $setting,
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

        $setting = Setting::first(); // Get the first row from the `settings` table

        if (!$setting) { // If there is no row in the `settings` table
            $setting = new Setting(); // Create a new instance of the `Setting` model
        }

        foreach ($langages as $language) {
            $validator = Validator::make($request->all(), [
                'raison_sociale_' . $language =>  'max:255|required',
                'adresse_' . $language =>  'max:255|required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "success" => false,
                    "errorsValidation" => $validator->messages()
                ], 400);
            }

            $setting->translateOrNew($language)->raison_sociale = $request->input('raison_sociale_' . $language);
            $setting->translateOrNew($language)->adresse = $request->input('adresse_' . $language);
        }

        $setting->email = $request->input('email');
        $setting->num_tel_p = $request->input('num_tel_p');
        $setting->num_tel_s = $request->input('num_tel_s');
        $setting->fax_p = $request->input('fax_p');
        $setting->fax_s = $request->input('fax_s');
        $setting->facebook = $request->input('facebook');
        $setting->youtube = $request->input('youtube');
        $setting->twitter = $request->input('twitter');
        $setting->save();

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
        $setting = Setting::findOrFail($request->id);
        return response()->json(
            [
                "success" => true,
                "message" => "Selection est effectuée avec success",
                "setting" => $setting,
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

                'raison_sociale_' . $language =>  'max:255|required',
                'adresse_' . $language =>  'max:255|required',
            ]);
        }
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "errorsValidation" => $validator->messages()
            ], 400);
        } else {

            $setting = Setting::findOrFail($request->id);
            $langages = app(Locales::class)->all();

            foreach ($langages as $language) {
                $setting->translateOrNew($language)->raison_sociale = $request->input('raison_sociale_' . $language);
                $setting->translateOrNew($language)->adresse = $request->input('adresse_' . $language);
            }


            $setting->email = $request->input('email');
            $setting->num_tel_p = $request->input('num_tel_p');
            $setting->num_tel_s = $request->input('num_tel_s');
            $setting->fax_p = $request->input('fax_p');
            $setting->fax_s = $request->input('fax_s');
            $setting->facebook = $request->input('facebook');
            $setting->youtube = $request->input('youtube');
            $setting->twitter = $request->input('twitter');
            $setting->save();

            $setting->save();
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
