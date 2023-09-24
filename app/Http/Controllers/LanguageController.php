<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Astrotomic\Translatable\Locales;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function getAllLangs()
    {

        return response()->json(

            app(Locales::class)->all()

        );
    }


    public function getCurrentLang()
    {
        return response()->json(
            [
                app(Locales::class)->current()
            ],
            200
        );
    }
}
